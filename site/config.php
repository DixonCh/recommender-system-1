<?php
	define ("A", "db4free.net:3306");
	define ("B", "sharad46");
	define ("C", "Xci45tsa_");
	define ("D", "recommender");
	echo("S");
	print(A.B.C.D);
	// define ("A", "sql12.freesqldatabase.com");
	// define ("B", "sql12247654");
	// define ("C", "Ptw6aicQFD");
	// define ("D", "sql12247654");	
	// define ("A", "localhost");
	// define ("B", "root");
	// define ("C", "");
	// define ("D", "recommender");

	session_start();
	
	$user = (empty($_SESSION["usrid"]))? 0:1;

	require_once("classes/db.php");
	require_once("classes/misc.php");

	function redirect($to="index.php") {
		header("Location: " . $to);
		exit();
	}
?>
