<?php

namespace AHP;

use \SplObjectStorage;
use \InvalidArgumentException;

class Rangking extends SplObjectStorage
{
	public function __construct($clusToR = array(), $nSkala = array()){
		
		foreach ($clusToR as $key => $value)
		{
			foreach ($clusToR as $key2 => $value2)
				($key == $key2) ? ($nilai[$key][$key2] = array_fill(0,3,1)) : ($nilai[$key][$key2] = $this->getNilai($value, $value2, $nSkala));
		}
		
		$hasilAHP = $this->getPembagianJumlh($this->getMatriks($nilai));
		
		//Pr($hasilAHP);
		//Matriks($nilai[0]);
	}
	
	public function getPembagianJumlh($nilai){
		
		foreach($nilai as $no => $row){
			$jumHasil = 0;
			foreach($row as $key => $value){
				foreach($value as $id => $isi){
					if($key == 'jumlah')
						continue;
					
					$hasil[$no][$key][$id]= $isi / $row['jumlah'][$id];
				}
				
				if($key == 'jumlah')
					continue;
				
					$hasil[$no][$key]['jumlah'] = array_sum($hasil[$no][$key]);
					$hasil[$no][$key]['prioritas'] = $hasil[$no][$key]['jumlah'] / 3; //manual input
					$hasil[$no][$key]['hasil'] = $hasil[$no][$key]['jumlah'] + $hasil[$no][$key]['prioritas'];
					$jumHasil += $hasil[$no][$key]['hasil'];
					$hasilAHP['prioritas'][$no][$key] = $hasil[$no][$key]['prioritas'];
			}
			$hasil[$no]['ratio'] = $jumHasil;
			//$hasilAHP['hasilRangking'][$no][$key] = $hasil[$no][$key]['prioritas'];
		}
		$hasilAHP[0] = $nilai;
		$hasilAHP[1] = $hasil;
		
		return $hasilAHP;
	}
	
	public function getMatriks($data){
		
		for($i=0;$i<3;$i++){ //manual
		
			foreach ($data as $idpembanding => $id)
				$jumlah[$i][$idpembanding] = 0;
		
			foreach ($data as $idpembanding => $id){
				foreach ($id as $keybanding => $key){
					$nilai[$i][$idpembanding][$keybanding] = $key[$i];
					$jumlah[$i][$keybanding] += $key[$i];
				}
			}
			$nilai[$i]['jumlah'] = $jumlah[$i];
		}
		
		return $nilai;
	}
	
	public function lebihBesar($v1,$v2,$nSkala){
		
		$selisih = $v1 - $v2;
		
		for($i=1;$i<count($nSkala);$i++){
			
			if($selisih > $nSkala[$i-1]['rentang'] AND $selisih <= $nSkala[$i]['rentang'])
				$nilai = $nSkala[$i]['nilai'];
		}
		return $nilai;
	}
	
	public function lebihKecil($v1,$v2,$nSkala){
		
		$selisih = $v2 - $v1;
		
		for($i=1;$i<count($nSkala);$i++){
			
			if($selisih > $nSkala[$i-1]['rentang'] AND $selisih <= $nSkala[$i]['rentang'])
				$nilai = 1 / $nSkala[$i]['nilai'];
		}
		return $nilai;
	}
	
	public function getNilai($value,$value2,$nSkala){
		
		if (count($value) <> count($value2))
			throw new InvalidArgumentException("invalid clusters number");
		
		for($i=0;$i<count($value);$i++){
			$pVal = $value[$i] <=> $value2[$i];
			($pVal == 0) ? ($nilai[$i] = 1) : (($pVal == 1) ? ($nilai[$i] = $this->lebihBesar($value[$i],$value2[$i],$nSkala)) : ($nilai[$i] = $this->lebihKecil($value[$i],$value2[$i],$nSkala)) );
		}
			
		/*Print_r($value);echo("</br>");
		Print_r($value2);echo("</br>");
		Print_r($nilai);echo("</br></br>");*/
		
		return $nilai;
	}
}