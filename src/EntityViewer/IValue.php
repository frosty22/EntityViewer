<?php

namespace EntityViewer;

/**
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
interface IValue {


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @return string
	 */
	public function getValue();

}