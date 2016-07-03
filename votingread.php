<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/vorlesungManager.php");
	require_once("mapper/votingManager.php");

	if(!isset($_GET["id_voting"])) {
		header("Location: 404.php");
		exit();
	}

	$id_voting = (int)htmlspecialchars($_GET["id_voting"], ENT_QUOTES, "UTF-8");
	$votingManager = new VotingManager();
	$voting = $votingManager->show($id_voting);

	if($voting == null) {
		header("Location: 404.php");
		exit();
	}

	$vorlesungManager = new VorlesungManager();
	$vorlesung = $vorlesungManager->findBykursnummer($voting->kursnummer);

	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		header("Location: 404.php");
		exit();
	}

?>

<!DOCTYPE html>
<html>

	<?php include("inc/head.php"); ?>

	<body>

		<?php include("inc/navbar_loggedin_dozent.php"); ?>

		<div id="votingread-inhalt" class="container panel hauptbereich seite-fuellen">
			<h1 class="panel-heading jumbotron">
				<?php
					echo "Thema: $voting->thema <br>";
					echo "Frage: $voting->frage";

				?>
			</h1>

			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>a)</th>
							<th>b)</th>
							<th>c)</th>
							<th>d)</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php
								echo "<td>$voting->a</td>";
								echo "<td>$voting->b</td>";
								echo "<td>$voting->c</td>";
								echo "<td>$voting->d</td>";
							?>
						</tr>
					</tbody>
				</table>
				<div>
					<a href="votinguebersicht.php?kursnummer=<?php echo $voting->kursnummer; ?>"
					   class="btn btn-default col-sm-2">Zurück</a>
				<?php
					if($voting->bild !== null) {
						?>
							<img class="img-rounded col-sm-offset-1 col-sm-6"
								 src="pictures/<?php echo $voting->bild ?>">
						<?php
					}
				?>
				</div>

			</div>
		</div>

		<?php include('inc/footer.php') ?>
	</body>
</html>

