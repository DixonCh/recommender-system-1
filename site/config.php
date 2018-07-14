<?php
	define ("A", "85.10.205.173:3306");
	define ("B", "sharad46");
	define ("C", "Xci45tsa_");
	define ("D", "recommender");

	session_start();
	
	$user = (empty($_SESSION["usrid"]))? 0:1;

	require_once("classes/db.php");
	require_once("classes/misc.php");

	function redirect($to="index.php") {
		header("Location: " . $to);
		exit();
	}
?>
