<?php

namespace EntityViewer;
use Ale\Entities\BaseEntity;

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
		$template->render();
	}


}