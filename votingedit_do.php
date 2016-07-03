<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/votingManager.php");
	require_once("mapper/vorlesungManager.php");

	require_once("mapper/errorHandler.php");

	if(!isset($_POST["id_voting"]) || !isset($_POST["thema"]) || !isset($_POST["frage"])
		|| !isset($_POST["a"]) || !isset($_POST["b"]) || !isset($_POST["c"]) || !isset($_POST["d"])) {
		$errorHandler->storeError("fehler", "Es wurden nicht alle POST Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}
	
	$id_voting = htmlspecialchars($_POST["id_voting"], ENT_QUOTES, "UTF-8");

	$votingManager = new VotingManager();
	$vorlesungManager = new VorlesungManager();
	
	$voting = $votingManager->findById_voting($id_voting);
	$vorlesung = $vorlesungManager->findBykursnummer($voting->kursnummer);
	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		$errorHandler->storeError("fehler", "Sie sind nicht dazu berechtigt dieses Voting zu bearbeiten.");
		header("Location: fehler.php");
		exit();
	}
	
	
	$thema = htmlspecialchars($_POST["thema"], ENT_QUOTES, "UTF-8");
	$frage = htmlspecialchars($_POST["frage"], ENT_QUOTES, "UTF-8");
	$a = htmlspecialchars($_POST["a"], ENT_QUOTES, "UTF-8");
	$b = htmlspecialchars($_POST["b"], ENT_QUOTES, "UTF-8");
	$c = htmlspecialchars($_POST["c"], ENT_QUOTES, "UTF-8");
	$d = htmlspecialchars($_POST["d"], ENT_QUOTES, "UTF-8");

	if(!empty($id_voting) && !empty($thema) && !empty($frage) && !empty($a) && !empty($b) && !empty($c) && !empty($d)) {
		if(isset($_FILES["bild"]) && $_FILES["bild"]["error"] == UPLOAD_ERR_OK) {
			$tmp_name = $_FILES["bild"]["tmp_name"];
			$ext = end(explode(".", $_FILES["bild"]["name"]));
			unlink("pictures/".$voting->bild);
			$bild = $voting->token . "." . $ext;
			move_uploaded_file($tmp_name, "pictures/" . $bild);
			$voting->bild = $bild;
		}
		
		$voting->thema = $thema;
		$voting->frage = $frage;
		$voting->a = $a;
		$voting->b = $b;
		$voting->c = $c;
		$voting->d = $d;

		$voting->stimmen_a = 0;
		$voting->stimmen_b = 0;
		$voting->stimmen_c = 0;
		$voting->stimmen_d = 0;
		$voting->gestartet = 0;

		$voting->token = randomString(6);
		$votingManager->save($voting);

		header('Location: votinguebersicht.php?kursnummer=' . $voting->kursnummer);
	} else {
		$errorHandler = new ErrorHandler();
		$errorHandler->storeError("votingedit", "Error: Bitte alle Felder ausfüllen!");
		header('Location: votingedit_form.php?id_voting=' . $voting->id_voting);
	}

	function randomString($laenge) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for($i = 0; $i < $laenge; $i++) {
			$randstring .= $characters[rand(0, strlen($characters)-1)];
		}

		return $randstring;
	}