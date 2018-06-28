<?php		
	require_once("config.php");

	$_POST = json_decode(file_get_contents('php://input'), true);

	if(empty($_POST)) {
		redirect();
	} else {
		if(!empty($_SESSION["usrid"])) {
			$usrid = $_SESSION["usrid"];
			$dbel = new DBel();
			$rdid = ($_POST["rtid"] === 0)? NULL : $_POST["rtid"]; 
			$result = $dbel->q("INSERT INTO user_ratings (id,userid,movieid,rating) VALUES (:rt, :usrid,:fid,:r) ON DUPLICATE KEY UPDATE rating = :rating", 5, array(":rt"=>$rdid, ":usrid" => $usrid, ":fid"=>$_POST["fid"], ":r"=>$_POST["r"], ":rating"=>$_POST["r"]));
		} else {
			echo ("Sign up and start rating!");
		}
	}
?>