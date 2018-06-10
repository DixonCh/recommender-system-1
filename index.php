<?php require_once("config.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Recommendation System</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head><body>
<?php
	$dbel = new DBel();
	$n = (isset($_GET["i"]))? $_GET["i"] : 25;
	$fetched = $dbel->q("SELECT * FROM films LIMIT :nu", 1, array(":nu" => (int)($n)));
?>
	<div class="head">Here's a list of films we have:</div><br>
	<div>Showing <?php echo($n); ?> Movies &middot; <a href="?i=50">50</a> &middot; <a href="?i=100">100</a> &middot; <a href="?i=200">200</a> &middot; <a href="?i=500">500</a> &middot; <a href="?i=1000">1000</a></div><br>
<?php
	if(isset($fetched)) {
		$rating = new starRating();
	    foreach($fetched as $film) {
			$genre = $dbel->q("SELECT genrename FROM movie_genre_relations JOIN movie_genres USING (genreid) WHERE movieid = :id", 1, array(":id" => $film["id"]));

		    $genres = $genre[0]["genrename"];

		    for($i = 1; $i < count($genre); $i++) {
		    	$genres .= ", "; 
		    	$genres .= $genre[$i]["genrename"];
		    }

		    $stars = $rating->getRating("film", $film["id"]);

		    $title = number_format((float)$stars, 2, '.', '');
		    $width = $stars * 16;

			echo <<<EOT
<div id="elem"><div class="title">{$film['name']}</div>{$film['year']} {$genres}<div class="userrating"><span class="t">Your Rating:</span><p><span class="starRating"><input id="rating5" type="radio" name="rating" value="5">  <label for="rating5">5</label> <input id="rating4" type="radio" name="rating" value="4"><label for="rating4">4</label>  <input id="rating3" type="radio" name="rating" value="3"><label for="rating3">3</label>	<input id="rating2" type="radio" name="rating" value="2">	<label for="rating2">2</label><input id="rating1" type="radio" name="rating" value="1"> <label for="rating1">1</label></span></p></div><p><span class="stars" title="{$title}"><span style="width: {$width}px;"></span></span></p></div>
EOT;
	    }
	} else {
		//redirect
	}
?>
</body>
</html>