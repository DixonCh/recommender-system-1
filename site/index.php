<?php
 	require_once("config.php");
 	require_once("incls/header.php");
	
	$usrid = (!empty($user))? $_SESSION["usrid"] : null;
 
 	$dbel = new DBel();
 	
	$n = (isset($_GET["i"]))? $_GET["i"] : 250;
 	$films = $dbel->q("SELECT * FROM films LIMIT :nu", 2, array(":nu" => $n));

 	if(!empty($films)) {
?><div id="wrapper"><div id="content"><div id="header"><div class="head">Head</div><br><div>Showing <?php echo($n); ?> Movies &middot; <a href="?i=50">50</a> &middot; <a href="?i=100">100</a> &middot; <a href="?i=200">200</a> &middot; <a href="?i=500">500</a> &middot; <a href="?i=1000">1000</a> <?php if(!empty($usrid)) {?>&middot; <a href="profile.php">My Ratings</a> &middot; <a href="login.php?out">Logout</a> <?php } ?></div></div><div id="list"><?php
		$user_ratings = prepareFilmList($films);
		// if(isset($usrid)) {
		// 	$recommendations = recommend();
		// 	if(!empty($recommendations)) {
		// 		$r_s = array();
		// 		foreach($recommendations as $recommendation) {$r_s[] = $recommendation[0];}
		// 		$r = $dbel->q_with_array("SELECT * FROM films WHERE movieid IN ", $r_s, "movieid");
		// 	} else { echo "Start by rating films you have watched.";}
		// }
 	} else {
 		echo <<<EOT
Please <a href=".">try again</a> or check later.
EOT;
 	}
?><?php if(empty($user)) { echo '<div id="tscreen"><div class="modal"><span class="close">&times;</span><br><span class="message"></span> </div></div>'; } ?> </div><div id="sidebar">Recommended:<br><?php if(!empty($r)) {prepareFilmList($r); }?></div></div><script type="text/javascript">var s=[<?php echo implode(',', $user_ratings); ?>];</script><?php require_once("incls/footer.php");?> 