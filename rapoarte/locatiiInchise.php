<?php
require_once '../autoloader.php';
require_once "../includes/dbFull.php";
require_once "../classes/Mappers/LocatiiMaper.php";
require_once "../classes/SessionClass.php";
require_once "../classes/PageClass.php";
$page = new PageClass();

$appSettings = $session = new SessionClass();
$session->exchangeArray($_SESSION);

$database = new DataConnection();

if(isset($_GET['an']) OR isset($_GET['luna'])){
    $get = $db->sanitizePost($_GET);
    $session->setAn($get['an']);
    $session->setLuna($get['luna']);
    header("location:".$_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Locatii Inchise</title>
    <?php require_once "../includes/header.php"; ?>
</head>
<body>
<?php require_once "../includes/menu.php" ?>
<?php

$locatii = new LocatiiMaper($database,$session);
$zileLuna = $page->zileInLuna($session->getLuna(),$session->getAn());
$inchise = $locatii->getLocatiiInchise($session->getAn(), $session->getLuna(),$zileLuna);
$deschise = $locatii->getLocatiiDeschise($session->getAn(), $session->getLuna(),$zileLuna);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form method="get" id="data">
            <fieldset class="col-md-6">
                <select name="an" id="an" class="form-control">
                    <option value="<?php echo $session->getAn() ?>"><?php echo $session->getAn() ?></option>
                    <?php
                    for ($z = 2015; $z < 2020; $z++) {
                        if ($z != $session->getAn()) {
                            ?>
                            <option value="<?php echo $z ?>"><?php echo $z; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </fieldset>
            <fieldset class="col-md-6">
                <select name="luna" id="luna" class="form-control">
                    <option value="<?php echo $session->getLuna() ?>"><?php echo $page->getLuna($session->getLuna()) ?></option>
                    <?php
                    for ($i = 1; $i < 13; $i++) {
                        if ($i != $luna) {
                            ?>
                            <option
                                value="<?php echo $i; ?>"><?php echo $page->getLuna($i) ?></option>
                            <?php
                        }
                    }
                    ?>

                </select>
            </fieldset>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <td colspan="4"><h4>Nr. Locatii Inchise : <?php echo count($inchise); ?></h4></td>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                    /** @var LocatiiEntity $locatie */
                    foreach($inchise as $locatie){
                    ?>
                        <tr>
                            <td><?php echo $i; $i++;?></td>
                            <td><?php echo $locatie->getDenumire(); ?></td>
                            <td><?php echo $locatie->getAdresa(); ?></td>
                            <td class="data"><?php echo $locatie->getDtInchidere(); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <td colspan="4"><h4>Nr. Locatii deschise : <?php echo count($deschise); ?></h4></td>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                foreach($deschise as $locatie){
                    ?>
                    <tr>
                        <td><?php echo $i; $i++;?></td>
                        <td><?php echo $locatie->getDenumire(); ?></td>
                        <td><?php echo $locatie->getAdresa() ?></td>
                        <td class="data"><?php echo $locatie->getDtInfiintare()?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('change','#an,#luna',function(event){
        $("#data").submit();
    });
</script>
</body>
</html>
