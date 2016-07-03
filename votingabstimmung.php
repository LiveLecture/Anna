<?php
require_once("inc/dozent_check.php");

require_once("mapper/votingManager.php");

if (!isset($_GET["id_voting"])) {
    header("Location: 404.php");
    exit();
}

$id_voting = htmlspecialchars($_GET["id_voting"], ENT_QUOTES, "UTF-8");

$votingManager = new VotingManager();
$voting = $votingManager->findById_voting($id_voting);

if ($voting == null) {
    header("Location: 404.php");
    exit();
}

$host = Data::$host;
?>

<!DOCTYPE html>
<html>

<?php include("inc/head.php"); ?>

<body>

<?php include("inc/navbar_loggedin_dozent.php"); ?>

<div class="container panel hauptbereich">
    <div class="panel-heading">
        <h1>Frage:<?php echo($voting->frage); ?></h1>
    </div>
    <div class="panel-body">
        <h2>
            <p class="col-sm-8">Link: <?php echo "$host/abstimmungstudenten.php?key=$voting->token"; ?></p>

            <a href="votinguebersicht.php?kursnummer=<?php echo $voting->kursnummer ?>"
               class='btn btn-default col-sm-1'>Zur&uuml;ck</a>
            <?php
            if ($voting->gestartet) {
                ?>
                <a href="votingabstimmung_do.php?key=?php echo $voting->token ?>&start=0"
                   id="abstimmungs_button" class='btn btn-danger col-sm-offset-1 col-sm-1'>Beenden</a>
                <?php
            } else {
                ?>
                <a href="votingabstimmung_do.php?key=<?php echo $voting->token ?>&start=1"
                   id="abstimmungs_button" class='btn btn-success col-sm-offset-1 col-sm-1'>Start</a>
                <?php
            }
            ?>
        </h2>
        <div>
            <div class="col-sm-6">
                <canvas id="myChart" height="50px"></canvas>
                <?php
                if ($voting->bild !== null) {
                    ?>
                    <img class="img-rounded col-sm-12 padding-0"
                         src="pictures/<?php echo $voting->bild ?>">

                    <?php
                }
                ?>
            </div>
            <div class="col-sm-offset-2 col-sm-4">
                <div>Antworten:</div>
                <div class="margin-top-5px">a) <?php echo $voting->a; ?></div>
                <div class="margin-top-5px">b) <?php echo $voting->b; ?></div>
                <div class="margin-top-5px">c) <?php echo $voting->c; ?></div>
                <div class="margin-top-5px">d) <?php echo $voting->d; ?></div>
            </div>
        </div>
    </div>
    <script src="js/votingabstimmung.js"></script>
    <script>update("<?php echo $voting->token ?>");</script>
</div>

<?php require_once('inc/footer.php') ?>
</body>
</html>