<?php

namespace KMeans;

use \SplObjectStorage;
use \LogicException;
use \InvalidArgumentException;

class Space extends SplObjectStorage
{
	const SEED_DEFAULT  = 1;
	
	protected $dimention;
	
	public function __construct($dimention)
	{
		if ($dimention < 1)
			throw new LogicException("a space dimention cannot be null or negative");

		$this->dimention = $dimention;
	}
	
	public function newPoint(array $coordinates)
	{
		if (count($coordinates) != $this->dimention)
			throw new LogicException("(" . implode(',', $coordinates) . ") is not a point of this space");

		return new Point($this, $coordinates);
	}

	public function addPoint(array $coordinates, $data = null)
	{
		return $this->attach($this->newPoint($coordinates), $data);
	}

	public function attach($point, $data = null)
	{
		if (!$point instanceof Point)
			throw new InvalidArgumentException("can only attach points to spaces");

		return parent::attach($point, $data);
	}
	
	public function getDimention()
	{
		return $this->dimention;
	}
	
	public function solve($pusatKlaster, $seed = self::SEED_DEFAULT, $iterationCallback = null)
	{
		if ($iterationCallback && !is_callable($iterationCallback))
			throw new InvalidArgumentException("invalid iteration callback");
		
		$clusters = $this->initializeClusters($this->nbClusters($pusatKlaster), $pusatKlaster, $seed);
		
		if (count($clusters) == 1)
			return $clusters[0];

		$min = $this->iterate($clusters);
		
		$data[] = $clusters;
		$data[] = $min;
						
		return $data;
	}
	
	protected function nbClusters($pusatKlaster)
	{
		return $nbClusters = count($pusatKlaster);
	}
	
	protected function initializeClusters($nbClusters, $pusatKlaster, $seed)
	{
		if ($nbClusters <= 0)
			throw new InvalidArgumentException("invalid clusters number");
		
		switch ($seed) {
			case self::SEED_DEFAULT:
					
				for ($n=0; $n<$nbClusters; $n++)
					$clusters[] = $this->getClusters($this, $pusatKlaster[$n]);
				
				break;
				
			case self::SEED_DASV:
				
				$position = rand(1, count($this));
				for ($i=1, $this->rewind(); $i<$position && $this->valid(); $i++, $this->next());
				$clusters[] = new Cluster($this, $this->current()->getCoordinates());

				$distances = new SplObjectStorage;

				for ($i=1; $i<$nbClusters; $i++) {
					$sum = 0;

					foreach ($this as $point) {
						$distance = $point->getDistanceWith($point->getClosest($clusters));
						$sum += $distances[$point] = $distance;
					}

					$sum = rand(0, $sum);
					foreach ($this as $point) {
						if (($sum -= $distances[$point]) > 0)
							continue;

						$clusters[] = new Cluster($this, $point->getCoordinates());
						break;
					}
				}

				break;
		}
		
		return $clusters;
	}
	
	protected function getClusters($space, array $coordinates)
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
	
	protected function iterate($clusters)
	{
		
		$nbClusters = $this->nbClusters($clusters[0]);
		
		for ($n=0; $n<$nbClusters; $n++)
		{
			$min= null;
			foreach ($clusters as $id => $nilai)
				($min > $nilai[$n] || $min === null) && $min = $nilai[$n] AND $nid = $id;
			
			$hasIterate[$nid][$n] = $min;
		}
		
		ksort($hasIterate);
		
		return $hasIterate;
	}
	
	function checking($clusters, $points)
	{
		$newPKlaster = $this->getNewPusatKlaster($clusters, $points);
		
		return $newPKlaster;
	}
	
	protected function getNewPusatKlaster($clusters, $points)
	{
		$newPK = array();
		for ($n=0; $n<count($clusters[1]); $n++)
		{
			for ($i=0; $i<$this->dimention; $i++)
			{
				$data = null;
				foreach($clusters[1][$n] as $idKlaster => $isiKlaster)
					$data += $points[$idKlaster][$i];
				
				$newPK[$n][$i] = $data/count($clusters[1][$n]);
				
			}
		}
		
		return $newPK;
	}
}