<?php
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
error_reporting(0);
$session = new SessionClass();
$page = new PageClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
$a = new DateTime();
$b = new DateTimeZone('Europe/Bucharest');
$a->setTimezone($b);
$post = $db->sanitizePost($_POST);

$aparateInfo = $db->getInfoAparate($post['idLocatie'], $post['idPersoana'], $post['luna'], $post['an']);
$j = 1;
$eroriAparate = $db->verificaErroriIndex($post['idLocatie']);

if (count($aparateInfo) > 0) {
    foreach ($aparateInfo as $objInfoAparate) {

        $pachete = $db->getPacheteAparat($objInfoAparate->idAparat, $post['an'], $post['luna'], date('d'));
        ?>
        <tr class="rowAparat <?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
            <td rowspan="2"><?php
                echo $j;
                $j++;
                ?></td>
            <td rowspan="2"><input class="noClick" type="text" style="width:20px;" size="2" name="aparat-<?php echo $objInfoAparate->idAparat; ?>"value="<?php echo $objInfoAparate->pozitieLocatie; ?>"/></td>
            <td rowspan="2">
                <?php
                $url = "http://$objInfoAparate->ipPic:" . ($objInfoAparate->pozitieLocatie + 60);
                ?>
                <img
                    src="css/images/<?php echo ($page->checkPic($url) == TRUE) ? 'green_light.png' : 'red_light.png'; ?>"
                    style="width:20px; height:20px;"/>
                <a class="ipPic"
                   href="http://<?php echo $objInfoAparate->ipPic; ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>"><?php echo $objInfoAparate->idAparat; ?></a>
                || <a class="techPic"
                      href="http://admin:ampera@<?php echo $objInfoAparate->ipPic ?>:<?php echo $objInfoAparate->pozitieLocatie + 60; ?>/tech/">T</a></a>
                <?php
                $a->format('Y-m-d H:i:s');
                $datetime2 = date_create($objInfoAparate->ultimaConectare, $b);
                $interval = date_diff($datetime2, $a);
                $diferentaOre = $interval->format('%h');
                ?></td>
        <td rowspan="2"><?php echo $objInfoAparate->seria; ?></td>
        <td rowspan="2"><?php echo $page->maxText($objInfoAparate->tip, 4); ?></td>
        <td rowspan="2"><?php echo $objInfoAparate->verSoft ?></td>
        <td rowspan="2">
            <img src='css/images/triangle_<?php echo ((!isset($eroriAparate[$objInfoAparate->idAparat]) OR $eroriAparate[$objInfoAparate->idAparat]->nrErori < 1 AND $objInfoAparate->dtBlocare != '1000-01-01') ? 'blue' : 'red'); ?>.png' class='eroriPic' serie-Aparat='<?php echo $objInfoAparate->seria ?>' data-idAparat='<?php echo $objInfoAparate->idAparat ?>'/>
        </td>
        <td><?php echo $page->getIndexReset($objInfoAparate->lastidxInM); ?><?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastidxInM)); ?>
        </td>
        <td><?php echo $page->getIndexReset($objInfoAparate->lastidxOutM); ?><?php echo $page->niceIndex($page->verifyIndexLength($objInfoAparate->lastidxOutM)); ?>
        <td><?php echo isset($pachete->nrPacWan) ? $pachete->nrPacWan : 0; ?></td>
        <td><?php echo isset($pachete->nrPac3g) ? $pachete->nrPac3g : 0; ?></td>
        <td><?php
            echo $page->afiseazaData($objInfoAparate->ultimaConectare);
            $diferentaZile = $interval->format('%a');
            ?></td>
        <td>
            <a class='btn btn-sm btn-primary istoricAparate' 
                data-id="<?php echo $objInfoAparate->idAparat; ?>"
                data-seria="<?php echo $objInfoAparate->seria; ?>">
                H
            </a>
        </td>
        <td><?php echo $db->getDataPornire($objInfoAparate->idAparat, $post['an'], $post['luna'], date('d')); ?></td>
        </tr>
        <?php $pachetePrecedente = $db->getPacheteAparat($objInfoAparate->idAparat, $post['an'], $post['luna'], (date('d') - 1)) ?>
        <tr class="<?php echo ($objInfoAparate->dtBlocare != '1000-01-01') ? 'blocat' : ''; ?>">
            <?php $valori = $db->getCashInCashOut($objInfoAparate->idAparat, $post['an'], $post['luna'], date('d')); ?>
            <td style="text-align: right; padding-right: 15px;"><?php echo $valori['cashIn']; ?></td>
            <td style="text-align: right; padding-right: 15px;"><?php echo $valori['cashOut']; ?></td>
            <td><?php echo isset($pachetePrecedente->nrPacWan) ? $pachetePrecedente->nrPacWan : 0; ?></td>
            <td><?php echo isset($pachetePrecedente->nrPac3g) ? $pachetePrecedente->nrPac3g : 0; ?></td>
            <td>MAC: <?php echo $objInfoAparate->macPic ?></td>
            <td>

            </td>
            <td><img 
                    src="css/images/<?php echo ($diferentaOre >= 1 or $diferentaZile >= 1) ? 'red_light.png' : 'green_light.png' ?>"
                    style="width: 20px; height : 20px; float:right"/><?php
                    if ($diferentaZile == 0) {
                        echo $interval->format('%hh%im%ss');
                    } else {
                        echo $interval->format('%R%a zile');
                    }
                    ?></td>
        </tr>
        <tr class="aparateActions">
            <td colspan="13">
                <a href="#" class="btn btn-sm btn-primary">Poze Teren</a>
                <a href="#" class="btn btn-sm btn-primary audit"
                   data-ip="<?php echo $objInfoAparate->ipPic; ?>"
                   data-port="<?php echo($objInfoAparate->pozitieLocatie + 60) ?>"
                   data-seria="<?php echo $objInfoAparate->seria ?>"
                   data-id="<?php echo $objInfoAparate->idAparat; ?>">Audit Aparat</a>
                <a href="#" class="btn btn-sm btn-primary extra">PVA</a>
                <a href="#" class="btn btn-sm btn-primary"><span
                        class="glyphicon glyphicon-random"></span></a>
                <a href="#" class="btn btn-sm btn-primary">D</a>
                <a href="#" class="btn btn-sm btn-primary extra">VT</a>
                <?php
//$dataMetro = "<form method='POST' enctype='multipart/form-data' ><input type='hidden' name='dosar' value='Metrologii'/><input type='file' class='uploadMetro' name='upload' value='upload'><input type='hidden' name='serie' value='" . $objInfoAparate->seria . "'/></form>";
                ?>
                <!--<a href="#" class="btn btn-sm btn-success metro" data-title="Actiuni"  data-placement="left" data-trigger="click" data-html="TRUE" data-content="<?php echo $dataMetro; ?>">Metro</a>-->
                <a href="ftp://acte:acte77@rodiz.ro/metrologii/curente/<?php echo $objInfoAparate->seria ?>.pdf"
                   target="_blank" class="btn btn-sm btn-primary metrologii">Metrologie</a>
                   <?php $dataAutorizatii = "<form method='POST' enctype='multipart/form-data' ><input type='hidden' name='dosar' value='Autorizatii'/><input type='file' class='uploadMetro' name='upload' value='upload'><input type='hidden' name='serie' value='" . $objInfoAparate->seria . "'/></form>"; ?>
                <a href='#' class='btn btn-sm btn-primary autorizatii'
                   data-title='Actiuni' data-placement='left' data-trigger='click'
                   data-html='TURE' data-content="<?php echo $dataAutorizatii; ?>">Autorizatii</a>
                <a href="idxZile.php?idAparat=<?php echo $objInfoAparate->idAparat; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>" class="btn btn-primary">Idx Zile</a>
                <a href="interfataPic/game.php?seria=<?php echo $objInfoAparate->seria; ?>&an=<?php echo $an ?>&luna=<?php echo $luna ?>"
                                                   class="btn btn-success configurare" target="_blank">Configurare</a>
                                               <div class="loading"><img src="css/AjaxLoader.gif" /></div>
            </td>
        </tr>
        <?php
    }
}
?>