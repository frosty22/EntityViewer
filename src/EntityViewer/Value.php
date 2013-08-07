<?php

namespace EntityViewer;

use EntityMetaReader\ColumnReader;
use Nette\Object;

/**
 * Simple value
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Value extends Object implements IValue {


	const DATETIME_FORMAT = "j.n.Y H:i";

	/**
	 * @var string
	 */
	private $name;


	/**
	 * @var string
	 */
	private $value;


	/**
	 * @param string $name
	 * @param mixed $value
	 * @param ColumnReader $column
	 */
	public function __construct($name, $value, ColumnReader $column = NULL)
	{
		$this->name = $name;

		if ($value instanceof \DateTime) {
			$value = $value->format("j.n.Y H:i");
		}

		$this->value = $value;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}



}