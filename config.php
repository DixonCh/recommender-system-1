<?php
	define ("A", "localhost");
	define ("B", "root");
	define ("C", "");
	define ("D", "recommender");

	session_start();

	require_once("classes/db.php");
	require_once("classes/misc.php");

	function redirect($to="index.php") {
		header("Location: " . $to);
		exit();
	}
?>
