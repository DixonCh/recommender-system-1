<?php
	require_once("config.php");
	echo ("PHP is working");
	$dbel = new DBel();
	$n = (isset($_GET["i"]))? $_GET["i"] : 10;
 	$f = $dbel->q("SELECT * FROM films LIMIT :nu", 2, array(":nu" => $n));
 	print_r($f);
?>