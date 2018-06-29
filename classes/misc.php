<?php
	class recoDB {
		private $db;

		function __construct(){
			$this->db = new DBel();
		}

		public function getRating($getBy, $fid, $uid = null){
			$r = array();
			if($getBy == "film") {
				if(count($fid) > 0) {
					$ratings = $this->db->q_with_array("SELECT id, rating FROM films WHERE id IN ", $fid);
					$r = array_column($ratings, "rating");
				}
			} elseif ($getBy == "user") {
				$add = (!empty($uid))? "userid = " . $uid . " AND " : "";
				$ratings = $this->db->q_with_array("SELECT id, movieid, rating FROM user_ratings WHERE " . $add . "movieid IN ", $fid);
				
				foreach($ratings as $rating) {
					$r[$rating["movieid"]] = array("rating" => $rating["rating"], "id" => $rating["id"]);
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
<label>Username:</label><div><input type="" name="username" class="input"></div><label>Password:</label><div><input type="password" name="password" class="input" class=""></div><div style="text-align:center;"><input type="submit" name="submitbtn" value="Sign in"></div></form></div>
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
		$genres = $recodb->getGenresByMovieIds(array_column($f, "id"));

		$film_ids = array_keys($genres);

		$stars = $recodb->getRating("film", $film_ids);
		$usr_stars = (!empty($usrid))? $recodb->getRating("user", $film_ids, $usrid) : null;

		$user_ratings = $rating_ids = array_fill_keys($film_ids, 0);

		foreach(array_keys($user_ratings) as $v) {
			if(isset($usr_stars[$v])) {
				$user_ratings[$v] = $usr_stars[$v]["rating"];
			}
		}

		foreach(array_keys($rating_ids) as $v) {
			if(isset($usr_stars[$v])) {
				$rating_ids[$v] = $usr_stars[$v]["id"];
			}
		}

		for($i=0;$i<count($f);$i++) {
		    $title = number_format((float)$stars[$i], 2, '.', '');
		    $width = $stars[$i]* 16;
		    $fid = $f[$i]["id"];
			echo <<<EOT
<div id="ffi-card"><div class="title">{$f[$i]['name']}</div><div class="title">{$f[$i]['year']} {$genres[$fid]}</div><div class="user-rating"><span class="t">Your Rating:</span><p><span class="c-rating" name="ffi-{$f[$i]['id']}"></span></p></div><div class="title"><p><span class="stars" title="{$title}"><span style="width: {$width}px;"></span></span></p></div></div>
EOT;
		}
		return array($user_ratings, $rating_ids);
	}

?>