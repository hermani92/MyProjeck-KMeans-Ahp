<?php

namespace KMeans;

use \IteratorAggregate;
use \Countable;
use \SplObjectStorage;

class Cluster extends Point implements IteratorAggregate, Countable
{
	protected $space;
	protected $points;
	
	public function __construct(Space $space, array $coordinates)
	{
		foreach ($space as $id => $nilai)
		{
			$data = null;
			for ($nb=0; $nb<count($coordinates); $nb++){
				$data += pow(($nilai[$nb] - $coordinates[$nb]),2);
			}
			
			$clusters[] = sqrt($data);
		}
		
		return $clusters;
	}
	
	public function getIterator()
	{
		return $this->points;
	}

	public function count()
	{
		return count($this->points);
	}
}