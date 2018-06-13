<?php
	class DBel {
		private $conn;
		private $stmt;

		function __construct(){
			$this->conn = new PDO("mysql:host=localhost;dbname=recommender;charset=utf8mb4", "root", "");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}

		public function q($st, $binder = 0, $binders = 0) {
			try {
				$this->stmt = $this->conn->prepare($st);

				if($binder > 0) {
					foreach(array_keys($binders) as $binder) {
						$this->stmt->bindParam($binder, $binders[$binder]);
					}
				}
				
				$this->stmt->execute();
			    $this->stmt->setFetchMode(PDO::FETCH_ASSOC); 
			    return $this->stmt->fetchAll();
			} catch(PDOException $e) {
	   			echo "Error: " . $e->getMessage();
			}

		}

		public function q_with_array($query, $ids) {
			$in = join(',', array_fill(0, count($ids), '?'));

			$bindarray = array(1 => $ids[0]);

			for($i=1;$i<count($ids);$i++) {
				$bindarray[] = $ids[$i];
			}

			$genre = $this->q($query . "(" . $in . ")", count($ids), $bindarray);

			return $genre;
		}

		function __destruct() {
        	$this->conn = null;
    	}
	}
?>