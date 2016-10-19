<?php
    require_once('../router.php');
    // require_once('../classes/SessionClass.php');
    // require_once('../includes/dbFull.php');
    // require_once('../classes/PageClass.php');
    $session = new SessionClass();
    $page = new PageClass();
    $appSettings = $session = new SessionClass();
    $session->exchangeArray($_SESSION);
    $db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
    $page->checkLogin($session->getUsername(), $session->getOperator());
    $dbScrie = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
    $get = $db->sanitizePost($_GET);

    $id_persoana = $_POST['idResp'];
    $idOperator = $_POST['idOperator'];
    $id_locatie = $_POST['idLoc'];

    if (!isset($_POST['luna']) OR !isset($_POST['an'])) {
        $luna = date('n');
        $an = date('Y');
    } else {
        $luna = $_POST['luna'];
        $an = $_POST['an'];
    }
    $a = new DateTime();
    $b = new DateTimeZone('Europe/Bucharest');
    $a->setTimezone($b);
    $a->format('Y-m-d H:i:s');
    error_reporting(E_ALL);
?>
<div class="panel panel-primary" id="mainPanel">
    <?php $objInfoLocatie = $db->getLocationInfo($id_locatie); ?>
    <div class="panel-heading">
        <span class="width80">
            <?php echo '<strong>' . $objInfoLocatie->nickLocatie . '</strong> / ' . $objInfoLocatie->denumire . ' / ' . $objInfoLocatie->adresa ?>
        </span>
        <span class="text-right width20">
            <strong> IdOp :</strong>
                <?php echo $objInfoLocatie->idOperator; ?>
            <strong>IdLoc :</strong>
                <?php echo $objInfoLocatie->idlocatie; ?>
        </span>
<!--         <a id="tabel-sas" class="btn btn-warning btn-md" 
            data-idloc="<?php echo $id_locatie;?>" 
            data-op="<?php echo $objInfoLocatie->idOperator;?>" 
            data-resp="<?php echo $session->getIdresp();?>">
            SAS
        </a> -->
        <a id="tabel-man" class="btn btn-info btn-md" 
            data-idloc="<?php echo $id_locatie;?>" 
            data-op="<?php echo $objInfoLocatie->idOperator;?>" 
            data-resp="<?php echo $session->getIdresp();?>">
            Mecanic
        </a>
    </div>
    <div class="body">
        
            <form method="POST" id="aparate-sas">
                <table class="table-bordered table-striped table-condensed cf col-md-12 tabel-raport zoomable">
                    <thead>
                        <tr>
                            <th>Nr.</th>
                            <th class="centered">Poz</th>
                            <th class="centered">ID AP</th>
                            <th class="centered">Seria</th>
                            <th class="centered">Tip</th>
                            <th class="centered">V</th>
                            <th class='centered'>St Idx</th>
                            <th class="centered">Total In Electr.</th>
                            <th class="centered">Total Out Electr.</th>
                            <th>WAN</th>
                            <th>3G</th>
                            <th class="centered">Data</th>
                            <th class="centered">Istoric</th>
                            <th class="centered">Inactivitate</th>
                        </tr>
                    </thead>
                    <tbody id="aparateTarget">
                    <?php
                        $eroriAparate = $db->verificaErroriIndex($id_locatie);
                        $aparateInfo = $db->getInfoAparateElectr($id_locatie, $id_persoana, $luna, $an);
                        $j = 1;
                        if (count($aparateInfo) > 0) {
                            foreach ($aparateInfo as $objInfoAparate) {
                                $pachete = $db->getPacheteAparat($objInfoAparate->idAparat, $an, $luna, date('d'));
                    ?>
                                <tr class="rowAparat <?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
                                    <td rowspan="2">
                    <?php
                                        echo $j;
                                        $j++;
                    ?>
                                    </td>
                                    <td rowspan="2">
                                        <input class="noClick" type="text" style="width:20px;"
                                           size="2"
                                           name="aparat-<?php echo $objInfoAparate->idAparat; ?>"
                                           value="<?php echo $objInfoAparate->pozitieLocatie; ?>"/>
                                    </td>
                                    <td rowspan="2">
                    <?php
                                        $url = "http://$objInfoAparate->ipPic:" . ($objInfoAparate->pozitieLocatie + 60);
                    ?>
                                        <img  src="<?php echo DOMAIN;?>/css/images/<?php echo ($page->checkPic($url) == TRUE) ? 'green_light.png' : 'red_light.png'; ?>"
                                            style="width:20px; height:20px;"/>
                                        <a class="ipPic" href="http://<?php echo $objInfoAparate->ipPic; ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>">
                                                <?php echo $objInfoAparate->idAparat; ?>
                                        </a>
                                        || 
                                        <a class="techPic" href="http://admin:ampera@<?php echo $objInfoAparate->ipPic ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>/tech/">T
                                        </a>
                                    </td>
                    <?php
                                    $a->format('Y-m-d H:i:s');
                                    $datetime2 = date_create($objInfoAparate->ultimaConectare, $b);
                                    $interval = date_diff($datetime2, $a);
                                    $diferentaOre = $interval->format('%h');
                    ?>
                                    <td rowspan="2"><?php echo $objInfoAparate->seria; ?></td>
                                    <td rowspan="2"><?php echo $page->maxText($objInfoAparate->tip, 4); ?></td>
                                    <td rowspan="2"><?php echo $objInfoAparate->verSoft ?></td>
                                    <td rowspan="2">
                                        <img src='<?php echo DOMAIN;?>/images/triangle_<?php echo ($db->getNrEroriAparat($objInfoAparate->idAparat) == 0) ? 'blue' : 'red'; ?>.png' class='eroriPic' mac-aparat='<?php echo $objInfoAparate->macPic; ?>' serie-Aparat='<?php echo $objInfoAparate->seria ?>' data-idAparat='<?php echo $objInfoAparate->idAparat ?>'/>
                                        <?php
                                            if ($db->getNrEroriAparat($objInfoAparate->idAparat) > 0) {
                                                echo $db->tipEroare($objInfoAparate->idAparat);
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $page->getIndexReset($objInfoAparate->lastIdxInE); ?>
                                        <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastIdxInE)); ?>
                                    </td>
                                    <td>
                                        <?php echo $page->getIndexReset($objInfoAparate->lastIdxOutE); ?>
                                        <?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastIdxOutE)); ?>
                                    <td class="grey"><?php echo isset($pachete->nrPacWan) ? $pachete->nrPacWan : 0; ?></td>
                                    <td class="grey"><?php echo isset($pachete->nrPac3g) ? $pachete->nrPac3g : 0; ?></td>
                                    <td>
                                        <?php
                                            echo $page->afiseazaData($objInfoAparate->ultimaConectare);
                                            $diferentaZile = $interval->format('%a');
                                        ?>
                                    </td>
                                    <td>
                                        <a class='btn btn-sm btn-primary istoricAparate' 
                                            data-id="<?php echo $objInfoAparate->idAparat; ?>"
                                            data-seria="<?php echo $objInfoAparate->seria; ?>">
                                            H
                                        </a>
                                    </td>
                                    <td><?php echo $db->getDataPornire($objInfoAparate->idAparat, $an, $luna, date('d')); ?></td>
                                </tr>
                                <?php 
                                    $pachetePrecedente = $db->getPacheteAparat($objInfoAparate->idAparat, $an, $luna, date('d') - 1);
                                ?>
                                <tr class="<?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
                                    <?php 
                                        $valori = $db->getCashInCashOut($objInfoAparate->idAparat, $an, $luna, date('d')); 
                                    ?>
                                    <td style="text-align: right; padding-right: 15px;"><?php //echo $valori['cashIn']; ?></td>
                                    <td style="text-align: right; padding-right: 15px;"><?php //echo $valori['cashOut']; ?></td>
                                    <td class="lightgrey"><?php echo isset($pachetePrecedente->nrPacWan) ? $pachetePrecedente->nrPacWan : 0; ?></td>
                                    <td class="lightgrey"><?php echo isset($pachetePrecedente->nrPac3g) ? $pachetePrecedente->nrPac3g : 0; ?></td>
                                    <td>MAC: <?php echo $objInfoAparate->macPic; ?></td>
                                    <td></td>
                                    <td>
                                        <img src="<?php echo DOMAIN;?>/images/<?php echo ($diferentaOre >= 1 or $diferentaZile >= 1) ? 'red_light.png' : 'green_light.png' ?>"
                                            style="width: 20px; height : 20px; float:right"/>
                                        <?php
                                            if ($diferentaZile == 0) {
                                                echo $interval->format('%hh%im%ss');
                                            } else {
                                                echo $interval->format('%R%a zile');
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="aparateActions">
                                    <td colspan="13">
                                        <a href="#" class="btn btn-sm btn-primary">Poze Teren</a>
                                        <?php
                                        	/*<a href="#" class="btn btn-sm btn-primary audit"
                                           data-ip="<?php echo $objInfoAparate->ipPic; ?>"
                                           data-port="<?php echo($objInfoAparate->pozitieLocatie + 60) ?>"
                                           data-seria="<?php echo $objInfoAparate->seria ?>"
                                           data-id="<?php echo $objInfoAparate->idAparat; ?>">Audit Aparat</a>*/
                                      	?>
                                        <a href="#" class="btn btn-sm btn-primary extra">PVA</a>
                                        <a href="#" class="btn btn-sm btn-primary"><span
                                                class="glyphicon glyphicon-random"></span></a>
                                        <a href="#" class="btn btn-sm btn-primary">D</a>
                                        <a href="#" class="btn btn-sm btn-primary extra">VT</a>
                                        <?php
                                        //$dataMetro = "<form method='POST' enctype='multipart/form-data' ><input type='hidden' name='dosar' value='Metrologii'/><input type='file' class='uploadMetro' name='upload' value='upload'><input type='hidden' name='serie' value='" . $objInfoAparate->seria . "'/></form>";
                                        ?>
                                        <!--<a href="#" class="btn btn-sm btn-success metro" data-title="Actiuni"  data-placement="left" data-trigger="click" data-html="TRUE">Metro</a>-->
                                        <a href="ftp://acte:acte77@rodiz.ro/metrologii/curente/<?php echo $objInfoAparate->seria ?>.pdf"
                                           target="_blank"
                                           class="btn btn-sm btn-primary metrologii">Metrologie</a>
                                        <a href='ftp://acte:acte77@rodiz.ro/autorizatii/curente/<?php echo $objInfoAparate->seria; ?>.pdf'
                                           class='btn btn-sm btn-primary autorizatii'>Autorizatii</a>
                                        <a href="idxZile.php?idAparat=<?php echo $objInfoAparate->idAparat; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>"
                                           class="btn btn-primary">Idx Zile</a>
                                       <a href="interfataPic/game.php?seria=<?php echo $objInfoAparate->seria; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>"
                                           class="btn btn-success configurare" target="_blank">Configurare</a>
                                       <div class="loading"><img src="<?php echo DOMAIN;?>/css/AjaxLoader.gif" /></div>
                                    </td>
                                </tr>
                    <?php
                            }
                        }
                    ?>
                    </tbody>
                </table>
                <input type="submit" name="submit" value="submit" style="display: none;"
                       id="submit-me"/>
                <input type="hidden" name="form-name" value="index"/>
            </form>
            <div class="panel panel-primary" style="display : none;" id="extraOptiuni">
                <div class="panel-heading">
                    Extra Optiuni
                </div>
                <div class="col-md-6">
                    <p class="text-center">Genereaza</p>
                    <a href="" class="btn btn-sm btn-primary center-block">
                        <span class="glyphicon glyphicon-download"></span>
                    </a>
                </div>
                <div class="col-md-6">
                    <p class="text-center">Incarca</p>
                    <a href="" class="btn btn-sm btn-primary center-block">
                        <span class="glyphicon glyphicon-upload"></span>
                    </a>
                </div>
            </div>
        
        <div class="butoane">
            <a href="<?php echo DOMAIN ?>/rapoarte/raportzilnic.php?id=<?php echo $objInfoLocatie->idlocatie; ?>"
               class="btn btn-primary btn-md"
               target="_blank">Raport Zilnic</a>
            <a href="rapoarte/raportlunar.php"
               class="btn btn-primary btn-md"
               target="_blank>Raport Lunar</a>
            <a href="#" class="btn btn-primary" id="save-form-submit">Salveaza Pozitie Locatie</a>
            <a href="#" class="btn btn-primary" id="inventar">Adauga obiecte in inventar</a>
            <a href="#" class="btn btn-primary" id="changeZoom">Activeaza Zoom</a>
            <?php
                $footer = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                 <button type=\"button\" class=\"btn btn-primary\" id='salveazaInventar'>Introdu Element</button>";
                $formular = $page->createFieldset('text', 'denumire', 'Denumire Produs', "id='denProdInv' class='form-control'");
                $formular .= $page->createFieldset('text', 'cantitate', 'Cantitate in bucati', "id='canProdInv' class='form-control'");
                $formular .= $page->createFieldset('text', 'stare', 'Stare Produse', "id='stareProdInv' class='form-control'");
                $formular .= $page->createFieldset('text', 'observatii', 'Observatii', "id='observatiiProdInv' class='form-control'");
                echo $page->createModal('inventar-modal', 'Adauga Elemente In Inventar', $formular, $footer);
            ?>
            <a href="#" class="btn btn-success" id="refreshLocatie">Refresh Locatie</a>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#refreshLocatie').click(function (event) {
                        event.preventDefault();
                        var idLocatie = $('#idLocProdInv').attr('placeholder');
                        var idPers = <?php echo $id_persoana ?>;
                        var an = <?php echo $an; ?>;
                        var luna = <?php echo $luna; ?>;
                        $.ajax({
                            type: "POST",
                            url: 'ajax/aparateAjax.php',
                            data: {
                                'idLocatie': idLocatie,
                                'idPersoana': idPers,
                                'an': an,
                                'luna': luna
                            },
                            success: function (result) {
                                var loader = $('#loader').html();
                                $('#aparateTarget').html(loader);
                                setTimeout(function () {
                                    $('#aparateTarget').html(result);
                                }, 1000);

                            }
                        });
                    });
                });
            </script>
            <div class="panel panel-info">
                <div class="panel-heading" id="infoFirma">Click pentru informatii locatie
                    ||
                    <?php
                        $net = $db->getNetByLocation($id_locatie);
                        foreach ($net as $netElement) {
                            switch ($netElement->tip) {
                                case 1 :
                                    echo 'Router1 - ';
                                    break;
                                case 2 :
                                    echo 'Router2 - ';
                                    break;
                                case 3 :
                                    echo 'Camere';
                                    break;
                            }
                    ?>
                            <a href="http://<?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic : ''; ?>:<?php echo $netElement->port ?>"
                               target="_blank">
                                <?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic : ''; ?>
                            </a> ||
                    <?php
                        }
                    ?>
                        || 3G IP - 
                        <a href="http://<?php echo (array_key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic3g : ''; ?>">
                            <?php echo (key_exists(0, $aparateInfo)) ? $aparateInfo[0]->ipPic3g : ''; ?>
                        </a>
                </div>
                <div class="panel-body" style="display : none;">
                    <table class="table table-responsive">
                        <tr>
                            <td><strong>Fond:</strong> <?php echo $objInfoLocatie->fond; ?></td>
                            <td><strong>Incasari noi:</strong> 0</td>
                            <td><strong>Incasari restante:</strong> 0</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Detinator:</strong> <?php echo $objInfoLocatie->denumire; ?>
                            </td>
                            <td><strong>Manager:</strong> <?php echo $objInfoLocatie->manager; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Regiune:</strong> <?php echo $objInfoLocatie->regiune; ?></td>
                            <td><strong>Localitate:</strong> <?php echo $objInfoLocatie->localitate; ?></td>
                            <td><strong>Adresa:</strong> <?php echo $objInfoLocatie->adresa; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Persoana
                                    Contact: </strong> <?php echo $objInfoLocatie->persContact; ?></td>
                            <td><strong>Telefon:</strong> <?php echo $objInfoLocatie->telefon; ?></td>
                            <td>
                                <fieldset class="form-inline">
                                    <strong>Parola Noua : </strong>
                                    <input type="password" name="pass" class="form form-control"
                                           id="form-pass"/>
                                    <input type="hidden" name="loc" value="<?php echo $id_locatie; ?>"
                                           id="val_loc"/>
                                    <input type="submit" value="Schimba" id="schimbaParola"
                                           class="btn btn-md btn-default"/>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <form method="POST">
                        <table class="table table-responsive">
                            <input type="hidden" name="loc" value="<?php echo $id_locatie; ?>"/>
                            <input type="hidden" value="<?php echo(isset($net[1]) ? 'update' : 'insert') ?>" name="actiune"/>
                            <?php 
                                for ($i = 1; $i < 4; $i++) {
                            ?>
                                    <tr>
                                        <td>
                            <?php
                                            switch ($i) {
                                                case 1 :
                                                    echo 'Rooter 1';
                                                    break;
                                                case 2 :
                                                    echo 'Rooter 2';
                                                    break;
                                                case 3 :
                                                    echo 'Rooter 3';
                                                    break;
                                            }
                            ?>
                                        </td>
                                        <td>
                                            <fieldset style="display:inline-block">
                                                Port :
                                                <input type="text"
                                                       name="port_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                       size="2" class="noClick"
                                                       value="<?php echo(isset($net[$i]) ? $net[$i]->port : '80'); ?>"/>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset style="display:inline-block">
                                                User:
                                                <input type="text"
                                                       name="user_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                       class="noClick"
                                                       value="<?php echo(isset($net[$i]) ? $net[$i]->valUser : 'user'); ?>"/>
                                            </fieldset>
                                        </td>
                                        <td>
                                            <fieldset style="display:inline-block">
                                                Parola
                                                <input type="text"
                                                       name="parola_<?php echo(isset($net[$i]) ? $net[$i]->idNet : $i); ?>"
                                                       class="noClick"
                                                       value="<?php echo isset($net[$i]) ? $net[$i]->valPass : 'parola'; ?>"/>
                                            </fieldset>
                                        </td>
                                    </tr>
                            <?php 
                                } 
                            ?>
                                <tr>
                                    <td colspan="4">
                                        <fieldset style="display:inline-block">
                                            <input type="hidden" name="idLocatie"
                                                   value="<?php echo $id_locatie; ?>"/>
                                            <button type="submit" name="form-rooter-locatie"
                                                    value="form-rooter-locatie" class="btn btn-primary">Salveza
                                            </button>
                                        </fieldset>
                                    </td>
                                </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div id="loader">
                <img src="../css/AjaxLoader.gif"/>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading" id="panel-angajati">Click aici pentru a vizualiza angajatii.  </div>
                <div class="panel-body table-responsive" style="display: none;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Principal</th>
                            <th>Nume</th>
                            <th>Telefon</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                $angajati = $db->getAngajati($id_locatie);
                                $i = 1;
                                foreach ($angajati as $angajat) {
                                    if ($i == 1) {
                                        $idAngajatActual = $angajat->idpers;
                                    }
                            ?>
                                    <tr>
                                        <td>
                                            <input type="radio"
                                                   name="angajat" <?php echo (strpos($angajat->telefon, 'p') !== FALSE) ? 'checked' : ''; ?>
                                                   value="<?php echo $idAngajatActual . "_" . $angajat->idpers ?>"
                                                   data-telefon="<?php echo str_replace('p', '', $angajat->telefon); ?>"
                                                   class="angajati">
                                        </td>
                                        <td><?php echo $angajat->nume . ' ' . $angajat->prenume; ?></td>
                                        <td>
                                            <a href="tel:<?php echo str_replace('p', '', $angajat->telefon); ?>">
                                                <?php echo str_replace('p', '', $angajat->telefon); ?>
                                            </a>
                                        </td>
                                        <td><?php echo $angajat->email; ?></td>
                                    </tr>
                            <?php
                                    $i++;
                                }   

                                $footerPersonal = "  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                     <button type=\"button\" class=\"btn btn-primary\" id='salveazaPersonal'>Introdu Angajat</button>";
                                $formularPersonal = $page->createFieldset('text', 'nume', 'Numele de familie', "id='numePersonal' class='form-control'");
                                $formularPersonal .= $page->createFieldset('text', 'prenume', 'Prenumele', "id='prenumePersonal' class='form-control'");
                                $formularPersonal .= $page->createFieldset('text', 'nick', "Nick", "id='nickPersoana' class='form-control'", 1);
                                $formularPersonal .= $page->createFieldset('text', 'telefon', 'Telefon', "id='telefonPersonal' class='form-control'", 1);
                                $formularPersonal .= $page->createFieldset('email', 'émail', 'Email', "id='emailPersonal' class='form-control'");
                                echo $page->createModal('addPersonal', 'Adauga Personal la locatie', $formularPersonal, $footerPersonal);
                            ?>
                        </tbody>
                    </table>
                    <a href="#" class="btn btn-primary" id="adaugaPersonal">Adauga Angajat</a>
                </div>
            </div>
            <div class="panel panel-info" style="margin-top: 5px;">
                <div class="panel-heading" id="inventarPanel">Obiecte pe inventar</div>
                <div class="panel-body table-responsive" style="display: none;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>Nr. Crt.</td>
                            <td>Nume</td>
                            <td>Cantitate</td>
                            <td>Stare</td>
                            <td>Observatii</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $inventar = $db->getInventar($id_locatie);
                        $k = 1;
                        foreach ($inventar as $obiect) {
                            ?>
                            <tr>
                                <td><?php
                                    echo $k;
                                    $k++;
                                    ?></td>
                                <td><?php echo $obiect->denumire; ?></td>
                                <td><?php echo $obiect->cantitate; ?></td>
                                <td><?php echo $obiect->stare ?></td>
                                <td><?php echo $obiect->observatii; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>