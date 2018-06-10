<?php
	class starRating {
		private $db;

		function __construct(){
			$this->db = new DBel();
		}

		public function getRating($getBy, $id){
			if($getBy == "film") {
				$ratings = $this->db->q("SELECT rating FROM films WHERE id = :m", 1, array(":m" => $id)); //add user specification once you have users database going on
				return $ratings[0]["rating"];
			} elseif ($getBy == "genre") {
				//
			}
		}
	}
?>