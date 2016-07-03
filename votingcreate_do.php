<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/votingManager.php");
	$votingManager = new VotingManager();

	require_once("mapper/vorlesungManager.php");
	$vorlesungManager = new VorlesungManager();

	require_once("mapper/errorHandler.php");
	$errorHandler = new ErrorHandler();

	if(!isset($_POST["kursnummer"]) || !isset($_POST["thema"]) || !isset($_POST["frage"])
		|| !isset($_POST["a"]) || !isset($_POST["b"]) || !isset($_POST["c"]) || !isset($_POST["d"])
	) {
		$errorHandler->storeError("fehler", "Es wurden nicht alle POST Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}

	$kursnummer = htmlspecialchars($_POST["kursnummer"], ENT_QUOTES, "UTF-8");
	$vorlesung = $vorlesungManager->findBykursnummer($kursnummer);
	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		$errorHandler->storeError("fehler", "Sie haben nicht die Berechtigung ein Voting für diesen Kurs anzulegen.");
		header("Location: fehler.php");
		exit();
	}

	$thema = htmlspecialchars($_POST["thema"], ENT_QUOTES, "UTF-8");
	$frage = htmlspecialchars($_POST["frage"], ENT_QUOTES, "UTF-8");
	$a = htmlspecialchars($_POST["a"], ENT_QUOTES, "UTF-8");
	$b = htmlspecialchars($_POST["b"], ENT_QUOTES, "UTF-8");
	$c = htmlspecialchars($_POST["c"], ENT_QUOTES, "UTF-8");
	$d = htmlspecialchars($_POST["d"], ENT_QUOTES, "UTF-8");

	if(!empty($thema) && !empty($frage) && !empty($a) && !empty($b)) {

		do {
			$token = randomString(6);
		} while($votingManager->findByToken($token) != null);

		if(isset($_FILES["bild"]) && $_FILES["bild"]["error"] == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["bild"]["tmp_name"];
			$ext = end(explode(".", $_FILES["bild"]["name"]));
			$bild = $token . "." . $ext;
			move_uploaded_file($tmp_name, "pictures/" . $bild);
			echo $bild;
		} else {
			$bild = null;
		}

		$votingdaten = [
			"thema"      => $thema,
			"frage"      => $frage,
			"a"          => $a,
			"b"          => $b,
			"c"          => $c,
			"d"          => $d,
			"kursnummer" => $kursnummer,
			"bild"       => $bild,
			"token"      => $token
		];
		$voting = new Voting ($votingdaten);
		$votingManager->save($voting);
		header('Location: votinguebersicht.php?kursnummer=' . $kursnummer);
	} else {
		$errorHandler = new ErrorHandler();
		$errorHandler->storeError("votingcreate", "Error: Bitte alle Felder ausfüllen!");
		header('Location: votingcreate_form.php?kursnummer=' . $kursnummer);
	}


	function randomString($laenge) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for($i = 0; $i < $laenge; $i++) {
			$randstring .= $characters[rand(0, strlen($characters)-1)];
		}

		return $randstring;
	}
