<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/votingManager.php");
	require_once("mapper/vorlesungManager.php");

	if(!isset($_GET["key"]) || !isset($_GET["start"])) {
		$errorHandler->storeError("fehler", "Es wurden nicht alle GET Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}
	
	$token = htmlspecialchars($_GET["key"], ENT_QUOTES, "UTF-8");
	$start = htmlspecialchars($_GET["start"], ENT_QUOTES, "UTF-8");

	$votingManager = new VotingManager();
	$vorlesungManager = new VorlesungManager();
	
	$voting = $votingManager->findByToken($token);
	$vorlesung = $vorlesungManager->findBykursnummer($voting->kursnummer);
	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		$errorHandler->storeError("fehler", "Sie sind nicht berechtigt diese Abstimmung zu starten oder zu beenden.");
		header("Location: fehler.php");
		exit();
	}

	switch($start) {
		case 0:
			$voting->gestartet = 0;
			break;
		case 1:
			$voting->gestartet = 1;
			break;
		default:
			exit();
	}

	$votingManager->save($voting);

	header("Location: votingabstimmung.php?id_voting=".$voting->id_voting);