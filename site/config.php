<?php
	// define ("A", "db4free.net");
	// define ("B", "sharad46");
	// define ("C", "Xci45tsa_");
	// define ("D", "recommender");
	define ("A", "sql12.freesqldatabase.com");
	define ("B", "sql12247654");
	define ("C", "Ptw6aicQFD");
	define ("D", "sql12247654");

	session_start();
	
	$user = (empty($_SESSION["usrid"]))? 0:1;

	require_once("classes/db.php");
	require_once("classes/misc.php");

	function redirect($to="index.php") {
		header("Location: " . $to);
		exit();
	}
?>