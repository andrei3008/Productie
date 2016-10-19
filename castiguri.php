<?php
require_once "autoloader.php";


/**
 * Session handler
 */
$session =  $appSettings = new SessionClass();
// var_dump($session);
/**
 * Check user login
 */
$page = new PageClass();
$page->checkLogin($session->getUsername(), $session->getOperator());

/**
 * Database
 */
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
$database = new DataConnection();
/**
 * Mappers
 */
$locatiiMapper = new LocatiiMaper($database,$session);
$personalMapper = new PersonalMapper($database,$session);

/**
 * Sanitize get
 */
$get = $db->sanitizePost($_GET);

/**
 * Se preia responsabilul
 */
$personal = $personalMapper->getPersonal($get['id']);


?>
<!DOCTYPE html>
<html>
<head>
    <title>Lista Castiguri</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<style type="text/css" scoped>
    td, th {
        width: 10px !important;
    }
</style>
<?php require_once('includes/menu.php'); ?>
<div id="loading"></div>
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div style="display: inline-block">Lista Castiguri <span><?php echo $personal->getNick(); ?></span></div>
            <div style="display: inline-block">
                <fieldset>
                    <select name="an" id="an" class="form-control">
                        <option value="<?php echo $session->getAn() ?>"><?php echo $session->getAn(); ?></option>
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
            </div>
            <div style="display: inline-block">
                <fieldset>
                    <select name="luna" id="luna" class="form-control">
                        <option
                            value="<?php echo $session->getLuna(); ?>"><?php echo $page->getLuna($session->getLuna()) ?></option>
                        <?php
                        for ($i = 1; $i < 13; $i++) {
                            if ($i != $_GET['luna']) {
                                ?>
                                <option
                                    value="<?php echo $i; ?>"><?php echo $page->getLuna($i) ?></option>
                                <?php
                            }
                        }
                        ?>

                    </select>
                </fieldset>
            </div>
            <?php echo $session->getDirection() ?>
            <div class="inline">
                <fieldset class="inline">
                    <select name="order" class="form-control" id="order">
                        <option
                            value="<?php echo($session->getOrder() == 'bani' ? 'bani' : 'afiliere') ?>"><?php echo($session->getOrder() == 'bani' ? 'Bani' : 'Afiliere') ?></option>
                        <option
                            value="<?php echo($session->getOrder() == 'bani' ? 'dtInfiintare' : 'bani') ?>"><?php echo($session->getOrder() == 'bani' ? 'Afiliere' : 'Bani') ?></option>
                    </select>
                </fieldset>
            </div>
            <div class="inline">
                <select name="type" class="form-control" id="dimensiune">
                    <option
                        value="<?php echo ($session->getDirection() == 'DESC') ? 'DESC' : 'ASC' ?>"><?php echo ($session->getDirection() == 'DESC') ? 'Descrescator' : 'Crescator' ?></option>
                    <option
                        value="<?php echo ($session->getDirection() == 'DESC') ? 'ASC' : 'DESC' ?>"><?php echo ($session->getDirection() == 'DESC') ? 'Crescator' : 'Descrescator' ?></option>
                </select>
            </div>
            <div style="display: inline-block">
                <button id="afiseazaPacheteWan" class="btn btn-sm btn-primary"
                        data-an="<?php echo $session->getAn(); ?>"
                        data-luna="<?php echo $session->getLuna(); ?>"
                        data-idResponsabil="<?php echo $personal->getIdpers(); ?>">Afiseaza Pachete Wan
                </button>
            </div>
            <div style="display: inline-block">
                <button id="afiseazaPachete3g" class="btn btn-sm btn-primary"
                        data-an="<?php echo $session->getAn(); ?>"
                        data-luna="<?php echo $session->getLuna(); ?>"
                        data-idResponsabil="<?php echo $personal->getIdpers(); ?>">Afiseaza Pachete 3g
                </button>
            </div>
            <div style="display: inline-block">
                <button id="afiseazaCastiguri" class="btn btn-sm btn-primary"
                        data-an="<?php echo $session->getAn(); ?>"
                        data-luna="<?php echo $session->getLuna(); ?>"
                        data-idResponsabil="<?php echo $personal->getIdpers(); ?>">Afiseaza Castiguri
                </button>
            </div>
            <div style="display: inline-block;">
                <button id="afiseazaLocatii" class="btn btn-sm btn-primary"
                        data-an="<?php echo $session->getAn(); ?>"
                        data-luna="<?php echo $session->getLuna(); ?>"
                        data-idResponsabil="<?php echo $personal->getIdpers(); ?>">
                    Afiseaza Castiguri pe locatii
                </button>
            </div>
        </div>
        <div class="panel-body" id="table-container">
            <?php
            $today = date('d');
            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <td>Nr. Crt.</td>
                    <td>Denumire</td>
                    <td>Serie Aparat</td>
                    <?php
                    $zile = $page->zileInLuna($session->getLuna(), $session->getAn());
                    for ($i = 1; $i <= $zile; $i++) {
                        if ($i != $today) {
                            ?>
                            <td><?php echo $i; ?></td>
                            <?php
                        } else {
                            ?>
                            <td>Azi</td><?php
                        }
                    }
                    ?>
                    <td>TA</td>
                    <td>TL</td>
                </tr>
                </thead>
                <tbody>
                <?php
                /*
                 * Se preia toate locatiile active ale responsabilului
                 */
                $locatii = $personal->getLocatii($database,$session->getAn(), $session->getLuna(), $session->getOrder(), $session->getDirection());
                $j = 1;
                $grandTotal = 0;
                /** @var LocatiiEntity $locatie */
                foreach ($locatii as $locatie) {

                    $aparate = $locatie->getAparateLocatie();
                    $nrAparateLaLocatie = $locatie->getNumarAparateActive();

                    $nrAparateCiclate = 1;
                    /** @var AparatEntity $aparat */
                    foreach ($aparate as $aparat) {
                        ?>
                        <tr>
                            <?php if (($nrAparateCiclate == 1) AND ($nrAparateCiclate <= $nrAparateLaLocatie)) { ?>
                                <td rowspan="<?php echo $nrAparateLaLocatie ?>"><?php echo $j;
                                    $j++; ?></td>
                                <td rowspan="<?php echo $nrAparateLaLocatie ?>"><?php echo "<span class='milion'>{$locatie->getDenumire()}</span>"; ?>
                                    <br/><?php echo "<span class='zecimala'>{$locatie->getLuniDeLaAfiliere()}</span>"; ?>
                                </td>
                            <?php } ?>
                            <td><?php echo $aparat->getSeria(); ?></td>
                            <?php
                            $contori = $aparat->getContoriZilnici($session->getAn(), $session->getLuna());
                            $totalAparat = 0;
                            for ($k = 1; $k <= $zile; $k++) {
                                $valoare = $aparat->getIndexPeZi($session->getAn(), $session->getLuna(), $k);
                                ?>
                                <td class="<?php echo ($aparat->getIndexNeprelucratPeZi($session->getAn(), $session->getLuna(), $k) == 0) ? 'nuTransmis' : '' ?>"><?php
                                    $totalAparat += $aparat->getIndexPeZi($session->getAn(), $session->getLuna(), $k);
                                    echo $valoare;
                                    ?></td>
                                <?php
                            }
                            ?>
                            <td><?php echo $aparat->getTotalLunarAparat(); ?></td>
                            <?php if (($nrAparateCiclate == 1) AND ($nrAparateCiclate <= $nrAparateLaLocatie)) { ?>
                                <td rowspan="<?php echo $nrAparateLaLocatie ?>"><?php echo $locatie->getRoundBani();
                                    $grandTotal += $locatie->getRoundBani(); ?></td>
                            <?php }
                            $nrAparateCiclate++;
                            ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <td colspan="<?php echo $zile + 5; ?>"><h4>Total : <?php echo $grandTotal; ?></h4></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        /**
         * Folosite pentru sortari
         */
        $(document).ready(function () {
            $('#an').change(function () {
                var an = $(this).val();
                $.ajax({
                    method: 'POST',
                    url: '<?php echo DOMAIN; ?>/router.php',
                    data: {
                        'an': an
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });
            $('#luna').change(function () {
                var luna = $(this).val();
                $.ajax({
                    method: 'POST',
                    url: '<?php echo DOMAIN ?>/router.php',
                    data: {
                        'luna': luna
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });
            $('#order').change(function () {
                var order = $(this).val();
                $.ajax({
                    method: "POST",
                    url: '<?php echo DOMAIN; ?>/router.php',
                    data: {
                        'order': order
                    },
                    success: function () {
                        window.location.reload();
                    }
                });
            });
            $('#dimensiune').change(function () {
                var dimension = $(this).val();
                $.ajax({
                    method: 'POST',
                    url: '<?php echo DOMAIN ?>/router.php',
                    data: {
                        'direction': dimension
                    },
                    success: function (result) {
                        window.location.reload();
                    }
                })
            });
        });
        /**
         * Tipuri de pachete
         */
        $('#afiseazaPacheteWan').on('click',function (event) {
            var an = $(this).attr('data-an');
            var luna = $(this).attr('data-luna');
            var idResponsabil = $(this).attr('data-idResponsabil');
            var tipRequest = 'wan';

            $.ajax({
                type: "POST",
                url: 'ajax/getPachete.php',
                data: {
                    'an': an,
                    'luna': luna,
                    'idResponsabil': idResponsabil,
                    'tipPachete': tipRequest
                },
                success: function (result) {
                    $('#table-container').html(result);
                }
            });


        });
        $('#afiseazaPachete3g').click(function (event) {
            var an = $(this).attr('data-an');
            var luna = $(this).attr('data-luna');
            var idResponsabil = $(this).attr('data-idResponsabil');
            var tipPachete = '3g';

            $.ajax({
                type: "POST",
                url: 'ajax/getPachete.php',
                data: {
                    'an': an,
                    'luna': luna,
                    'idResponsabil': idResponsabil,
                    'tipPachete': tipPachete
                },
                success: function (result) {
                    $('#table-container').html(result);
                }
            });


        });

        $('#afiseazaCastiguri').click(function (event) {
            var an = $(this).attr('data-an');
            var luna = $(this).attr('data-luna');
            var idResponsabil = $(this).attr('data-idResponsabil');
            var tipPachete = 'castiguri';

            $.ajax({
                type: "POST",
                url: 'ajax/getPachete.php',
                data: {
                    'an': an,
                    'luna': luna,
                    'idResponsabil': idResponsabil,
                    'tipPachete': tipPachete
                },
                success: function (result) {
                    $('#table-container').html(result);
                }
            });
        });
        $('#afiseazaLocatii').click(function (event) {
            var an = $(this).attr('data-an');
            var luna = $(this).attr('data-luna');
            var idResponsabil = $(this).attr('data-idResponsabil');
            var tipPachete = 'castiguriLocatie';

            $.ajax({
                type: "POST",
                url: 'ajax/getPachete.php',
                data: {
                    'an': an,
                    'luna': luna,
                    'idResponsabil': idResponsabil,
                    'tipPachete': tipPachete
                },
                success: function (result) {
                    $('#table-container').html(result);
                }
            });


        });
        $(document).ajaxStop(function () {
            $('#loading').hide();
        });

        $(document).ajaxStart(function () {
            $('#loading').show();
        });
    </script>
</body>
</html>