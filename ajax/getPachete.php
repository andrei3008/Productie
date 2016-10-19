<?php
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
$page = new PageClass();
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);
$post = $db->sanitizePost($_POST);

$an = $post['an'];
$luna = $post['luna'];
$idPers = $post['idResponsabil'];
if ($post['tipPachete'] == 'wan') {
    echo $page->getWell('Tabela Pachete WAN');
    ?>
    <table class="table table-responsive table-bordered">
    <?php $rezultat = $db->getLocatii('', $idPers, $an, $luna);
    ?>
    <thead>
    <tr>
        <th>Nr. Crt</th>
        <th>Nume Locatie</th>
        <th>Serie Aparat</th>
        <?php
        $nrZile = $page->zileInLuna($luna, $an);
        for ($i = 1; $i <= $nrZile; $i++) {
            if ($i == date('d') and $luna == date('n') and $an == date('Y')) {
                ?>
                <th><?php echo 'Azi' ?></th><?php
            } else {
                ?>
                <th><?php echo $i; ?></th><?php
            }
        }
        ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $j = 1;
    $nrAparate = 0;
    foreach ($rezultat as $locatieArray) {
        $totalLocatie = 0;
        $locatie = reset($locatieArray);
        $index = key($locatieArray);
        $aparate = $db->getCastigAparate($an, $luna, $locatie->idlocatie);
        $k = 1;
    foreach ($aparate as $aparat) {
        $infoAparat = reset($aparat);
        ?>
        <tr data-luna="<?php echo $luna ?>" data-an="<?php echo $an ?>"
            data-idAparat="<?php echo $infoAparat->idAparat ?>" class="<?php if(($k <= $index) AND ($j % 2 == 0)){ echo 'greyedOut';} ?>">
            <?php
            if ($k == 1) {
                ?>
                <td rowspan='<?php echo $index ?>'
                    style="vertical-align: middle;"><?php echo $j; ?></td>
                <td rowspan='<?php echo $index ?>'
                    style="vertical-align: middle;"><?php echo $locatie->denumire . ' ' . $index ?>
                Aparate</td><?php
            }
            ?>
            <td>
                <?php
                echo $infoAparat->seria;
                $nrAparate++;
                ?></td>
            <?php
            $totalAparat = 0;
            for ($i = 1; $i <= $nrZile; $i++) {
                ?>
                <td class="<?php
                $data = strtotime($an . '-' . $luna . '-' . $i);
                echo(($data > strtotime(date('Y-m-d'))) ? 'blocat' : ''); ?>
                <?php if(!array_key_exists($i,$aparat) OR ($aparat[$i]->nrPacWan == 0 AND $aparat[$i]->nrPac3g == 0)){ echo 'cTransmis';} ?>">
                    <?php echo array_key_exists($i,$aparat) ? $aparat[$i]->nrPacWan : 0; ?>
                </td>
                <?php
                if($k== $index){
                    $j++;
                }
            }
            ?>
        </tr>
    <?php
    $k++;
    }
    ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#total-<?php echo $locatie->idlocatie ?>').html(<?php echo round($totalLocatie,1) ?>);
            })
        </script>
        <?php
    }
    ?>
    <tr>
        <td colspan="<?php echo($page->zileInLuna($luna, $an) + 5); ?>"><h3>Total Castig Lunar : <span
                    id="grandTotal"></span></h3></td>
    </tr>
    </tbody>
    </table><?php

} elseif ($post['tipPachete'] == '3g') {
    echo $page->getWell('Pachete 3g');
    ?>
    <table class="table table-responsive table-bordered">
        <?php $rezultat = $db->getLocatii('', $idPers, $an, $luna);
        ?>
        <thead>
        <tr>
            <th>Nr. Crt</th>
            <th>Nume Locatie</th>
            <th>Serie Aparat</th>
            <?php
            $nrZile = $page->zileInLuna($luna, $an);
            for ($i = 1; $i <= $nrZile; $i++) {
                if ($i == date('d') and $luna == date('n') and $an == date('Y')) {
                    ?>
                    <th><?php echo 'Azi' ?></th><?php
                } else {
                    ?>
                    <th><?php echo $i; ?></th><?php
                }
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $j = 1;
        $nrAparate = 0;
        foreach ($rezultat as $locatieArray) {
            $totalLocatie = 0;
            $locatie = reset($locatieArray);
            $index = key($locatieArray);
            $aparate = $db->getCastigAparate($an, $luna, $locatie->idlocatie);
            $k = 1;
        foreach ($aparate as $aparat) {
            $infoAparat = reset($aparat);
            ?>
            <tr data-luna="<?php echo $luna ?>" data-an="<?php echo $an ?>"
                data-idAparat="<?php echo $infoAparat->idAparat ?>" class="<?php if(($k <= $index) AND ($j % 2 == 0)){ echo 'greyedOut';} ?>">
                <?php
                if ($k == 1) {
                    ?>
                    <td rowspan='<?php echo $index ?>'
                        style="vertical-align: middle;"><?php echo $j; ?></td>
                    <td rowspan='<?php echo $index ?>'
                        style="vertical-align: middle;"><?php echo $locatie->denumire . ' ' . $index ?>
                    Aparate</td><?php

                }
                ?>
                <td>
                    <?php
                    echo $infoAparat->seria;
                    $nrAparate++;
                    ?></td>
                <?php
                $totalAparat = 0;
                for ($i = 1; $i <= $nrZile; $i++) {
                    ?>
                    <td class="<?php
                    $data = strtotime($an . '-' . $luna . '-' . $i);
                    echo(($data > strtotime(date('Y-m-d'))) ? 'blocat' : ''); ?>
                    <?php if(!array_key_exists($i,$aparat) OR ($aparat[$i]->nrPacWan = 0 AND $aparat[$i]->nrPac3g == 0)){ echo 'cTransmis';} ?>">
                        <?php echo array_key_exists($i,$aparat) ? $aparat[$i]->nrPac3g  : 0;?>
                    </td>
                    <?php
                    if($k == $index){
                        $j++;
                    }
                }
                ?>
            </tr>
        <?php
        $k++;
        }
        ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#total-<?php echo $locatie->idlocatie ?>').html(<?php echo round($totalLocatie,1) ?>);
                })
            </script>
            <?php
        }
        ?>
        <tr>
            <td colspan="<?php echo($page->zileInLuna($luna, $an) + 5); ?>"><h3>Total Castig Lunar : <span
                        id="grandTotal"></span></h3></td>
        </tr>
        </tbody>
    </table>
    <?php

} elseif ($post['tipPachete'] == 'castiguri') {
    echo $page->getWell('Tabela Castiguri');
    ?>
    <table class="table table-responsive table-bordered">
        <?php $rezultat = $db->getLocatii('', $idPers, $an, $luna);
        ?>
        <thead>
        <tr>
            <th>Nr. Crt</th>
            <th>Nume Locatie</th>
            <th class="aparat">Serie Aparat</th>
            <?php
            $nrZile = $page->zileInLuna($luna, $an);
            for ($i = 1; $i <= $nrZile; $i++) {
                if ($i == date('d') and $luna == date('n') and $an == date('Y')) {
                    ?>
                    <th><?php echo 'Azi' ?></th><?php
                } else {
                    ?>
                    <th><?php echo $i; ?></th><?php
                }
            }
            ?>
            <th class="aparat">T</th>
            <th>TL</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $j = 1;
        $nrAparate = 0;
        foreach ($rezultat as $locatieArray) {
            $totalLocatie = 0;
            $locatie = reset($locatieArray);
            $index = key($locatieArray);
            $aparate = $db->getCastigAparate($an, $luna, $locatie->idlocatie);
            $k = 1;
        foreach ($aparate as $aparat) {
            $infoAparat = reset($aparat);
            ?>
            <tr data-luna="<?php echo $luna ?>" data-an="<?php echo $an ?>"
                data-idAparat="<?php echo $infoAparat->idAparat ?>" class="<?php if(($k <= $index) AND ($j % 2 ==0)) { echo 'greyedOut';} ?>">
                <?php
                if ($k == 1) {
                    ?>
                    <td rowspan='<?php echo $index ?>'
                        style="vertical-align: middle;"><?php echo $j; ?></td>
                    <td rowspan='<?php echo $index ?>'
                        style="vertical-align: middle;"><?php echo $locatie->denumire . ' ' . $index ?>
                    Aparate</td><?php
                }
                ?>
                <td class="aparat">
                    <?php
                    echo $infoAparat->seria;
                    $nrAparate++;
                    ?></td>
                <?php
                $totalAparat = 0;
                for ($i = 1; $i <= $nrZile; $i++) {
                    ?>
                    <td class="<?php
                    $data = strtotime($an . '-' . $luna . '-' . $i);
                    echo(($data > strtotime(date('Y-m-d'))) ? 'blocat' : '');
                    ?> <?php echo(!array_key_exists($i, $aparat) ? 'nuTransmis' : ''); ?>">
                        <?php
                        if (array_key_exists($i, $aparat)) {
                            $ziIntreg = intval($aparat[$i]->castig/100);
                            $ziFractie = round(($aparat[$i]->castig - floor($aparat[$i]->castig)),1) * 10;
                            echo $ziIntreg.",<span style='font-size : 0.75em;'>$ziFractie</span>";
                            $totalAparat += ($aparat[$i]->castig);
                        } else {
                            echo 0;
                        }
                        ?>
                    </td>
                    <?php
                    $parteIntreaga = intval($totalAparat/100);
                    $parteFractionara = $totalAparat - floor($totalAparat);
                }
                ?>
                <td style="text-align: center;" class="aparat">
                    <span style="display:inline-block; float:left;"><?php echo $parteIntreaga ?></span>
                    <span style="display:inline-block; text-align: center">,</span>
                            <span
                                style="display: inline-block; float: right;"><?php echo round($parteFractionara, 1) * 10; ?></span>
                    <?php
                    $totalLocatie += $totalAparat;
                    ?></td>
                <?php if ($k == 1) { ?>
                    <td rowspan="<?php echo $index ?>" style="vertical-align: middle;text-align: right"
                        id="total-<?php echo $locatie->idlocatie ?>"></td>
                <?php }elseif($k== $index){
                    $j++;
                } ?>
            </tr>
        <?php
        $k++;
        }
        ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    <?php $totalLocatie /=100; ?>
                    $('#total-<?php echo $locatie->idlocatie ?>').html(<?php echo round($totalLocatie,1) ?>);
                })
            </script>
            <?php
        }
        ?>
        <tr>
            <td colspan="<?php echo($page->zileInLuna($luna, $an) + 5); ?>"><h3>Total Castig Lunar : <span
                        id="grandTotal"></span></h3></td>
        </tr>
        </tbody>
    </table>
    <?php
} elseif($post['tipPachete'] == 'castiguriLocatie') {  ?>
    <table class="table table-responsive table-bordered">
        <?php $rezultat = $db->getCastigPeZileLocatii($an,$luna,$idPers);
        ?>
        <thead>
        <tr>
            <th>Nr. Crt</th>
            <th>Nume Locatie</th>
            <th class="aparat hiden">Serie Aparat</th>
            <?php
            $nrZile = $page->zileInLuna($luna, $an);
            for ($i = 1; $i <= $nrZile; $i++) {
                if ($i == date('d') and $luna == date('n') and $an == date('Y')) {
                    ?>
                    <th><?php echo 'Azi' ?></th><?php
                } else {
                    ?>
                    <th><?php echo $i; ?></th><?php
                }
            }
            ?>
            <th class="aparat hiden">T</th>
            <th>TL</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $j = 1;
        $nrAparate = 0;

        foreach ($rezultat as $locatieArray) {
            $locatie = reset($locatieArray);?>
            <tr>
                <td><?php echo $j; $j++; ?></td>
                <td><?php echo $locatie->denumire;  ?></td>
                <?php $suma = 0;
                for($i=1;$i<=$page->zileInLuna($luna,$an);$i++){ ?>
                    <td><?php if(array_key_exists($i,$locatieArray)) {
                            $suma += $locatieArray[$i]->castig;
                            echo round(($locatieArray[$i]->castig/100),1);
                        }else{
                            echo '0';
                        } ?></td>
                <?php } ?>
                <td><?php
                    $parteIntreaga = intval($suma);
                    $parteFractionara = $suma - floor($suma);
                    echo $parteIntreaga . ",<span style='font-size : 0.75em;'>{$parteFractionara}</span>";?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php }else{
    echo $page->printDialog('danger', 'Nu exista acest tip de pachet');
}