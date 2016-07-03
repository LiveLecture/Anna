<?php
	require_once("inc/dozent_check.php");
	require_once("mapper/vorlesungManager.php");
	require_once("mapper/errorHandler.php");

	if(!isset($_GET["kursnummer"])) {
		header("Location: 404.php");
		exit();
	}
	
	$kursnummer = (int)htmlspecialchars($_GET["kursnummer"], ENT_QUOTES, "UTF-8");

	$vorlesungManager = new VorlesungManager();
	$vorlesung = $vorlesungManager->findBykursnummer($kursnummer);
	
	if($vorlesung==null) {
		header("Location: 404.php");
		exit();
	}

	$errorHandler = new ErrorHandler();
	$error = $errorHandler->getError("votingcreate");
?>

<!DOCTYPE html>
<html>

	<?php include("inc/head.php"); ?>

	<body>

		<?php include("inc/navbar_loggedin_dozent.php"); ?>
		
		<?php include("inc/fehlermeldung.php"); ?>

		<div class="container panel hauptbereich seite-fuellen">

			<h1 class="panel-heading">Neues Voting</h1>

			<div class="panel-body">
				<form class="form-horizontal" action="votingcreate_do.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="kursnummer" value="<?php echo $kursnummer ?>">

					<div class="form-group">
						<label class="control-label col-sm-2" for="thema">Thema: <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="thema" id="thema"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="frage">Frage: <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="frage" id="frage"/>
						</div>
					</div>
					<div class="form-group"> <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
						<label class="control-label col-sm-2" for="a">a):
							<input type="text" class="form-control" name="a" id="a"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="b">b): <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="b" id="b"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="c">c): <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="c" id="c"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="d">d): <br>(max 265 Zeichen)</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="d" id="d"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="bild">Bild: <br>(max 2Mb)</label>
						<div class="col-sm-10">
							<input type="file" class="file" name="bild" id="bild"/>
						</div>
					</div>
					<button type="submit" class="col-sm-offset-2 col-sm-2 btn btn-default">Hinzuf&uuml;gen</button>

					<a href="votinguebersicht.php?kursnummer=<?php echo $kursnummer ?>"
					   class="col-sm-offset-6 col-sm-2 btn btn-default">Zur&uuml;ck</a>
				</form>
			</div>
		</div>

		<?php include("inc/footer.php"); ?>
	</body>
</html>