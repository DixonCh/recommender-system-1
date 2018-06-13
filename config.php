<?php
	require_once("classes/db.php");
	require_once("classes/misc.php");

	function redirect($to="index.php") {
		header("Location: " . $to);
		exit();
	}
?>
