<?php
require_once "../autoloader.php";
require_once "../includes/class.db.php";
$datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
$appSettings = new SessionClass();
$db = new DataConnection();

$locatiiMapper = new LocatiiMaper($db, $appSettings);

$locatie = $locatiiMapper->getCurrentLocation();
$macsMapper = new MacPicMapper($db, $appSettings);
$stareAparateMapper = new StareAparateMapper($db, $appSettings);

$aparate = $locatie->getAparateActive();
// print_r($locatie);
$macuri = $macsMapper->getMacs2();

if (count($macuri) > 0) {
    ?>
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign"
              aria-hidden="true">Atentie!!! Au fost montate <strong><?php echo count($macuri); ?></strong> picuri:</span>
        <span class="sr-only"></span>
        <a href="#" class="btn btn-sm btn-primary" id="button-vezi-tabel">Vezi mai multe</a> <button class="btn btn-sm btn-primary" id="buton-sters">Sterge toate macurile de test</button>
    </div>
<?php } ?>
<div style="max-height: 336px; overflow: scroll">
    <table class="table table-responsive table-bordered" style="display:none;" id="table-macuri">
        <thead>
        <tr>
            <th style='width: 30px'>Nr.</th>
            <th style='width: 30px'></th>
            <th style='width: 140px'>Mac</th>
            <th style='max-width: 110px'>Locatie <br />MAC</th>
            <th>Stare</th>
            <th style='width: 110px'>Ip</th>
            <th style='width: 110px'>Locatie <br />IP</th>
            <th>Idx In</th>
            <th>Idx Out</th>
            <th>Ver. Soft</th>
            <th style='width: 100px'>Prima inserare</th>
            <th style='width: 100px'>Ultimul Tact</th>
            <th style='width: 260px'>Aparate</th>
            <th style='width: 50px'>Asociat</th>
            <th>Locatie</th>
            
        </tr>
        </thead>
        <?php
        $nrMac = 1;
        /** @var MacPic $mac */
        foreach ($macuri as $mac) {
        	if ($mac->getStareRetur() === '0') {
        		$tit = 'MAC-uri asociate, catre care s-au transmis date.';
        		$class = 'orange';
        		$style='color: orange; background: #F5F5F5';
        	} elseif ($mac->getStareRetur() === '2') {
        		$tit = 'MAC-uri asociate, catre care nu s-a reusit transmiterea datelor.';
        		$class = 'green';
        		$style='color: green; background: #F5F5F5';
        	} elseif (is_null($mac->getStareRetur()))  {
        		$tit = 'MAC-uri neasociate';
        		$class = 'red';
        		$style='color: red; background: #F5F5F5';
        	}
        	if ($nrMac == 1) {
        		$stare_init = $mac->getStareRetur();
        		echo " <tr> <td colspan='15' class='{$class}' style='{$style}'>".$tit."</td> </tr> ";
        	} else {
        		if ($stare_init != $mac->getStareRetur()) {
                    $stare_init = $mac->getStareRetur();
                    echo " <tr> <td colspan='15' class='{$class}' style='{$style}'>".$tit."</td> </tr> ";
                } 
        	}
            /**----------------------------------------------------------------------
             *  Se cauta locatii unde figureaza IP-ul aferent macului din exceptii
            **---------------------------------------------------------------------*/
                $locatii_ip = $datab->getRows('stareaparate s, aparate a, locatii l', 'l.denumire', 'WHERE s.idAparat = a.idAparat AND a.idLocatie = l.idlocatie AND s.ipPic = ? GROUP BY l.denumire', array($mac->getIp()));
                $locatiile_ip = (count($locatii_ip) > 0) ? '' : '-';
                foreach ($locatii_ip as $key => $locatie) {
                    $locatiile_ip .= '- '. $locatie['denumire'].'<br />';
                }
                if ($mac->getIp() == '82.79.220.114') {
                    $locatiile_ip = 'Ap 11';
                }
            /**------------------------------------------------------------------**/

            /**----------------------------------------------------------------------
             *  Se cauta locatii de unde vin exceptiile care contin "pachet <MAC>"
            **---------------------------------------------------------------------*/
                $locatii_mac = $datab->getRows('errorpk e, aparate a, locatii l', 'e.idAparat, l.denumire', 'WHERE e.idAparat = a.idAparat AND a.idLocatie = l.idlocatie AND e.exceptia LIKE ? GROUP BY e.idAparat ORDER BY e.idpachet DESC', array('%pachet%'.$mac->getMacPic().'%'));
                $locatiile_mac = (count($locatii_mac) > 0) ? '' : '-';
                foreach ($locatii_mac as $key => $locatie) {
                    $locatiile_mac .= '- '. $locatie['denumire'].'<br />';
                }
            /**------------------------------------------------------------------**/
        ?>
            <tr>
                <td><?php echo $nrMac; ?></td>
                <td><img src="/images/red_light.png" width="20px" height="auto"/></td>
                <td><?php echo $mac->getMacPic() ?></td>
                <td><?php echo $locatiile_mac ?></td>
                <td><?php echo $mac->getStareRetur() ?></td>
                <td><?php echo $mac->getIp() ?></td>
                <td><?php echo $locatiile_ip; ?></td>
                <td><?php echo $mac->getIdxInM() ?></td>
                <td><?php echo $mac->getIdxOutM() ?></td>
                <td><?php echo $mac->getSoft() ?></td>
                <td><?php echo date('d M Y', strtotime($mac->getDataPrimaInserare())) ?></td>
                <td><?php echo date('d M Y', strtotime($mac->getDataUltimaInserare())) ?></td>
                <td><?php
                    /** @var AparatEntity $aparat */
                    foreach ($aparate as $aparat) {
//                    var_dump($stareAparateMapper->updateStareAparate($aparat->getStareaparate()));
                   	$active = ($aparat->getSeria() ==  $mac->getSeria()) ? 'style="background: #A94442; color: #fff"' : '';
                        ?>
                        <a href="#" <?php echo $active; ?>
                           data-idAparat="<?php echo $aparat->getIdAparat() ?>"
                           data-mac="<?php echo $aparat->getStareaparate()->getMacPic() ?>"
                           data-macNou="<?php echo $mac->getIdmacpic() ?>"
                           data-seria="<?php echo $aparat->getSeria() ?>"
                           data-macDeAsociat="<?php echo $mac->getMacPic() ?>"
                           class="btn btn-xs btn-primary seriiMacuri"><?php echo $aparat->getSeria(); ?></a>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <span style="font-size: 12px"><?php echo $mac->getSeria(); ?></span>
                </td>
                <td>
                	<span style="font-size: 12px"><?php echo $mac->getDenumireLocatie(); ?></span>
                </td>
            </tr>
            <?php
            $nrMac++;
        } ?>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.seriiMacuri').click(function (event) {
            event.preventDefault();
            var mac = $(this).attr("data-mac");
            var macNou = $(this).attr("data-macNou");
            var seria = $(this).attr("data-seria");
            var idAparat = $(this).attr("data-idAparat");
            var macDeAsociat = $(this).attr("data-macDeAsociat");
            if (mac == "") {
                $.ajax({
                    url: "<?php echo DOMAIN; ?>/ajax/atribuiePic.php",
                    type: "POST",
                    data: {
                        'seria': seria,
                        'idAparat': idAparat,
                        'idmac': macNou,
                        'macDeAsociat': macDeAsociat
                    },
                    success: function (result) {
                        alert(result);
                        location.reload();
                    }
                });
            } else {
                if (window.confirm("Aparatul cu id " + idAparat + " si seria " + seria + " are deja mac-ul " + mac + ". Sunteti sigur ca doriti sa suprascrieti ?")) {
                    $.ajax({
                        url: "<?php echo DOMAIN; ?>/ajax/atribuiePic.php",
                        type: "POST",
                        data: {
                            'seria': seria,
                            'idAparat': idAparat,
                            'idmac': macNou,
                            'macDeAsociat': macDeAsociat
                        },
                        success: function (result) {
                            alert(result);
                            location.reload();
                        }
                    });
                }
            }
        });
        $("#button-vezi-tabel").click(function () {
            $("#table-macuri").toggle();
        });
        $('#buton-sters').on('click', function () {
            $(this).attr("disabled",true);
            $(this).text("Loading ....");
            $.ajax({
                url: "http://red77.ro/ajax/stergeMac.php",
                type: "POST",
                success: function (response) {

//                    alert(response);
                },
                complete : function(){
                    // location.reload();
                }
            });
        });
    });
</script>

