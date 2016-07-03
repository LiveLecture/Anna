<?php
	require_once("inc/dozent_check.php");

	require_once("mapper/dozentManager.php");
	require_once("mapper/vorlesungManager.php");
	require_once("mapper/votingManager.php");

	if(!isset($_GET["kursnummer"])) {
		header("Location: 404.php");
		exit();
	}
	$kursnummer = (int)htmlspecialchars($_GET["kursnummer"], ENT_QUOTES, "UTF-8");

	$vorlesungManager = new VorlesungManager();
	$vorlesung = $vorlesungManager->findBykursnummer($kursnummer);

	if($dozent->id_dozent !== $vorlesung->id_dozent) {
		header("Location: 404.php");
		exit();
	}

	$votingManager = new VotingManager();
	$liste = $votingManager->findAll($kursnummer);
?>

<!DOCTYPE html>
<html>

	<?php include("inc/head.php"); ?>

	<body>

		<?php include("inc/navbar_loggedin_dozent.php"); ?>

		<div class="container hauptbereich seite-fuellen">

			<h1 class="panel-heading">Votingübersicht</h1>

			<div class="panel-body">
				<table class="table table-hover table-inhalt-mittig">
					<thead>
						<th class="col-sm-2">Voting-ID</th>
						<th class="col-sm-2">Thema</th>
						<th class="col-sm-2">Frage</th>
						<th class="col-sm-2">Anzeigen</th>
						<th class="col-sm-1">Editieren</th>
						<th class="col-sm-1">Löschen</th>
						<th class="col-sm-2">Abstimmung</th>
					</thead>

					<tbody>

						<?php
							foreach($liste as $voting) {
								echo "<tr>";
								echo "<td>$voting->id_voting</td>";
								echo "<td>$voting->thema</td>";
								echo "<td>$voting->frage</td>";
								echo "<td>
                    <a href='votingread.php?id_voting=$voting->id_voting' class='glyphicon glyphicon-eye-open'></a>
                 </td>";
								echo "<td>
                    <a href='votingedit_form.php?id_voting=$voting->id_voting' class='glyphicon glyphicon-pencil'></a>
                 </td>";
								echo "<td>
                    <a href='votingdelete_do.php?id_voting=$voting->id_voting' class='glyphicon glyphicon-trash'></a>
                 </td>";
								echo "<td>
                    <a href='votingabstimmung.php?id_voting=$voting->id_voting' class='glyphicon glyphicon-list-alt'></a>
                 </td>";
								echo "</tr>";
							}
						?>

					</tbody>
				</table>
				<div class="col-sm-12">
					<a href="vorlesungsuebersicht.php" class="btn btn-default col-sm-2">Zurück</a>
					<a href="votingcreate_form.php?kursnummer=<?php echo $kursnummer ?>"
					   class="btn btn-default col-sm-2 col-sm-offset-8">neues Voting</a>
				</div>
			</div>
		</div>

		<?php include('inc/footer.php') ?>

	</body>
</html>
