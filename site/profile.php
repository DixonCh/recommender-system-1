<?php 
	require_once("config.php");
	require_once("incls/header.php");

	if(!empty($_SESSION["usrid"])) {
		$usrid = $_SESSION["usrid"];
		$dbel = new DBel();

		//user activity log if you are interested
		$films = $dbel->q("SELECT * FROM user_ratings JOIN films ON user_ratings.movieid = films.movieid WHERE userid = :usr ORDER BY films.movieid", 1, array(":usr" => $usrid));
		if(!empty($films)) {
			$user_ratings = prepareFilmList($films);
		} else {
			echo 'You haven\'t rated any movie yet.';
		}

?><?php if(!empty($films)) { ?><script type="text/javascript">var s=[<?php echo implode(', ', $user_ratings); ?>];</script><?php 	}
		require_once("incls/footer.php");
	} else {
		//show an outsider
	}
?>