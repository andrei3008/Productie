<?php
require_once "autoloader.php";

$appSettings = new SessionClass();
$db = new DataConnection();

$page = new PageClass();
$personalMapper = new PersonalMapper($db, $appSettings);
$locatiiMapper = new LocatiiMaper($db, $appSettings);


$user = $personalMapper->getPersonal($appSettings->getUserId());
$locatie = $locatiiMapper->getLocatie($user->getIdlocatie());
$firma = $locatie->getFirma();

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <?php require_once "includes/header.php"; ?>
</head>
<body>
<?php
require_once "includes/meniu_locatie.php"
?>
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"><strong><?php echo $locatie->getDenumire(); ?></strong>
            / <?php echo $firma->getDenumire() ?>
            / <?php echo $locatie->getAdresa() ?>
            / <strong>IdOp</strong> <?php echo $locatie->getIdOperator() ?>
            / <strong>IdLoc</strong> <?php echo $locatie->getIdlocatie() ?></div>
        <div class="panel-body">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Poz</th>
                    <th>Id Aparat</th>
                    <th>Seria</th>
                    <th>Tip</th>
                    <th>V</th>
                    <th>Total In</th>
                    <th>Total Out</th>
                    <th>Data Ultimului Index</th>
                    <th>Inactivitate</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                $aparate = $locatie->getAparateActive();
                /** @var AparatEntity $aparat */
                foreach ($aparate as $aparat) {
                    ?>
                    <tr>
                        <td><?php echo $i; $i++; ?></td>
                        <td><?php echo $aparat->getPozitieLocatie(); ?></td>
                        <td><?php echo $aparat->getIdAparat() ?></td>
                        <td><?php echo $aparat->getSeria() ?></td>
                        <td><?php echo $aparat->getTipJocMetrologii() ?></td>
                        <td><?php echo $aparat->getStareaparate()->getVerSoft(); ?></td>
                        <td><?php echo $page->niceIndex($aparat->getStareaparate()->getLastIdxInM()) ?></td>
                        <td><?php echo $page->niceIndex($aparat->getStareaparate()->getLastIdxOutM()) ?></td>
                        <td><?php $dataUltimulIndex = new DateTime($aparat->getStareaparate()->getDtLastM());
                            echo $dataUltimulIndex->format("H:i:s / d-M-Y"); ?></td>
                        <td><?php $dataUltimaConectare = new DateTime($aparat->getStareaparate()->getUltimaConectare());
                            echo $dataUltimaConectare->format("H:i:s / d-M-Y") ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
