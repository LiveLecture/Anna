<?php
	require_once("Mapper/VotingManager.php");
	require_once("Mapper/ErrorHandler.php");

	$errorHandler = new ErrorHandler();

	if(session_id() == "") {
		session_start();
	}
	setcookie(session_name(), session_id(), time() + 3600 * 24); // 24 Stunden lang gültig

	if(!isset($_POST["token"])) {
		$errorHandler = new ErrorHandler();
		$errorHandler->storeError("fehler", "Es wurden nicht alle POST Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}
	$token = htmlspecialchars($_POST["token"], ENT_QUOTES, "UTF-8");

	if(!isset($_POST["antwort"])) {
		$errorHandler->storeError("abstimmungstudenten", "Bitte geben Sie eine Antwort an.");
		header("Location: abstimmungstudenten.php?key=" . $token);
		exit();
	}
	$antwort = htmlspecialchars($_POST["antwort"], ENT_QUOTES, "UTF-8");
	$antwort = intval($antwort);

	if($antwort != 1 && $antwort != 2 && $antwort != 3 && $antwort != 4) {
		$errorHandler->storeError("fehler", "Ein POST Feld entspricht nicht den Anforderungen. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}


	$votingManager = new VotingManager();
	$voting = $votingManager->findByToken($token);

	if($voting == null || $voting->gestartet == 0) {
		$errorHandler->storeError("fehler", "Die Abstimmung existiert nicht oder wurde noch nicht gestartet.");
		header("Location: fehler.php");
		exit();
	}

	if(!isset($_SESSION["voteFor"]) || $_SESSION["voteFor"]!=$voting->id_voting) {
		$errorHandler->storeError("fehler", "Sie sind nicht berechtigt hier abzustimmen. Bitte wenden Sie sich an den Administrator!");
		header("Location: fehler.php");
		exit();
	} else {
		unset($_SESSION["voteFor"]);
	}

	if(isset($_SESSION["votings"])) {
		$fertigeVotings = $_SESSION["votings"];
	} else {
		$fertigeVotings = [];
	}

	if(!isset($fertigeVotings[$token]) || !$fertigeVotings[$token]) {
		switch($antwort) {
			case 1:
				$voting->stimmen_a++;
				break;
			case 2:
				$voting->stimmen_b++;
				break;
			case 3:
				$voting->stimmen_c++;
				break;
			case 4:
				$voting->stimmen_d++;
				break;
			default:
				exit();
		}

		$votingManager->save($voting);
		$fertigeVotings[$token] = true;

		$_SESSION["votings"] = $fertigeVotings;
	}

	header("Location: danke.php");

?>