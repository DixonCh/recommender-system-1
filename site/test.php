<?php
	require_once("config.php");

	$recommendations = recommend();
	print_r($recommendations);
?>