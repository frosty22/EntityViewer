<?php

namespace EntityViewer;
use Ale\Entities\BaseEntity;
use EntityMetaReader\ColumnReader;
use EntityMetaReader\EntityReader;
use Nette\Security\User;

/**
 *
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class EntityViewer extends \Ale\Application\UI\Control {


	/**
	 * List of entities to show
	 * @var array
	 */
	private $entities = array();


	/**
	 * Additional columns
	 * @var array
	 */
	private $columns = array();


	/**
	 * @var EntityReader
	 */
	private $entityReader;


	/**
	 * @var User
	 */
	private $user;


	/**
	 * @param EntityReader $entityReader
	 */
	public function setEntityReader(EntityReader $entityReader)
	{
		$this->entityReader = $entityReader;
	}


	/**
	 * @param User $user
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}


	/**
	 * Add column
	 * @param string $name
	 * @param string $value
	 */
	public function addColumn($name, $value)
	{
		$this->columns[] = $this->createValue($name, $value);
	}

	/**
	 * Add entity for display
	 * @param BaseEntity $entity
	 */
	public function addEntity(BaseEntity $entity)
	{
		$this->entities[] = $entity;
	}


	/**
	 * @param BaseEntity $entity
	 */
	public function render(BaseEntity $entity = NULL)
	{
		if ($entity !== NULL)
			$this->addEntity($entity);

		$template = $this->createTemplate();
		$template->values = $this->getValues();
		$template->render();
	}


	/**
	 * Get values from entities like associative array
	 * @throws InvalidStateException
	 * @return Value[]
	 */
	private function getValues()
	{
		$values = array();

		if (count($this->entities)) {

			if (!$this->entityReader)
				throw new InvalidStateException("For reading entity metadata must be set EntityReader.");

			foreach ($this->entities as $entity) {
				$columns = $this->entityReader->getEntityColumns($entity);
				foreach ($columns as $column) {
					if ($this->canShow($column)) {
						$name = $this->getColumnName($column);
						$value = $this->getValue($column, $entity);
						$values[] = $this->createValue($name, $value, $column);
					}
				}
			}
		}

		return array_merge($values, $this->columns);
	}


	/**
	 * @param ColumnReader $column
	 * @return string
	 */
	protected function getColumnName(ColumnReader $column)
	{
		$name = $column->getAnnotation('EntityMetaReader\Mapping\Name', TRUE, $column->getName());
		/** @var Name $name */

		return $name->getName();
	}


	/**
	 * @param ColumnReader $column
	 * @param BaseEntity $entity
	 * @return string
	 */
	protected function getValue(ColumnReader $column, BaseEntity $entity)
	{
		$value = $entity->{$column->getName()};
		return $value;
	}


	/**
	 * @param ColumnReader $column
	 * @return bool
	 */
	protected function canShow(ColumnReader $column)
	{
		if (!$column->isValueType()) return FALSE;
		return $this->checkAccess($column);
	}


	/**
	 * Check access to column (for read of course)
	 * @param ColumnReader $column
	 * @return bool
	 */
	protected function checkAccess(ColumnReader $column)
	{
		$access = $column->getAnnotation('EntityMetaReader\Mapping\Access', TRUE);
		/** @var \EntityMetaReader\Mapping\Access $access */

		if (!$this->user) return $access->isReadable();
		return $access->checkReadAccess($this->user);
	}


	/**
	 * Create value render
	 * @param string $name
	 * @param string $value
	 * @param ColumnReader $column
	 * @return View
	 */
	protected function createValue($name, $value, ColumnReader $column = NULL)
	{
		return new Value($name, $value, $column);
	}

}