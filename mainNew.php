<?php
require_once('classes/SessionClass.php');
require_once('includes/dbConnect.php');
$session = new SessionClass();
if (!isset($_SESSION['username'])) {
    header('location:index.php');
}
if (isset($_GET['id_pers'])) {
    $id_persoana = $_GET['id_pers'];
} else {
    $id_persoana = 1;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <?php require_once('includes/header.php'); ?>
    </head>
    <body>
        <div class="container-fluid">
            <?php require_once 'includes/menu.php'; ?>
            <div class="row">
            </div>
            <div class="spacer"></div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <?php
                        $queryAparateAmpera = "SELECT "
                                . "count(aparate.idlocatie) AS nr_aparate, "
                                . "personal.idpers, "
                                . "personal.nick "
                                . "FROM ampera.aparate "
                                . "INNER JOIN ampera.locatii "
                                . "ON aparate.idLocatie = locatii.idlocatie "
                                . "INNER JOIN ampera.personal "
                                . "ON personal.idpers = locatii.idresp GROUP BY personal.idpers ORDER BY personal.idpers ASC";
                        $resultAparateAmpera = $con->query($queryAparateAmpera);
                        $queryAparateRedlong = "SELECT "
                                . "count(aparate.idlocatie) AS nr_aparate, "
                                . "personal.idpers, "
                                . "personal.nick "
                                . "FROM redlong.aparate "
                                . "INNER JOIN redlong.locatii "
                                . "ON aparate.idLocatie = locatii.idlocatie "
                                . "INNER JOIN redlong.personal "
                                . "ON personal.idpers = locatii.idresp GROUP BY personal.idpers ORDER BY personal.idpers ASC";
                        $resultAparateRedlong = $con->query($queryAparateRedlong);
                        while ($objAparateRedlong = $resultAparateRedlong->fetch_object()) {
                            $numarAparateRedlong[strtolower($objAparateRedlong->nick)] = $objAparateRedlong;
                        }
                        while ($objAparateAmpera = $resultAparateAmpera->fetch_object()) {
                            $numarAparateAmpera[strtolower($objAparateAmpera->nick)] = $objAparateAmpera;
                        }
                        $queryPersonalAmpera = "SELECT"
                                . " p.nick,"
                                . " p.nume,"
                                . " p.prenume,"
                                . " p.telefon,"
                                . " p.user,"
                                . " p.pass,"
                                . " p.idpers,"
                                . " count(l.idresp) as nr_locatii "
                                . "FROM"
                                . " ampera.personal as p "
                                . "INNER JOIN "
                                . " ampera.locatii as l "
                                . "WHERE p.idpers = l.idresp GROUP BY p.idpers ASC";
                        $queryPersonalRedlong = "SELECT"
                                . " p.nick,"
                                . " p.nume,"
                                . " p.prenume,"
                                . " p.telefon,"
                                . " p.user,"
                                . " p.pass,"
                                . " p.idpers,"
                                . " count(l.idresp) as nr_locatii "
                                . "FROM"
                                . " redlong.personal as p "
                                . "INNER JOIN "
                                . " redlong.locatii as l "
                                . "WHERE p.idpers = l.idresp GROUP BY p.idpers ASC";
                        $resultPersonalRedlong = $con->query($queryPersonalRedlong);
                        $resultPersonalAmpera = $con->query($queryPersonalAmpera);
                        while ($objPersonalRedlong = $resultPersonalRedlong->fetch_object()) {
                            while ($objPersonalAmpera = $resultPersonalAmpera->fetch_object()) {
                                $info = '<strong>' . $objPersonalAmpera->nume . ' ' . $objPersonalAmpera->prenume . "</strong><br/>"
                                        . 'Telefon : ' . $objPersonalAmpera->telefon . "<br/>"
                                        . 'User    : ' . $objPersonalAmpera->user . "<br/>"
                                        . 'Pass    : ' . $objPersonalAmpera->pass;
                                ?>
                                <li role="presentation" <?php echo ($id_persoana == $objPersonalAmpera->idpers) ? 'class="active"' : '' ?>>
                                    <a href="?id_pers=<?php echo $objPersonalAmpera->idpers ?>" title="<?php echo $info ?>"><span class="glyphicon  glyphicon glyphicon-user"></span> <?php echo $objPersonalAmpera->nick . ' A( ' . $objPersonalAmpera->nr_locatii . 'L / ' . $numarAparateAmpera[strtolower($objPersonalAmpera->nick)]->nr_aparate . 'A )'; ?></a>
                                    <?php
                                    if (strtolower($objPersonalAmpera->nick) == strtolower($objPersonalRedlong->nick)) {
                                        echo strtolower($objPersonalRedlong->nick);
                                        ?>
                                        <a href="<?php ?>">R(<?php echo $objPersonalRedlong->nr_locatii; ?>L / <?php echo $numarAparateRedlong[strtolower($objPersonalRedlong->nick)]->nr_aparate ?>A)</a>
                                        <?php
                                    }
                                    ?>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Locatii</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php
                                $queryLocatie = "SELECT "
                                        . " locatii.denumire,"
                                        . " locatii.idlocatie "
                                        . "FROM " . $_SESSION['database'] . ".locatii "
                                        . "WHERE "
                                        . " locatii.idresp = " . $id_persoana;
                                $resultLocatii = $con->query($queryLocatie);
                                $i = 1;
                                while ($objLocatii = $resultLocatii->fetch_object()) {
                                    if (isset($_GET['id_locatie'])) {
                                        $id_locatie = $_GET['id_locatie'];
                                    } elseif ($i == 1) {
                                        $id_locatie = $objLocatii->idlocatie;
                                    }
                                    ?>
                                    <li class="list-group-item"><a href="?id_pers=<?php echo $id_persoana; ?>&id_locatie=<?php echo $objLocatii->idlocatie; ?>"><?php
                                            echo $i . '. ' . $objLocatii->denumire;
                                            $i++;
                                            ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="centered">
                                <form method="post">
                                    <fieldset>
                                        <label for="an">Anul</label>
                                        <select name="an">
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                        </select>
                                        <label for="luna">Luna</label>
                                        <select name="luna">
                                            <option value="01">Ianuarie</option>
                                            <option value="02">Februarie</option>
                                            <option value="03">Martie</option>
                                            <option value="04">Aprilie</option>
                                            <option value="05">Mai</option>
                                            <option value="06">Iunie</option>
                                            <option value="07">Iulie</option>
                                            <option value="08">August</option>
                                            <option value="09">Septembrie</option>
                                            <option value="10">Octombrie</option>
                                            <option value="11">Noiembrie</option>
                                            <option value="12">Decembrie</option>
                                        </select>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading">Informatii locatie :</div>
                                <div class="panel-body">
                                    <?php
                                    $queryInfoLocatie = "SELECT
                                        locatii.idlocatie,
                                        locatii.fond,
                                        locatii.telefon,
                                        locatii.persContact,
                                        locatii.adresa,
                                        locatii.regiune,
                                        locatii.localitate,
                                        firme.manager,
                                        firme.denumire
                                    FROM " . $_SESSION['database'] . ".locatii
                                    INNER JOIN " . $_SESSION['database'] . ".firme
                                    ON locatii.idfirma=firme.idfirma WHERE locatii.idlocatie=$id_locatie;";
                                    $resultInfoLocatie = $con->query($queryInfoLocatie);
                                    $objInfoLocatie = $resultInfoLocatie->fetch_object();
                                    ?>
                                    <table class="table table-responsive">
                                        <tr>
                                            <td><strong>Fond:</strong> <?php echo $objInfoLocatie->fond; ?></td>
                                            <td><strong>Incasari noi:</strong> 0</td>
                                            <td><strong>Incasari restante:</strong> 0</td>
                                        </tr>             
                                        <tr>
                                            <td colspan="2"><strong>Detinator:</strong> <?php echo $objInfoLocatie->denumire; ?></td>
                                            <td><strong>Manager:</strong> <?php echo $objInfoLocatie->manager; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Regiune:</strong> <?php echo $objInfoLocatie->regiune; ?></td>
                                            <td><strong>Localitate:</strong> <?php echo $objInfoLocatie->localitate; ?></td>
                                            <td><strong>Adresa:</strong> <?php echo $objInfoLocatie->adresa; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong>Persoana Contact: </strong> <?php echo $objInfoLocatie->persContact; ?></td>
                                            <td><strong>Telefon:</strong> <?php echo $objInfoLocatie->telefon; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading">Infotmatii aparate in incinta locatiei</div>
                                <div class="panel-body">
                                    <table class="table-bordered table-striped table-condensed cf col-md-12 tabel-raport">
                                        <thead>
                                            <tr>
                                                <th class="centered">Nr. Crt.</th>
                                                <th class="centered">Seria</th>
                                                <th class="centered">Tip Aparat</th>
                                                <th class="centered">Total In</th>
                                                <th class="centered">Total Out</th>
                                                <th class="centered">Data</th>
                                                <th class="centered">Actiuni</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $queryInfoAparate = "SELECT 
                                            aparate.idAparat,
                                            aparate.seria,
                                            aparate.tip,
                                            MAX(c1.idxInM) as idxInM,
                                            MAX(c1.idxOutM) as idxOutM,
                                            MAX(c1.dtServer) as dataMax
                                        FROM " . $_SESSION['database'] . ".contormecanic201512 as c1
                                        INNER JOIN " . $_SESSION['database'] . ".aparate 
                                        ON aparate.idAparat = c1.idAparat
                                        WHERE aparate.idLocatie=$id_locatie GROUP BY aparate.idAparat ";
                                            $resultInfoAparate = $con->query($queryInfoAparate);
                                            $j = 1;
                                            while ($objInfoAparate = $resultInfoAparate->fetch_object()) {
                                                ?>

                                                <tr>
                                                    <td><?php
                                                        echo $j;
                                                        $j++;
                                                        ?></td>
                                                    <td><?php echo $objInfoAparate->seria; ?></td>
                                                    <td><?php echo $objInfoAparate->tip; ?></td>
                                                    <td><?php echo $objInfoAparate->idxInM; ?></td>
                                                    <td><?php echo $objInfoAparate->idxOutM; ?></td>
                                                    <td><?php echo $objInfoAparate->dataMax; ?></td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-primary extra">PVI</a>
                                                        <a href="#" class="btn btn-sm btn-primary extra">PVA</a>
                                                        <a href="#" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-random"></span></a>
                                                        <a href="#" class="btn btn-sm btn-success">D</a>
                                                        <a href="#" class="btn btn-sm btn-warning extra">VT</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="panel panel-primary" style="display : none;" id="extraOptiuni">
                                        <div class="panel-heading">
                                            Extra Optiuni
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-center">Genereaza</p>
                                            <
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-center">Incarca</p>
                                            <a href=='' class='btn btn-sm btn-primary center-block'><span class='glyphicon glyphicon-upload'></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="butoane">
                                    <a href="raportzilnic.php?id=<?php echo $objInfoLocatie->idlocatie; ?>" class="btn btn-primary btn-md">Raport Zilnic</a>
                                    <a href="rapoarte/raportlunar.php?id=<?php echo $objInfoLocatie->idlocatie; ?>" class="btn btn-primary btn-md"/>Raport Lunar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
