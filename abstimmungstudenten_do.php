<?php
	require_once("mapper/votingManager.php");
	require_once("mapper/errorHandler.php");

	session_start();
	setcookie(session_name(), session_id(), time() + 3600 * 12); // 12 Stunden lang gültig

	if(!isset($_POST["token"])) {
		$errorHandler = new ErrorHandler();
		$errorHandler->storeError("fehler", "Es wurden nicht alle POST Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}
	$token = $_POST["token"];

	if(!isset($_POST["antwort"])) {
		$errorHandler = new ErrorHandler();
		$errorHandler->storeError("abstimmungstudenten", "Bitte geben Sie eine Antwort an.");
		header("Location: abstimmungstudenten.php?key=".$token);
		exit();
	}

	if(isset($_SESSION["votings"])) {
		$fertigeVotings = $_SESSION["votings"];
	} else {
		$fertigeVotings = [];
	}


	$token = htmlspecialchars($_POST["token"], ENT_QUOTES, "UTF-8");
	$antwort = htmlspecialchars($_POST["antwort"], ENT_QUOTES, "UTF-8");

	if(!isset($fertigeVotings[$token]) || !$fertigeVotings[$token]) {
		$votingManager = new VotingManager();
		$voting = $votingManager->findByToken($token);
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