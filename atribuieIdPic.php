<?php
require_once "autoloader.php";
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
require_once("classes/SessionClass.php");

$appSettings = $session = new SessionClass();
$session->exchangeArray($_SESSION);

$database = new DataConnection();
$aparateMapper = new AparateMapper($database,$appSettings);
$macsMapper = new MacPicMapper($database,$appSettings);

$page = new PageClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
?>
<!DOCTYPE html>
<head>
    <title>Atribuie Aparate</title>
    <meta charset="UTF-8"/>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<?php require_once('includes/menu.php'); ?>
<div class="col-md-12">
    <script type="text/javascript">
        $(function () {
            var seriiUnice = [
                <?php
                $aparate = $aparateMapper->getDistinctSerii();
                foreach($aparate as $aparata){
                    echo '"'.$aparata.'",';
                }
                ?>
            ];
            $("input[id^='id-mac-input-']").each(function () {
                $(this).autocomplete({
                    source: seriiUnice
                });
            });
        });
        $(document).ready(function(){
            $('button[id^="mac-button-"]').click(function(){
                var idmac = $(this).attr('data-idmacpic');
                var seria = $("#id-mac-input-"+idmac).val();
                if(seria != '') {
                    $.ajax({
                        url: 'ajax/atribuiePic.php',
                        method: 'POST',
                        data: {
                            'idmac': idmac,
                            'seria': seria
                        },
                        success: function (data) {
                            alert(data);
                        }
                    });
                }else{
                    alert("Va rugam sa alegeti o serie! Multumim pentru intelegere!");
                }
            });
        });
    </script>
    <table class="table table-responsive">
        <thead>
        <tr>
            <th>MAC</th>
            <th>Seria</th>
            <th>Actiune</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $macuri = $macsMapper->getMacs();
        /** @var MacPic $mac */
        foreach ($macuri as $mac) {
            ?>
            <tr>
                <td>
                    <?php echo $mac->getMacPic(); ?>
                </td>
                <td><input style="border:none" type="text" name="mac-<?php echo $mac->getIdmacpic(); ?>"
                           class="form-control" id="id-mac-input-<?php echo $mac->getIdmacpic() ?>"/></td>
                <td>
                    <button class="btn btn-primary" id="mac-button-<?php echo $mac->getIdmacpic(); ?>" data-idmacpic="<?php echo $mac->getIdmacpic(); ?>">Atribuie</button>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
</body>
