<?php
require_once('includes/dbConnect.php');

if (!isset($_SESSION['operator']) AND !isset($_SESSION['username_ampera'])) {
    header('location:login.php');
}
?>
<div class = "panel-heading">Lista detaliata locatii</div>
<?php
$queryLocatii = 'SELECT '
        . 'firme.denumire,'
        . ' locatii.idfirma,'
        . ' locatii.localitate,'
        . ' locatii.idlocatie,'
        . ' locatii.adresa FROM brunersrl.locatii INNER JOIN brunersrl.firme ON locatii.idfirma=firme.idfirma WHERE  locatii.idOperator="'.$_SESSION['operator'].'" AND locatii.dtInchidere="1000-01-01" AND locatii.denumire!="Depozit A" ORDER BY locatii.idlocatie AND locatii.denumire!="Depozit A"  LIMIT ' . $_POST['offset'] . ',5;';
if ($resultDetalii = $con->query($queryLocatii)) {
    $index = $_POST['index'];
	while ($objDetalii = $resultDetalii->fetch_object()) {
        ?>
        <div class="panel panel-info <?php echo ($index==$_POST['index']) ? 'çurrent' : ''; ?>" data-cur="<?php echo ($index == $_POST['index']) ? $index : ''; ?>" >
            <div class="panel-heading"><?php echo '<h4 class="'.(($index == $_POST['index']) ? 'orange' : '').'">'.$index.'. ' . $objDetalii->denumire . '</h4> ' . $objDetalii->adresa; $index++; ?></div>
            <div class="panel-body">
                <?php
                $queryAparate = 'SELECT
                                        aparate.idAparat,
                                        aparate.seria,
                                        aparate.tip,
                                        aparate.pozitieLocatie,
                                        stareaparate.lastIdxInM,
                                        stareaparate.lastIdxOutM,
                                        stareaparate.ultimaConectare
                                        FROM brunersrl.aparate INNER JOIN brunersrl.stareaparate ON aparate.idAparat = stareaparate.idAparat
                                        INNER JOIN brunersrl.locatii ON aparate.idLocatie = locatii.idlocatie
                                        WHERE aparate.idLocatie="' . $objDetalii->idlocatie . '" AND locatii.idOperator="' . $_SESSION['operator'] . '" AND aparate.dtBlocare="1000-01-01"';
                if ($resultAparate = $con->query($queryAparate)) {
                    ?>
                    <table class="table-bordered table-striped table-condensed cf col-md-12">
                        <thead>
                            <tr>
                                <th>Poz Loc</th>
                                <th>Seria</th>
                                <th>Tip</th>
                                <?php /*<th>Contor IN</th>
                                <th>Contor OUT</th> */ ?>
                                <th>Ultima accesare</th>
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
                                    <?php /* <td><?php echo $objAparate->lastIdxInM; ?></td>
                                    <td><?php echo $objAparate->lastIdxOutM; ?></td> */?>
                                    <td><?php echo $objAparate->ultimaConectare; ?></td>
                                </tr>
                                <?php
                           $i++; }
                            ?>
                        </tbody>
                    </table>
                    <?php /* <div class="col-md-12">
                        <a href="raportzilnic.php?id=<?php echo $objDetalii->idlocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Zilnic</a>
                        <a href="raportlunar.php?<?php echo $objDetalii->idLocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Lunare</a>
                    </div>
                    <?php */
                }
                ?>
            </div>
        </div>
        <?php
    }
}
?>