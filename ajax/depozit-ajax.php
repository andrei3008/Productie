<?php
require_once('classes/SessionClass.php');
require_once('../includes/dbFull.php');
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
?>

<div class="panel panel-primary">
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-condensed">
            <thead >
            <tr>
                <th>Nr.<br/> crt</th>
                <th>Responsabil</th>
                <th>Provenienta</th>
                <th>Serie</th>
                <th>Contor IN</th>
                <th>Contor OUT</th>
                <th>Data Index</th>
                <th>Zile</th>
                <th>Adresa<br/> depozit</th>
                <th>Firma</th>
                <th>Observatii</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $aparateDepozit = $db->getAparateDepozitByResponsabili();
            $nick = '';
            $denumireLocVechi = '';
            $denumireLocVechi2 = '';
            $i=1;
            foreach ($aparateDepozit as $aparat) {
                if($aparat->denumireLocVechi!=$denumireLocVechi2){
                    $denumireLocVechi2 = $aparat->denumireLocVechi;
                    ?>
                    <tr ><td colspan="11" class="delimiter"></td></tr>
                    <?php
                }
                ?>
                <tr>
                    <td>
                        <?php echo $i; ?>
                    </td>
                    <td><?php if($aparat->denumireLocVechi!=$denumireLocVechi){
                            echo $aparat->nick;
                        } ?></td>
                    <td data-title=""><?php if($aparat->denumireLocVechi!=$denumireLocVechi){
                            $denumireLocVechi = $aparat->denumireLocVechi;
                            echo $denumireLocVechi;
                        }?></td>
                    <td><?php echo $aparat->seria; ?></td>
                    <td><?php echo $aparat->lastIdxInM; ?></td>
                    <td><?php echo $aparat->lastIdxOutM; ?></td>
                    <td><?php echo $aparat->dtLastM; ?></td>
                    <td>
                        <?php
                        $datetime1 = date_create(date('Y-m-d'));
                        $datetime2 = date_create($aparat->dtLastM);
                        $interval = date_diff($datetime2, $datetime1);
                        echo $interval->format('%R%a zile');
                        ?>
                    </td>
                    <td><?php echo $aparat->denumireLocActual; ?></td>
                    <td>
                        <?php
                        $letter = substr($aparat->denumireLocActual, -1, 1);
                        if($letter == "A"){
                            echo "Ampera";
                        }else{
                            echo "Redlong";
                        }
                        ?>
                    </td>
                    <td><a href="ftp://acte:acte77@rodiz.ro/FisierePDF/Metrologii/curente/<?php echo $aparat->seria ?>.pdf"
                           target="_blank" class="btn btn-sm btn-success">Metrologie</a></td>
                </tr>
                <?php

                $i++;
            }
            ?>
            <tr ><td colspan="11" class="delimiter"></td></tr>
            <tr>
                <td colspan="11"><h3>Total : <strong><?php echo $i-1; ?> bucati</strong></h3></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
