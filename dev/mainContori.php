<?php
require_once('includes/dbConnect.php');
require_once('classes/mailer/PHPMailerAutoload.php');
require_once('classes/mailer/class.phpmailer.php');
if (isset($_POST['ampera'])) {
    $_SESSION['username_redlong'] = 'KMRLIBWATS';
    $_SESSION['operator'] = '2';
    $_SESSION['com_name'] = 'Red Long';
}
$numarAparate = aparatePerOperator('2', $con);
if ($numarAparate != 451) {
    $mail = new PHPMailer();
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'amarcuadrian@yahoo.com';                 // SMTP username
    $mail->Password = "w3bd3v3lop3r";                          // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                   // TCP port to connect to

    $mail->setFrom('amarcuadrian@yahoo.com', 'Mailer');
    $mail->addAddress('amarcuadrian@yahoo.com', 'Marcu Adrian');     // Add a recipient // Name is optional
    $mail->addAddress('brunersrl@yahoo.com', 'Bruner');
    $mail->addAddress('fsanda22@yahoo.com', 'Birou');
    $mail->addAddress('bnesorek@yahoo.com', 'Sorin');

    $mail->Subject = 'Numar aparate redlong!';
    $mail->Body = 'Numarul de aparate este incorect la adresa <a href="http://redlong.ro/minister/login.php">RedLong Minister</a>. Va rugam sa contactati unul dintre responsabili cat mai rapid ';

    $mail->send();
}
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
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Aparate disponibile <span class="highlighted"><?php echo $_SESSION['com_name'] ?>
                    <br/><span><?php echo aparatePerOperator('2', $con) ?></span><a href="logout.php">[LogOut]</a></div>
            <ul class="search">
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
                . ' locatii.adresa FROM brunersrl.locatii INNER JOIN brunersrl.firme ON locatii.idfirma=firme.idfirma WHERE locatii.idOperator="' . $_SESSION['operator'] . '" AND locatii.dtInchidere="1000-01-01" AND locatii.denumire!="Depozit R"  ORDER BY locatii.idlocatie LIMIT ' . $offset . ',5 ;')
            ) {
                $k = 1;
                while ($objDetalii = $resultDetalii->fetch_object()) {
                    ?>
                    <div class="panel panel-info">
                        <div
                            class="panel-heading"><?php echo '<h4 class="' . (($k == 1) ? 'orange' : '') . '">' . $k . '. ' . $objDetalii->denumire . '</h4> ' . $objDetalii->adresa;
                            $k++; ?></div>
                        <div class="panel-body">
                            <?php
                            $query = 'SELECT
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
                                            <td><?php echo $objAparate->lastIdxInM; ?></td>
                                            <td><?php echo $objAparate->lastIdxOutM; ?></td>
                                            <td><?php echo $objAparate->ultimaConectare; ?></td>
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
