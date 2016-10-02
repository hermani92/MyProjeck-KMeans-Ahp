<?php
include "src/config.php";
$config = new Config();
$db = $config->getConnection();
?>
<?php

function Pr($data = null){
	echo("<pre>");print_r($data);echo("</pre>");
}

function Matriks($data = array()){
	Pr($data);
	echo("<pre>");
	echo'<table style="text-align:center" border="0">';
	$decimals = 6;
	
	foreach ($data as $row) {
		echo "<tr>";
		echo"<td>[</td>";
		foreach ($row as $i) {
			if($i%1 == 0){
				echo '<td style="padding:5px">'.$i;echo "</td>";
			}else{
				echo '<td style="padding:5px">'. sprintf("%01.{$decimals}f", round($i, $decimals));echo "</td>";
			}
		}
		echo"<td>]</td>";
		echo "</tr>";
	}
	
	echo"</table></br>";
	echo("</pre>");
}

require_once "src/KMeans/Space.php";
require_once "src/KMeans/Point.php";
require_once "src/KMeans/Cluster.php";
require_once "src/AHP/Rangking.php";
require_once "src/AHP/Modul.php";

$points = [
	[65,80,80],[75,77,50],[66,68,75],
	[60,75,85],[60,80,75],[60,66,80],
	[80,85,66],[55,74,85],[77,88,80],
	[68,75,85],[73,73,65],[50,80,78],
	[78,79,84],[65,84,88],[66,77,76],
	[50,60,60],[90,90,95],[77,64,70],
	[83,75,80],[89,80,80],[57,80,78],
	[67,73,82],[83,93,90],[82,65,77],
	[64,70,67],[72,84,73],[67,66,66],
	[78,76,77],[88,70,78],[81,69,77],
	[80,80,83],[70,65,80],[69,74,79],
	[66,77,74],[76,76,69],[77,68,80],
	[92,80,88],[85,79,84],
];

$batasIterasi = 500;
$pusatKlaster = [
	[90,90,90],
	[80,80,82],
	[70,70,70],
	[65,65,65],
];

$space = new KMeans\Space(3);

foreach ($points as $coordinates)
	$space->addPoint($coordinates);

for ($n=1; $n<=$batasIterasi; $n++)
{
	$clusters[$n] = $space->solve($pusatKlaster);
	
	$savePK[$n] = $pusatKlaster;
	
	if(count($clusters) != 1){
		if($space->checking($clusters[$n][1], $clusters[$n-1][1]) == TRUE)
			Break;
	}
	
	$pusatKlaster = $space->getNewPusatKlaster($clusters[$n], $points, $savePK[$n]);
}

//Pr($savePK);
//Pr($clusters);
