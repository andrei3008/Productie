<?php
require_once "autoloader.php";

$appSettings = new SessionClass();
$db = new DataConnection();
$page = new PageClass();

$personalMapper = new PersonalMapper($db, $appSettings);
$locatiiMapper = new LocatiiMaper($db, $appSettings);

$persoana = $personalMapper->getCurentPersonal();
$nrLocatiiAmpera = $locatiiMapper->getNumarLocatii($persoana->getIdpers(), '1');
$nrLocatiiRedlong = $locatiiMapper->getNumarLocatii($persoana->getIdpers(), '2');
?>
<!DOCTYPE>
<html lang="en">
<head>
    <title>

    </title>
    <?php require_once "includes/header.php" ?>
</head>
<body>
<?php require_once "includes/meniu_locatie.php" ?>
<div class="container-fluid">
    <div class="panel panel-primary col-md-3 padding2 margin0">
        <div class="panel-heading text-center padding2"><i
                class="glyphicon glyphicon-user"></i><?php echo $persoana->getNick() ?></div>
        <div class="panel-body padding2">
            <table class="table table-responsive margin0 responsabili">
                <?php ?>
                <tr data-id="<?php echo $persoana->getIdpers(); ?>" data-op="1" onclick="changeResponsabil(this)">
                    <td>A</td>
                    <td>(</td>
                    <td><?php echo $nrLocatiiAmpera ?> L</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateOperator(1); ?> A</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateDepozitOperator(1) ?> AD</td>
                    <td>)</td>
                </tr>
                <tr data-id="<?php echo $persoana->getIdpers() ?>" data-op="2" onclick="changeResponsabil(this)">
                    <td>R</td>
                    <td>(</td>
                    <td><?php echo $nrLocatiiRedlong ?> L</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateOperator(2); ?> A</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateDepozitOperator(2) ?> AD</td>
                    <td>)</td>
                </tr>
                <tr data-id="<?php echo $persoana->getIdpers() ?>" data-op="0" onclick="changeResponsabil(this)">
                    <td>T</td>
                    <td>(</td>
                    <td><?php echo($nrLocatiiAmpera + $nrLocatiiRedlong); ?> L</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateOperator(); ?> A</td>
                    <td>/</td>
                    <td><?php echo $persoana->getNumarAparateDepozitOperator(); ?> AD</td>
                    <td>)</td>
                </tr>
                <?php ?>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row margin-top-10"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary col-md-3  padding2 margin0" id="containerLocatii">
                <div class="preload-locatii"></div>
            </div>
            <div class="panel panel-primary col-md-9 padding2 margin0" id="containerLocatie">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            url : "ajax/infoLocatie.php",
            async : true,
            success : function(result){
                $("#containerLocatie").html(result);
            }
        });
        $.ajax({
            url : "ajax/locatiiResponsabil.php",
            async : true,
            success : function(result){
                $(".preload-locatii").fadeOut("slow");
                $("#containerLocatii").html(result);
            }
        })
    });
</script>
</body>
</html>
