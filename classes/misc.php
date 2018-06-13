<?php
	class recoDB {
		private $db;

		function __construct(){
			$this->db = new DBel();
		}

		public function getRating($getBy, $fid, $uid = null){
			$ratings = array();

			if($getBy == "film") {
				if(count($fid) > 0) {
					$ratings = $this->db->q_with_array("SELECT rating FROM films WHERE id IN ", $fid);
				}
			} elseif ($getBy == "user") {
				$ratings = $this->db->q_with_array("SELECT rating FROM user_ratings WHERE userid = 1 AND movieid IN ", $fid);
			}

			return array_column($ratings, "rating");
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
?>