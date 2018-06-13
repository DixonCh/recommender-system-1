<?php		
	require_once("config.php");

	$_POST = json_decode(file_get_contents('php://input'), true);

	if(empty($_POST)) {
		redirect();
	} else {
		//session (for ajax requests)
		$dbel = new DBel();
		$result = $dbel->q("INSERT INTO user_ratings (id,userid,movieid,rating) VALUES (1,1,:fid,:r) ON DUPLICATE KEY UPDATE rating = :rating", 3, array(":fid"=>$_POST["fid"], ":r"=>$_POST["r"], ":rating"=>$_POST["r"]));
	}
?>