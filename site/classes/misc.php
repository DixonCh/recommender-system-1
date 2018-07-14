<?php
	function avg($array) {
		$avg = 0;
		foreach($array as $el) {
			$avg += $el;
		}
		return ($avg/count($array));
	}

	class recoDB {
		private $db;

		function __construct(){
			$this->db = new DBel();
		}

		public function getRating($getBy, $fid, $uid = null){
			$r = array();
			if($getBy == "film") {
				if(count($fid) > 0) {
					$ratings = $this->db->q_with_array("SELECT movieid, rating FROM user_ratings WHERE movieid IN ", $fid);

					foreach($ratings as $rating){
						if(!in_array($rating["movieid"], $r)) {
							$r[$rating["movieid"]][] = $rating["rating"];
						}
					}

					$rating = array_fill_keys($fid, 0);

					foreach(array_keys($r) as $r_) {
						$rating[$r_-1] = avg($r[$r_]);
					}
					$r = $rating;
				}
			} elseif ($getBy == "user") {
				$add = (!empty($uid))? "userid = " . $uid . " AND " : "";
				$ratings = $this->db->q_with_array("SELECT movieid, rating FROM user_ratings WHERE " . $add . "movieid IN ", $fid);

				foreach($ratings as $rating) {
					$r[$rating["movieid"]] = $rating["rating"];
				}
			}
			return $r;
		}

		public function getGenresByMovieIds($ids) {
			$genre = $this->db->q_with_array("SELECT movieid, genrename FROM movie_genres JOIN movie_genre_relations USING (genreid) WHERE movieid IN ", $ids);

			$genres = array();

			for($i=0;$i<count($genre);$i++) {
				if(!isset($genres[$genre[$i]["movieid"]])) {
					$genres[$genre[$i]["movieid"]] = $genre[$i]["genrename"];
					$genre[$i] = null;
				}
			}

			foreach($genre as $g) {
				if(!empty($g)) {
					$genres[$g["movieid"]] .= ", " . $g["genrename"];
				}
			}

			return $genres;
		}
}

	class formMaker {
		public function create($what, $how) {
			$form = '<div style="text-align:center;"><form id="'. $what .'" method="'.$how[0].'" action="'.$how[1].'">';
			if($what == "login") {
				$form .= <<<EOT
<label>Username:</label><div><input type="" name="username" autocomplete="off" class="input"></div><label>Password:</label><div><input type="password" name="password" class="input" class=""></div><div style="text-align:center;"><input type="submit" name="submitbtn" value="Sign in"></div></form></div>
EOT;
			} elseif($what == "register") {
				$form .= <<<EOT
<label>First Name:</label><div><input type="" name="fname" class="input"></div>
<label>Last Name:</label><div><input type="" name="lname" class="input"></div>
<label>Username:</label><div><input type="" name="username" class="input"></div>
<label>Email</label><div><input type="email" name="email" class="input"></div>
<label>Password:</label><div>
<input type="password" name="password" class="input"></div>
<div style="text-align:center;"><input type="submit" name="submitbtn" value="Sign up"></div></form></div>
EOT;
			}

			return $form;
		}
	}

	function prepareFilmList($f) {
		$recodb = new recoDB();
		$usrid = (!empty($_SESSION["usrid"]))? $_SESSION["usrid"] : null;
		$genres = $recodb->getGenresByMovieIds(array_column($f, "movieid"));

		$film_ids = array_keys($genres);

		$usr_stars = (!empty($usrid))? $recodb->getRating("user", $film_ids, $usrid) : null;
		$stars = $recodb->getRating("film", $film_ids);

		$user_ratings = array_fill_keys($film_ids, 0);

		foreach(array_keys($user_ratings) as $v) {
			if(isset($usr_stars[$v])) {
				$user_ratings[$v] = $usr_stars[$v];
			}
		}

		foreach(array_keys($f) as $f_) {
		    $title = number_format((float)$stars[$f[$f_]["movieid"]-1], 2, '.', '');
		    $width = $stars[$f[$f_]["movieid"]-1] * 16;
		    $fid = $f[$f_]["movieid"];
		    $original = "";

			if($f[$f_]['originalTitle'] != $f[$f_]['primaryTitle']) {
				$original = '<br>(' . $f[$f_]['originalTitle'] . ')';
			}

			// $json = url_get_contents('http://www.omdbapi.com/?apikey=bbcbf298&i='.$f[$f_]["imdbID"]);

			// $obj = json_decode($json, true);

			// $img = ($obj["Poster"] != "N/A")?$obj["Poster"]:null;

			$img = "";

			echo <<<EOT
<div id="ffi"><img src="{$img}"><div class="title">{$f[$f_]['primaryTitle']}{$original}</div><div class="title">{$f[$f_]['startYear']} {$genres[$fid]}</div><div class="user-rating"><span class="t">Your Rating:</span><p><span class="c-rating" name="ffi-{$f[$f_]['movieid']}"></span></p></div><div class="title"><p><span class="stars" title="{$title}"><span style="width: {$width}px;"></span></span></p></div></div>
EOT;
		}			
		echo "</div>";
		return $user_ratings;
	}

	function url_get_contents ($Url) {
	    if (!function_exists('curl_init')){ 
	        die('CURL is not installed!');
	    }
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $Url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    $output = curl_exec($ch);
		    curl_close($ch);
		    return $output;
	}

	function recommend(){
		$usrid = $_SESSION["usrid"];
		$command = escapeshellcmd("python __pyscripts/ratings.py " . $usrid);
		$command .= " 2>&1";

		$output = shell_exec($command);
		if($output[0] == "[") {
			$filtered = explode("], [", substr($output, 2, -3));

			$predictions = array();

			foreach($filtered as $fi) {
				$pr = explode(",", $fi);
				$predictions[] = array($pr[0], $pr[1]); 
			}
		} else {
			$predictions = 0;
		}
		return $predictions;
	}

?>