<?php
require_once('includes/dbConnect.php');
if (!isset($_SESSION['operator']) AND !isset($_SESSION['username_redlong'])) {
    header('location:login.php');
}
?>
<div class = "panel-heading">Lista detaliata locatii</div>
<?php
if ($resultDetalii = $con->query('SELECT '
        . 'firme.denumire,'
        . ' locatii.idfirma,'
        . ' locatii.localitate,'
        . ' locatii.idlocatie,'
        . ' locatii.adresa FROM brunersrl.locatii INNER JOIN brunersrl.firme ON locatii.idfirma=firme.idfirma WHERE locatii.idOperator="'.$_SESSION['operator'].'" AND  locatii.dtInchidere="1000-01-01" AND locatii.denumire!="Depozit R" ORDER BY locatii.idlocatie  LIMIT ' . $_POST['offset'] . ',5;')) {
    $index = $_POST['index'];
	while ($objDetalii = $resultDetalii->fetch_object()) {
        ?>
        <div class="panel panel-info <?php echo ($index==$_POST['index']) ? 'çurrent' : ''; ?>" data-cur="<?php echo ($index == $_POST['index']) ? $index : ''; ?>" >
            <div class="panel-heading"><?php echo '<h4 class="'.(($index == $_POST['index']) ? 'orange' : '').'">'.$index.'. ' . $objDetalii->denumire . '</h4> ' . $objDetalii->adresa; $index++; ?></div>
            <div class="panel-body">
                <?php
                $query = 'SELECT 
                    aparate.idAparat,
                    aparate.seria,
                    aparate.tip,
                    avertizari.dtExpAutorizatie,
                    aparate.pozitieLocatie,
                    stareaparate.lastIdxInM,
                    stareaparate.lastIdxOutM,
                    stareaparate.ultimaConectare
                    FROM brunersrl.aparate INNER JOIN brunersrl.stareaparate ON aparate.idAparat = stareaparate.idAparat 
                    INNER JOIN brunersrl.locatii ON locatii.idlocatie=aparate.idLocatie
                    INNER JOIN brunersrl.avertizari ON avertizari.idAparat = aparate.idAparat
                    WHERE aparate.idLocatie="' . $objDetalii->idlocatie . '"  AND locatii.idOperator="'.$_SESSION['operator'].'" AND aparate.dtBlocare="1000-01-01"';
                if ($resultAparate = $con->query($query)) {
                    ?>
                    <table class="table-bordered table-striped table-condensed cf col-md-12">
                        <thead>
                            <tr>
                                <th>Poz Loc</th>
                                <th>Seria</th>
                                <th>Tip</th>
                                <th>Contor IN</th>
                                <th>Contor OUT</th>
                                <th>Expirare Autorizatie</th>
                                <th>Metrologie</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($objAparate = $resultAparate->fetch_object()) {
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $objAparate->seria; ?></td>
                                    <td><?php echo ($objAparate->tip == -1) ? '' : $objAparate->tip; ?></td>
                                    <td><?php echo $objAparate->lastIdxInM; ?></td>
                                    <td><?php echo $objAparate->lastIdxOutM; ?></td>
                                    <td><?php echo $objAparate->dtExpAutorizatie; ?></td>
                                    <td></td>
                                </tr>
                                <?php
                            $i++;}
                            ?>
                        </tbody>
                    </table>
                    <?php /*<div class="col-md-12">
                        <a href="raportzilnic.php?id=<?php echo $objDetalii->idlocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Zilnic</a>
                        <a href="raportlunar.php?<?php echo $objDetalii->idLocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Lunare</a>
                    </div> */ ?>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
}
?>