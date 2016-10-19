<?php
require_once "../autoloader.php";
require_once('../includes/dbFull.php');
//error_reporting(E_ALL);
$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$database = new DataConnection();
$appSettings = $session = new SessionClass();
$session->exchangeArray($_SESSION);
$page = new PageClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
if (!isset($_GET['an'])) {
    $an = date('Y');
} else {
    $get = $db->sanitizePost($_GET);
    $an = $get['an'];
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Lista transferuri</title>
        <?php require_once '../includes/header.php'; ?>
    </head>

    <body>
        <?php require_once '../includes/menu.php'; ?>
            <div class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog2">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Trasnferuri <?php echo $an ?></h4> </div>
                        <div class="modal-body">
                            <p>One fine body&hellip;</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog2 -->
            </div>
            <!-- /.modal -->
            <div class="container-fluid" style="margin-left: 10px">
                <div class="row">
                    <div class="panel panel-primary" style="width: 35%">
                        <div class="panel-heading">Lista transferuri</div>
                        <div class="panel-body">
                            <h4 class="inline"><span style="display: inline-block; width : 32%">Transferuri</span></h4>
                            <fieldset style="width: 20%; display: inline-block">
                                <select name="an" id="anTr" class="form-control">
                                    <?php
                    $ani = $db->getTransferAni('dtBaza');
                    
                    foreach ($ani as $key => $value) {
                        if ($an == $value) {
                            ?>
                                        <option value='<?php echo $value ?>'>
                                            <?php echo $value; ?>
                                        </option>
                                        <?php
                        }
                    }
                    foreach ($ani as $key => $value) {
                        if ($an != $value) {
                            ?>
                                            <option value='<?php echo $value ?>'>
                                                <?php echo $value; ?>
                                            </option>
                                            <?php
                        }
                    }
                    ?>
                                </select>
                            </fieldset>
                            <script type='text/javascript'>
                                $(document).on('change', '#anTr', function () {
                                    var an = $('#anTr').val();
                                    window.location.href = 'transferuri.php?an=' + an;
                                });
                            </script>
                        </div>
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Nr.Crt</th>
                                    <th>Luna</th>
                                    <th>Ampera</th>
                                    <th>Redlong</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
           
            for ($j = 1; $j < 13; $j++) {
                $data_start = date('Y-m-d', strtotime(date($an.'-'.$j.'-01')));
                $data_end = date('Y-m-t', strtotime(date($an.'-'.$j.'-01')));
                $transferuri = $db->returneazaTransferuri($data_start, $data_end);
                $transferuri_Ampera = $db->returneazaTransferuriOperatori($data_start, $data_end, 1);
                $transferuri_Red = $db->returneazaTransferuriOperatori($data_start, $data_end, 2);
                ?>
                                    <tr>
                                        <td>
                                            <?php echo $j; ?>
                                        </td>
                                        <td class="luna" data-luna="<?php echo $j ?>" data-an="<?php echo $an; ?>">
                                            <?php echo $page->getLuna($j); ?>
                                        </td>
                                        <td class="luna" data-luna="<?php echo $j ?>" data-an="<?php echo $an; ?>" data-op="1">
                                            <?php echo count($transferuri_Ampera); ?>
                                        </td>
                                        <td class="luna" data-luna="<?php echo $j ?>" data-an="<?php echo $an; ?>" data-op="2">
                                            <?php echo count($transferuri_Red); ?>
                                        </td>
                                        <td class="luna" data-luna="<?php echo $j ?>" data-an="<?php echo $an; ?>">
                                            <?php echo count($transferuri); ?>
                                        </td>
                                    </tr>
                                    <?php
            }
            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <script src="custom-transfer.js"></script>
    </body>

    </html>