<?php
require_once "../autoloader.php";
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
$session->exchangeArray($_SESSION);

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$database = new DataConnection();
$appSettings = $session = new SessionClass();


$page = new PageClass();

$page->checkLogin($_SESSION['username'], $_SESSION['operator']);

$post = $db->sanitizePost($_POST);

$an = $post['an'];
$luna = $post['luna'];
$luna_simplu = $_POST['luna'];
$idOperator = $_POST['op'];
$data_start = date('Y-m-d', strtotime(date($an.'-'.$luna.'-01')));
$data_end = date('Y-m-t', strtotime(date($an.'-'.$luna.'-01')));

?>
    <html lang="en">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />

    <body>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-10">
                <input type="hidden" name="input-op" value="<?php echo $idOperator ?>">
                <input type="hidden" name="input-an" value="<?php echo $an ?>">
                <input type="hidden" name="input-luna" value="<?php echo $luna ?>"> Raport transfer -
                <?php echo $page->getLuna($luna_simplu)?>
                    <?php echo $an ?> <?php if ($idOperator == 1) echo '- Ampera'; elseif ($idOperator == 2) echo '- Red Long'; ?>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-default btn-sm btn-rapoarte btn-pdf" data-ext="pdf">Print PDF</button>
                <button type="button" class="btn btn-default btn-sm btn-rapoarte btn-xls" data-ext="xls">Print XLS</button>
            </div>
        </div>

        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Nr. Crt</th>
                    <th>Seria</th>
                    <th>Id Aparat Inainte</th>
                    <th>Id Aparat Dupa</th>
                    <th>Mutat de la</th>
                    <th>Mutat la</th>
                    <th>Adresa Inainte</th>
                    <th>Adresa Dupa</th>
                    <th>Data PVR</th>
                    <th>Data baza de date</th>
                </tr>
            </thead>
            <tbody>
                <?php
//                    error_reporting(E_ALL);
                    $locatieInainte = new LocatiiEntity($database,$session);
                    $locatieDupa = new LocatiiEntity($database,$session);
                    if (isset($_POST[op])) {
                        $transferuri = $db->returneazaTransferuriOperatori($data_start, $data_end, $_POST[op]);
                    } else {
                        $transferuri = $db->returneazaTransferuri($data_start, $data_end);
                    }
                    $i = 1;
                    foreach ($transferuri as $transfer) {
                        $elementTransfer = new TransferAparate($transfer);

                        ?>
                    <tr>
                        <td>
                            <?php 
                            echo $i;
                            ?>
                        </td>
                        <td>
                            <?php
                                $aparat = new AparateMapper($database,$session);
                                $aparatTransfer = $aparat->getAparat($elementTransfer->getIdApDupa());
                                echo $aparatTransfer->getSeria();
                                ?>
                        </td>
                        <td class="align--center">
                            <?php echo $elementTransfer->getIdApInainte()?>
                        </td>
                        <td class="align--center">
                            <?php echo $elementTransfer->getIdApDupa(); ?>
                        </td>
                        <td>
                            <?php
                                $locatieInainte->getLocatie($elementTransfer->getIdLocInainte());
                                echo $locatieInainte->getDenumire();
                                ?>
                        </td>
                        <td>
                            <?php
                                $locatieDupa->getLocatie($elementTransfer->getIdLocDupa());
                                echo $locatieDupa->getDenumire();
                                ?>
                        </td>
                        <td>
                            <?php echo $locatieInainte->getAdresa(); ?>
                        </td>
                        <td>
                            <?php echo $locatieDupa->getAdresa();?>
                        </td>
                        <td>
                            <?php echo $elementTransfer->getDtPVR(); ?>
                        </td>
                        <td>
                            <?php echo $elementTransfer->getDtBaza(); ?>
                        </td>
                    </tr>
                    <?php
                        $i++;
                    }
                    ?>

            </tbody>
        </table>

    </body>

    </html>
