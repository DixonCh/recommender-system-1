<?php 
	require_once("config.php");
	require_once("incls/header.php");

	$dbel = new DBel();
	
	$n = (isset($_GET["i"]))? $_GET["i"] : 25;
	$f = $dbel->q("SELECT * FROM films LIMIT :nu", 1, array(":nu" => (int)($n)));

	if(!empty($f)) {
?><div class="head">Here's a list of films we have:</div><br><div>Showing <?php echo($n); ?> Movies &middot; <a href="?i=50">50</a> &middot; <a href="?i=100">100</a> &middot; <a href="?i=200">200</a> &middot; <a href="?i=500">500</a> &middot; <a href="?i=1000">1000</a></div><br><?php
		$recodb = new recoDB();

		$genres = $recodb->getGenresByMovieIds(array_column($f, "id"));
		$film_ids = array_keys($genres);

		$stars = $recodb->getRating("film", $film_ids);
		$usr_stars = $recodb->getRating("user", $film_ids);

		for($i=0;$i<count($f);$i++) {
		    $title = number_format((float)$stars[$i], 2, '.', '');
		    $width = $stars[$i] * 16;
		    $fid = $f[$i]["id"];
			echo <<<EOT
<div id="ffi-card"><div class="title">{$f[$i]['name']}</div><div class="title">{$f[$i]['year']} {$genres[$fid]}</div><div class="user-rating"><span class="t">Your Rating:</span><p><span class="c-rating" name="ffi-{$f[$i]['id']}"></span></p></div><p><span class="stars" title="{$title}"><span style="width: {$width}px;"></span></span></p></div>
EOT;
	    }
	} else {
		echo <<<EOT
Please <a href=".">try again</a> or check later.
EOT;
	}
?><script type="text/javascript">var s = [<?php echo implode(', ', $usr_stars); ?>]</script><?php require_once("incls/footer.php");?>