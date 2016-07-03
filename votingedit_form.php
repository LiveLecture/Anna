<?php
require_once("inc/dozent_check.php");

require_once("mapper/vorlesungManager.php");
require_once("mapper/votingManager.php");

require_once("mapper/errorHandler.php");

if (!isset($_GET["id_voting"])) {
    header("Location: 404.php");
    exit();
}

$id_voting = (int)htmlspecialchars($_GET["id_voting"], ENT_QUOTES, "UTF-8");

$votingManager = new VotingManager();
$voting = $votingManager->findById_voting($id_voting);

if ($voting == null) {
    header("Location: 404.php");
    exit();
}

$vorlesungManager = new VorlesungManager();
$vorlesung = $vorlesungManager->findBykursnummer($voting->kursnummer);

if ($dozent->id_dozent !== $vorlesung->id_dozent) {
    header("Location: 404.php");
    exit();
}

$errorHandler = new ErrorHandler();
$error = $errorHandler->getError("votingedit");
?>

<!DOCTYPE html>
<html>


<?php include("inc/head.php"); ?>

<body>

<?php include("inc/navbar_loggedin_dozent.php"); ?>

<?php include("inc/fehlermeldung.php"); ?>

<div class="container panel hauptbereich seite-fuellen">
    <h1 class="panel-heading">Bearbeite "<?php echo($voting->frage) ?>"</h1>

    <div class="panel-body">
        <form class="form-horizontal" action='votingedit_do.php' method='post' enctype="multipart/form-data">
            <input type='hidden' name='id_voting' value='<?php echo($voting->id_voting) ?>'/>

            <div class="form-group">
                <label class="control-label col-sm-2" for="thema">Thema: <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="thema" id="thema"
                           value='<?php echo($voting->thema) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="frage">Frage: <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="frage" id="frage"
                           value='<?php echo($voting->frage) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="a">a): <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="a" id="a" value='<?php echo($voting->a) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="b">b): <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="b" id="b" value='<?php echo($voting->b) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="c">c): <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="c" id="c" value='<?php echo($voting->c) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="d">d): <br>(max 265 Zeichen)</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" name="d" id="d" value='<?php echo($voting->d) ?>'/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="bild">Bild: <br>(max 2Mb)</label>

                <div class="col-sm-10">
                    <input type="file" class="file" name="bild" id="bild"/>
                </div>
            </div>
            <?php
            if ($voting->bild !== null) {
                ?>
                <div class="form-group">
                    <img class="img-rounded col-sm-offset-2 col-sm-6"
                         src="pictures/<?php echo $voting->bild ?>">

                    <button formaction="votingdelete_bild.php" class="col-sm-2 btn btn-default">Löschen!
                    </button>
                </div>
                <?php
            }
            ?>
            <a href="votinguebersicht.php?kursnummer=<?php echo $voting->kursnummer ?>"
               class="col-sm-offset-2 col-sm-2 btn btn-default">Zur&uuml;ck</a>
            <button class="col-sm-offset-6 col-sm-2 btn btn-default">Edit!</button>
        </form>
        <div class="text-danger col-sm-offset-2 col-sm-8 margin-top-5px">
            Wenn Sie ein Voting bearbeiten, werden alle bereits abgegebenen Stimmen zurückgesetzt!
        </div>
    </div>
</div>
<?php include('inc/footer.php') ?>
</body>
</html>