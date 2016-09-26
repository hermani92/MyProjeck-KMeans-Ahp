<?php

namespace KMeans;

use \ArrayAccess;
use \LogicException;

class Point implements ArrayAccess
{
	protected $space;
	protected $dimention;
	protected $coordinates;
	
	public function __construct(Space $space, array $coordinates)
	{
		$this->space       = $space;
		$this->dimention   = $space->getDimention();
		$this->coordinates = $coordinates;
	}
	
	public function offsetExists($offset)
	{
		return isset($this->coordinates[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->coordinates[$offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->coordinates[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->coordinates[$offset]);
	}
}