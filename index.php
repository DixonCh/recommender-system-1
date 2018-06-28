<?php 
	require_once("config.php");
	require_once("incls/header.php");

	$dbel = new DBel();
	
	$n = (isset($_GET["i"]))? $_GET["i"] : 25;
	$f = $dbel->q("SELECT * FROM films LIMIT :nu", 2, array(":nu" => $n));
	$usrid = (!empty($_SESSION["usrid"]))? $_SESSION["usrid"] : null;

	if(!empty($f)) {
?><div id="content"><div id="header"><div class="head">Here's a list of films we have:</div><br><div>Showing <?php echo($n); ?> Movies &middot; <a href="?i=50">50</a> &middot; <a href="?i=100">100</a> &middot; <a href="?i=200">200</a> &middot; <a href="?i=500">500</a> &middot; <a href="?i=1000">1000</a> <?php if(!empty($usrid)) {?>&middot; <a href="profile">My Ratings</a> &middot; <a href="login?out">Logout</a> <?php } ?></div></div><br><?php
		$res = prepareFilmList($f);

		$user_ratings = $res[0];
		$rating_ids = $res[1];
	} else {
		echo <<<EOT
Please <a href=".">try again</a> or check later.
EOT;
	}
?><script type="text/javascript">var s=[<?php echo implode(', ', $user_ratings); ?>];var rtid=[<?php echo implode(', ', $rating_ids); ?>];</script><?php require_once("incls/footer.php");?>