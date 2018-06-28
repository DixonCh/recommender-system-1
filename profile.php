<?php 
	require_once("config.php");
	require_once("incls/header.php");

	if(!empty($_SESSION["usrid"])) {
		$usrid = $_SESSION["usrid"];
		$dbel = new DBel();

		//user activity log if you are interested
		$films = $dbel->q("SELECT * FROM user_ratings JOIN films ON user_ratings.movieid = films.id WHERE userid = :usr ORDER BY films.id", 1, array(":usr" => $usrid));
		$res = prepareFilmList($films);

		$user_ratings = $res[0];
		$rating_ids = $res[1];

?><script type="text/javascript">var s=[<?php echo implode(', ', $user_ratings); ?>];var rtid=[<?php echo implode(', ', $rating_ids); ?>];</script><?php require_once("incls/footer.php");?>
<?php
	} else {
		//show an outsider
	}
?>