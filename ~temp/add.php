<?php
	$conn = new PDO("mysql:host=localhost;dbname=recommender", "root", "");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add film/album</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head
><body>
<?php
	if(isset($_POST["submit"]) && isset($_POST["typ"])) {
		$type = (preg_replace('/\s\s+/', ' ',$_POST["typ"]));

		$table = ($type == "film")? "films" : "albums";

		if($type == "film") {
			$stmt = $conn->prepare("INSERT INTO films (id, name, dir, cast) VALUES (NULL, :f1, :f2, :f3)");
			$t = "f";
		} elseif($type == "album") {
			$stmt = $conn->prepare("INSERT INTO albums (id, name, artist, year) VALUES (NULL, :f1, :f2, :f3)");
			$t = "a";
		} else {
			echo "Something went wrong, please try again.";
		}

		if(isset($stmt)) {
			$fields = array();			
			foreach($_POST as $f) {
				$fields[] = (preg_replace('/\s\s+/', ' ', $f));
			}

			try {
				$stmt->bindParam(':f1', $f1);
	    		$stmt->bindParam(':f2', $f2);
	    		$stmt->bindParam(':f3', $f3);

	    		$f1 = $fields[1];
	    		$f2 = $fields[2];
	    		$f3 = $fields[3];
				$stmt->execute();

			    echo ('Record added<br><a href="add.php?'.$t.'">Add another</a>');
			} catch(PDOException $e) {
				echo "Error: " . $e->getMessage();
			}
		}
	} else {
?>
	<div class="head">Add film or album</div><br>
	<input type="radio" class="typ" name="typ" id="~f" checked><label for="~f">Film</label>
	<input type="radio" class="typ" name="typ" id="~a" <?php if(isset($_GET["a"])) { echo "checked";} ?>><label for="~a">Album</label><br><br>Film
	<form action="" method="POST">
		<fieldset id="film" <?php if(isset($_GET["a"])) { echo ' disabled';} ?>>
			<input type="hidden" name="typ" value="film">
			Name: <input type="text" name="name" <?php if(isset($_GET["f"])) { echo 'autofocus="autofocus"';} ?>><br>
			Director: <input type="text" name="dir"><br>
			Cast: <input type="text" name="cast"><br>
			<input type="submit" name="submit" value="Add">
		</fieldset>
	</form> Album
	<form action="" method="POST">
		<fieldset id="album"<?php if(!isset($_GET["a"])) { echo ' disabled';} ?>>
			<input type="hidden" name="typ" value="album">
			Name: <input type="text" name="name" <?php if(isset($_GET["a"])) { echo 'autofocus="autofocus"';} ?>><br>
			Artist: <input type="text" name="artist"><br>
			Year: <input type="text" name="year"><br>
			<input type="submit" name="submit" value="Add">
		</fieldset>
	</form>
	<script type="text/javascript">
		 if(document.getElementsByClassName) {         
            let el = document.getElementsByClassName("typ");  
            el[0].onclick = function(){
                    document.getElementById("film").disabled = 0;
                    document.getElementById("album").disabled = 1;
            };            
            el[1].onclick = function(){
                    document.getElementById("album").disabled = 0;
                    document.getElementById("film").disabled = 1;
            };
        } 
	</script>
<?php } ?>
</body>
</html>
<?php $conn = null; ?>