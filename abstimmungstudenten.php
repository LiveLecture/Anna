<?php
	require_once("mapper/vorlesungManager.php");
	require_once("mapper/votingManager.php");
	require_once("mapper/errorHandler.php");

	if(!isset($_GET["key"])) {
		header("Location: 404.php");
		exit();
	}

	$token = htmlspecialchars($_GET["key"], ENT_QUOTES, "UTF-8");

	$votingManager = new VotingManager();
	$voting = $votingManager->findByToken($token);

	if($voting === null || $voting->gestartet == 0) {
		header("Location: 404.php");
		exit();
	}

	if(isset($_SESSION["votings"])) {
		$fertigeVotings = $_SESSION["votings"];
		$id_voting = $voting->id_voting;
		if(isset($fertigeVotings[$token]) && $fertigeVotings[$token]) {
			header("Location: danke.php");
			exit();
		}
	}

	$errorHandler = new ErrorHandler();
	$error = $errorHandler->getError("abstimmungstudenten");
?>

<!DOCTYPE html>
<html>

	<?php include("inc/head.php"); ?>

	<body>

		<?php include("inc/navbar_loggedout.php"); ?>

		<?php include("inc/fehlermeldung.php"); ?>

		<div class="container panel hauptbereich">
			<div class="col-sm-12 panel-header">
				<h1>Frage: <?php echo $voting->frage; ?></h1>
			</div>

			<div class="panel-body">

				<div class="col-sm-12">
					<?php
						if($voting->bild !== null) {
							?>
							<img class="img-rounded col-sm-offset-3 col-sm-6 padding-0"
								 src="pictures/<?php echo $voting->bild ?>">
							<?php
						}
					?>
				</div>
				<div class="col-sm-12">
					<form action="abstimmungstudenten_do.php" method="post">
						<input type="hidden" name="token" value="<?php echo $token ?>">
						<div class="col-sm-10 abstimmung-zeile">a) <?php echo $voting->a; ?></div>
						<div class="col-sm-offset-1 col-sm-1 abstimmung-zeile"><input name="antwort" type="radio" value="1"></div>
						<div class="col-sm-10 abstimmung-zeile">b) <?php echo $voting->b; ?></div>
						<div class="col-sm-offset-1 col-sm-1 abstimmung-zeile"><input name="antwort" type="radio" value="2"></div>
						<div class="col-sm-10 abstimmung-zeile">c) <?php echo $voting->c; ?></div>
						<div class="col-sm-offset-1 col-sm-1 abstimmung-zeile"><input name="antwort" type="radio" value="3"></div>
						<div class="col-sm-10 abstimmung-zeile">d) <?php echo $voting->d; ?></div>
						<div class="col-sm-offset-1 col-sm-1 abstimmung-zeile"><input name="antwort" type="radio" value="4"></div>

						<div class="col-sm-offset-11 col-sm-1">
							<input type="submit" class="btn btn-default" value="Abstimmen!">
						</div>

					</form>
				</div>
			</div>
		</div>
		<?php include('inc/footer.php') ?>
	</body>
</html>