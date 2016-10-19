<?php
require_once('includes/dbConnect.php');
require_once('classes/mailer/PHPMailerAutoload.php');
require_once('classes/mailer/class.phpmailer.php');
if (isset($_POST['ampera'])) {
    $_SESSION['username_redlong'] = 'KMRLIBWATS';
    $_SESSION['operator'] = '2';
    $_SESSION['com_name'] = 'Red Long';
}
$_SESSION['com_name'] = 'Red Long';
$_SESSION['operator'] = '2';
$numarAparate = aparatePerOperator('2', $con);

if (!isset($_SESSION['operator']) AND !isset($_SESSION['username_redlong'])) {
    header('location:login.php');
}
if (isset($_GET['offset'])) {
    $invalid_characters = array("$", "%", "#", "<", ">", "|");
    $offset = str_replace($invalid_characters, "", $_GET['offset']);
    $selectedItem = $_GET['offset'] + 1;
} else {
    $offset = 0;
    $selectedItem = 1;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<div class="container-fluid">
    <div class="col-md-12">
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Aparate disponibile <span class="highlighted"><?php echo $_SESSION['com_name'] ?>
                    <br/><span><?php echo aparatePerOperator('2', $con) ?></span><a href="logout.php">[LogOut]</a><a href="cautare.php">Cautare in functie de mai multe serii</a></div>
            <ul class="search">
                <li><input type="text" name="cauta_serie" style="width:76%" placeholder="Indroduceti Seria Cautata" id="seria_cautata"/>
                <input type="submit" name="submit" value="Cauta" style="width:20%" class="btn btn-sm btn-primary" id="butonCautare"/></li>
                <li><input type="text" id="box" name="search" class="form-control" placeholder="Cauta Locatie"></li>
            </ul>
            <ul class="list-group" id="nav">
                <?php
                $query = 'SELECT firme.denumire, locatii.idfirma,locatii.idlocatie FROM brunersrl.locatii INNER JOIN brunersrl.firme ON locatii.idfirma=firme.idfirma WHERE locatii.idOperator="' . $_SESSION['operator'] . '" AND locatii.dtInchidere="1000-01-01" AND locatii.denumire!="Depozit R" ORDER BY locatii.idlocatie;';
                if ($result = $con->query($query)) {
                    $i = 0;
                    while ($obj = $result->fetch_object()) {
                        ?>
                        <li class="list-group-item instafilta-target"><a href="#" data-offset="<?php echo $i;
                            $i++; ?>" data-index="<?php echo $i; ?>"
                                                                         class="ajax <?php echo 'current'; ?>"><?php echo $i . '. ' . $obj->denumire; ?></a>
                        </li>
                        <?php
                    }
                    ?><input type="hidden" name="nr_locatii" id="nr_locatii" data-max="<?php echo $i; ?>"><?php
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <div class="panel panel-primary" id="main-right">
            <div class="panel-heading">Lista detaliata locatii</div>
            <?php
            if ($resultDetalii = $con->query('SELECT '
                . 'firme.denumire,'
                . ' locatii.idfirma,'
                . ' locatii.localitate,'
                . ' locatii.idlocatie,'
                . ' locatii.adresa FROM brunersrl.locatii INNER JOIN brunersrl.firme ON locatii.idfirma=firme.idfirma WHERE locatii.idOperator="' . $_SESSION['operator'] . '" AND locatii.dtInchidere="1000-01-01" AND locatii.denumire!="Depozit R"  ORDER BY locatii.idlocatie;')
            ) {
                $k = 1;
                while ($objDetalii = $resultDetalii->fetch_object()) {
                    ?>
                    <div class="panel panel-info" id="<?php echo $k; ?>">
                        <div
                            class="panel-heading"><?php echo '<h4 class="' . (($k == 1) ? 'orange' : '') . '">' . $k . '. ' . $objDetalii->denumire . '</h4> ' . $objDetalii->adresa;
                            $k++; ?></div>
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
                                        INNER JOIN brunersrl.locatii ON aparate.idLocatie = locatii.idlocatie
                                        INNER JOIN brunersrl.avertizari ON avertizari.idAparat = aparate.idAparat
                                        WHERE aparate.idLocatie="' . $objDetalii->idlocatie . '" AND locatii.idOperator="' . $_SESSION['operator'] . '" AND aparate.dtBlocare="1000-01-01"';
                            if ($resultAparate = $con->query($query)) {
                                ?>
                                <table
                                    class="table-bordered table-striped table-condensed cf table-responsive col-md-12">
                                    <thead>
                                    <tr>
                                        <th>Poz Loc</th>
                                        <th>Seria</th>
                                        <th>Tip</th>
                                        <th>Contor IN</th>
                                        <th>Contor OUT</th>
                                        <th>Expirare Autorizatii</th>
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
                                            <td><?php echo $objAparate->dtExpAutorizatie;  ?></td>
                                            <td><a href="ftp://acte:acte77@rodiz.ro/metrologii/2015/<?php echo $objAparate->seria ?>.pdf"
                                                   target="_blank" class="btn btn-sm btn-primary metrologii">Metrologie</a>
                                            <a href="" class="btn btn-sm btn-primary">PVI</a> <a href="" class="btn btn-sm btn-primary">PVA</a></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                                /* ?><div class="col-md-12">
                                  <a href="raportzilnic.php?id=<?php echo $objDetalii->idlocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Zilnic</a>
                                  <a href="raportlunar.php?id=<?php echo $objDetalii->idlocatie; ?>" class="btn btn-primary btn-md right rapoarte">Raport Lunare</a>
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
        </div>
    </div>
</div>
</body>
</html>
