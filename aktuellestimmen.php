<?php
	require_once("mapper/votingManager.php");

	if(!isset($_GET["key"])) {
		header("Location: 404.php");
		exit();
	}

	$token = htmlspecialchars($_GET["key"], ENT_QUOTES, "UTF-8");

	$votingManager = new VotingManager();
	$voting = $votingManager->findByToken($token);
	
	echo "$voting->stimmen_a,$voting->stimmen_b,$voting->stimmen_c,$voting->stimmen_d";




