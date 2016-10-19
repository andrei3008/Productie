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

?>
<div id="table-macuri-legenda">
    <table style='float: right'>
        <tr> <td style="background: #F2DEDE"> Macuri neasociate</td> </tr>
        <tr> <td style="background: #ec971f"> Macuri asociate, catre care nu s-a reusit transmiterea datelor.</td> </tr>
        <tr> <td style="background: #449d44"> Macuri asociate, catre care s-au transmis date.</td> </tr>
        <tr> <td style=" "> IP test: <strong>82.79.220.114</strong></td> </tr>
    </table>
</div>                      
<table class="table table-responsive table-bordered" style="" id="table-macuri">
    <thead>
        <tr>
            <th style='width: 30px'>#</th>
            <th style='width: 30px'>St.</th>
            <th style='width: 30px'>#Ap</th>
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
    <tbody>
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
                // echo " <tr> <td colspan='15' class='{$class}' style='{$style}'>".$tit."</td> </tr> ";
            } else {
                if ($stare_init != $mac->getStareRetur()) {
                    $stare_init = $mac->getStareRetur();
                    // echo " <tr> <td colspan='15' class='{$class}' style='{$style}'>".$tit."</td> </tr> ";
                } 
            }
            if ($stare_init == '0') {
                 $class_row = 'row-orange';
            } elseif ($stare_init == '2') {
                $class_row = 'row-green';
            } else {
                $class_row = 'row-red';
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
            <tr class="<?php echo $class_row; ?>">
                <td><?php echo $nrMac; ?></td>
                <td><img src="/images/red_light.png" width="20px" height="auto"/></td>
                <td><?php echo ($mac->getidAparatMac()) ? $mac->getidAparatMac() : '0'; ?></td>
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
        } 
    ?>
    </tbody>
</table> 
<style type="text/css">
    .row-orange {
        background: #449d44
    }
    .row-green {
        background: #ec971f
    }
    .row-red {
        background: #F2DEDE
    }
</style>   