<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/votingManager.php");
	require_once("mapper/vorlesungManager.php");

	require_once("mapper/errorHandler.php");
	$errorHandler = new ErrorHandler();

	if(!isset($_POST["id_voting"])) {
		$errorHandler->storeError("fehler", "Es wurden nicht alle POST Felder angegeben. Bitte wenden Sie sich an den Administrator.");
		header("Location: fehler.php");
		exit();
	}
	
	$id_voting = (int)htmlspecialchars($_POST["id_voting"], ENT_QUOTES, "UTF-8");

	$votingManager = new VotingManager();
	$vorlesungManager = new VorlesungManager();

	$voting = $votingManager->findById_voting($id_voting);
	$vorlesung = $vorlesungManager->findBykursnummer($voting->kursnummer);
	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		$errorHandler->storeError("fehler", "Sie sind nicht dazu berechtigt dieses Voting zu bearbeiten.");
		header("Location: fehler.php");
		exit();
	}

	unlink("pictures/".$voting->bild);
	$voting->bild = null;
	$votingManager->save($voting);

	header('Location: votinguebersicht.php?kursnummer=' . $voting->kursnummer);