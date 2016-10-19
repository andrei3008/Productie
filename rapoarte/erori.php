<?php

require_once "../autoloader.php";

$session = new SessionClass();
$page = new PageClass();

$db = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
$database = new DataConnection();
$get = $db->sanitizePost($_GET);

$personalFactory = new \Factoryes\FabricaDePersonal($database,$session);

$page->checkLogin($_SESSION['username'], $_SESSION['operator']);

$responsabil = $personalFactory->getPersoana($get['id']);

$errorMapper = new FabricaDeErori($database,$session);

$seriiCuErori = $errorMapper->getEroriResponsabil($responsabil->getIdpers());
?>
<!DOCTYPE>
<html>
<head>
    <title>Erori zonale</title>
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
</head>
<body lang="en">
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php'; ?>
<div class="container">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4>Erori zona <?php echo $responsabil->getNick(); ?></h4></div>
            <div class="panel-body">
                <?php $i = 1;
                foreach ($seriiCuErori as $serie) {
                    $aparat = new AparatEntity($database,$session);
                    $locatie = new LocatiiEntity($database,$session);
                    $aparat->getAparatBySerie($serie['serieAparat']);
                    $locatie->getLocatie($aparat->getIdLocatie());
                    $aparat->getErori();
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4><?php echo $aparat->getSeria(); ?> <i
                                    class="glyphicon glyphicon-arrow-right"> </i> <?php echo $locatie->getDenumire(); ?>
                                <a href="#" class="btn btn-primary btn-sm" data-idLocatie="<?php echo $locatie->getIdlocatie() ?>" data-idResp="<?php echo $locatie->getIdresp() ?>" data-Operator="<?php echo $locatie->getIdOperator() ?>" onclick="mergiLaLocatie(this,event)">Mergi la locatie</a>
                            </h4></div>
                        <div class="panel-body">
                            <?php
                            $primaEroare = $aparat->getPrimaEroare();
                            $ultimaEroare = $aparat->getUltimaEroare();
                            $nrErori = $aparat->getNrEroriAparat();
                            ?>
                            <table class="table table-responsive">
                                <tr>
                                    <td><span class="bold">Numar Erori :</span></td>
                                    <td><?php echo $nrErori; ?></td>
                                    <td><span class="bold">Data Prima Eroare :</span></td>
                                    <td><?php echo $aparat->getPrimaEroare()->getDataServer() ?></td>
                                    <td><span class="bold">Data cea mai recenta eroare :</span></td>
                                    <td><?php echo $aparat->getUltimaEroare()->getDataServer(); ?></td>
                                </tr>
                                <tr>
                                    <td><span class="bold">Textul ultimei erori :</span></td>
                                    <td colspan="5">
                                        <?php echo $aparat->getUltimaEroare()->getExceptia(); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php
                    unset($aparat);
                } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
