<?php
require_once "autoloader.php";
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
require_once('classes/SessionClass.php');
$session =  $appSettings = new SessionClass();
$page = new PageClass();
$get = $db->sanitizePost($_GET);
$luna = $get['luna'];
$an = $get['an'];
$idAparat = $get['idAparat'];
$aparat = $db->getAparatInfo($get['idAparat']);
$nrZile = $page->zileInLuna($get['luna'], $get['an']);
$testing = $db->getIndexiCMAparat($idAparat, $an, $luna);
$verificare = [];
foreach ($testing as $test) {
    $elemente = explode(' ', $test->dtServer);
    $data = explode('-', $elemente[0]);
    $verificare[intval($data[1]) . '-' . intval($data[2])] = $test;
}
$first = array_slice($verificare, 0, 1, true);
$first = reset($first);
$second = array_slice($verificare, 1, 1, true);
$second = reset($second);
if (isset($_POST['submit'])) {
    $post = $db->sanitizePost($_POST);
    foreach ($post as $key => $value) {
        if ($key != 'an' AND $key != 'luna' AND $key != 'submit') {
            $bucati = explode('_', $key);
            /* Se verifica inceputul de luna! Daca este mai mic ca primul element sau este diferit de ce era deja in baza de date!  */
            if ($bucati[0] == 'inceput' AND $bucati[1] == 'in') {
                if ($post['inceput_in_' . $bucati[2]] != $first->idxInM OR $post['inceput_out_' . $bucati[2]] != $first->idxOutM) {
                    if (($second->idxInM >= $post['inceput_in_' . $bucati[2]]) AND ( $second->idxOutM >= $post['inceput_out_' . $bucati[2]])) {
                        $db->updateContoriById($bucati[2], $post['inceput_in_' . $bucati[2]], $post['inceput_out_' . $bucati[2]], $an, $luna, $_SESSION['userId']);
                        echo $page->printDialog('success', 'Inceputul lunii a fost editat cu success. Va rugam sa nu uitati sa editati sfarsitu lunii precedente. Va multumitm');
                        $idmec = $bucati[2];
                    } else {
                        echo $page->printDialog('danger', 'Inceputul de luna trebuie sa aibe cele mai mici valori!');
                        $idmec = $bucati[2];
                    }
                } else {
                    $idmec = $bucati[2];
                }
            }

            /* Se verifica resul zilelor pana pana la data de blocare in afara de pozitia 1  */
            if ($bucati[1] == 'in') {
                if (isset($post['index_in_' . $bucati[2]]) AND isset($post['index_out_' . $bucati[2]]) AND $bucati[0] != 'inceput') {
                    if (isset($post['index_in_' . ($bucati[2] - 1)])) {
                        if (($post['index_in_' . ($bucati[2] - 1)] <= $post['index_in_' . $bucati[2]]) AND ( $post['index_out_' . ($bucati[2] - 1)] <= $post['index_out_' . $bucati[2]])) {
                            $decizie = $db->verificaExistentaIndex($idAparat, $an, $luna, $bucati[2]);
                            if (!$decizie) {
                                if ($db->insertContori($idAparat, $aparat->idlocatie, $post['index_in_' . $bucati[2]], $post['index_out_' . $bucati[2]], $an, $luna, $bucati[2], $_SESSION['userId'])) {
                                    echo $page->printDialog('success', 'Indexi introdusi cu success pentru ziua ' . $bucati[2]);
                                } else {
                                    echo $page->printDialog('danger', 'Nu au putut fi introdusi indexi pentru ziua ' . $bucati[2]);
                                }
                            } else {
                                if (($verificare[$luna . '-' . $bucati[2]]->idxInM != $post['index_in_' . $bucati[2]]) OR ( $verificare[$luna . '-' . $bucati[2]]->idxOutM != $post['index_out_' . $bucati[2]])) {
                                    if ($db->updateContori($post['index_in_' . $bucati[2]], $post['index_out_' . $bucati[2]], $idAparat, $an, $bucati[2], $luna, $_SESSION['userId'])) {
                                        echo $page->printDialog('success', 'Indexi modificati cu success pentru ziua ' . $bucati[2]);
                                    }
                                }
                            }
                        } else {
                            echo $page->printDialog('danger', 'Indexii din ziua ' . $bucati[2] . ' sunt mai mari ca cei din ziua ' . ($bucati[2] + 1) . '!');
                        }
                    } elseif ($post['index_in_' . $bucati[2]] >= $post['inceput_in_' . $idmec] AND $post['index_out_' . $bucati[2]] >= $post['inceput_out_' . $idmec]) {
                        $decizie = $db->verificaExistentaIndex($idAparat, $an, $luna, $bucati[2]);
                        if (!$decizie) {
                            echo $bucati[2];
                            if ($db->insertContori($idAparat, $aparat->idlocatie, $post['index_in_' . $bucati[2]], $post['index_out_' . $bucati[2]], $an, $luna, $bucati[2], $_SESSION['userId'])) {
                                echo $page->printDialog('success', 'Indexi introdusi cu success pentru ziua ' . $bucati[2]);
                            } else {
                                echo $page->printDialog('danger', 'Nu au putut fi introdusi indexi pentru ziua ' . $bucati[2]);
                            }
                        } else {
                            if (($verificare[$luna . '-' . $bucati[2]]->idxInM >= $post['inceput_in_' . $idmec]) OR ( $verificare[$luna . '-' . $bucati[2]]->idxOutM >= $post['inceput_out_' . $idmec])) {
                                if ($db->updateContori($post['index_in_' . $bucati[2]], $post['index_out_' . $bucati[2]], $idAparat, $an, $bucati[2], $luna, $_SESSION['userId'])) {
                                    echo $page->printDialog('success', 'Indexi modificati cu success pentru ziua ' . $bucati[2]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $db->genereazaCastiguri($idAparat, $luna, $an, $aparat->idlocatie);
}

$indexi = $db->getIndexiCMAparat($idAparat, $an, $luna);
$precedent = [];
$indexZile = [];
$dataActivare = $dataBlocare = '';
foreach ($indexi as $index) {
    $data = explode(' ', $index->dtServer);
    $dataBlocare = strtotime($index->dtBlocare);
    $dataActivare = strtotime($index->dtActivare);
    $elemente = explode('-', $data[0]);
    $indexZile[$elemente[2]] = $index;
    if ($elemente[1] != $get['luna']) {
        $precedent[$elemente[2]] = $index;
    }
}
$dataActivare = strtotime($aparat->dtActivare);
$dataBlocare = strtotime($aparat->dtBlocare);
?>
<!DOCTYPE>
<html>
    <head>
        <title>Indexi pe zile</title>
        <?php require_once('includes/header.php'); ?>
    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div class="col-md-12">
            <form method="post">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan='9'>
                                <fieldset style="width: 50%; display: inline-block">
                                    <select name="an" id="an" class="form-control">
                                        <option value="<?php echo $an ?>"><?php echo $an ?></option>
                                        <?php
                                        for ($z = 2015; $z < 2020; $z++) {
                                            if ($z != $an) {
                                                ?>
                                                <option value="<?php echo $z ?>"><?php echo $z; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                                <fieldset style="width: 49%; display: inline-block">
                                    <select name="luna" id="luna" class="form-control">
                                        <option value="<?php echo $luna ?>"><?php echo $page->getLuna($luna) ?></option>
                                        <?php
                                        for ($i = 1; $i < 13; $i++) {
                                            if ($i != $_GET['luna']) {
                                                ?>
                                                <option
                                                    value="<?php echo $i; ?>"><?php echo $page->getLuna($i) ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>

                                    </select>
                                </fieldset>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="9" style="text-align: center;">
                                <span style=" width: 50%;">
                                    Responsabil :<?php echo $aparat->nick; ?></strong> |
                                    Locatia : <strong><?php echo $aparat->denumire; ?></strong> |
                                    Seria : <strong><?php echo $aparat->seria; ?></strong>
                                </span>
                            </th>
                        <tr>
                        <tr>
                            <th>Zi</th>
                            <th>Zi</th>
                            <th>Index In</th>
                            <th>Index Out</th>
                            <th>Cash In</th>
                            <th>Cash Out</th>
                            <th>Castig</th>
                            <th>Data Introducere</th>
                            <th>Introdus de</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($precedent as $key => $value) {
                            ?>
                            <tr>
                                <td><?php echo $key ?></td>
                                <td><?php
                                    $timp = strtotime($precedent[$key]->dtServer);
                                    $ziua = date('w', $timp);
                                    echo $page->getLiteraZilei($ziua);
                                    ?></td>
                                <td><input class="input-cell" type='text' name='inceput_in_<?php echo $precedent[$key]->idmec ?>' value="<?php echo $precedent[$key]->idxInM; ?>"/></td>
                                <td><input class="input-cell" type="text" name="inceput_out_<?php echo $precedent[$key]->idmec ?>" value="<?php echo $precedent[$key]->idxOutM; ?>"/></td>
                                <td><?php echo $precedent[$key]->cashIn; ?></td>
                                <td><?php echo $precedent[$key]->cashOut; ?></td>
                                <td><?php echo $precedent[$key]->castig; ?></td>
                                <td><?php echo $precedent[$key]->dtServer; ?></td>
                                <td><?php echo $precedent[$key]->nick ?></td>
                            </tr>
                            <?php
                            $indexInStandard = $precedent[$key]->idxInM;
                            $indexOutStandard = $precedent[$key]->idxOutM;
                        }
                        if (!isset($indexOutStandard)) {
                            $indexOutStandard = 0;
                        }
                        if (!isset($indexInStandard)) {
                            $indexInStandard = 0;
                        }
                        for ($i = 1; $i <= $nrZile; $i++) {
                            $zi = ($i < 10) ? '0' . $i : $i;
                            $data = strtotime($an . '-' . $luna . '-' . $zi);
                            iF (($dataActivare <= $data)) {
                                if (($dataBlocare >= $data OR $dataBlocare == strtotime('1000-01-01')) AND $data <= strtotime(date('Y-m-d'))) {
                                    if (key_exists($zi, $indexZile)) {
                                        $indexInStandard = $indexZile[$zi]->idxInM;
                                        $indexOutStandard = $indexZile[$zi]->idxOutM;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <?php
                                            $timp = strtotime($an . '-' . $luna . '-' . $i);
                                            $ziua = date('w', $timp);
                                            echo $page->getLiteraZilei($ziua);
                                            ?>
                                        </td>
                                        <td><input class="input-cell" type="text" name="index_in_<?php echo $i; ?>" value="<?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxInM : $indexInStandard; ?>"/></td>
                                        <td><input class="input-cell" type="text" name="index_out_<?php echo $i; ?>" value="<?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxOutM : $indexOutStandard; ?>"/></td>
                                        <td><?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->cashIn : '0' ?></td>
                                        <td><?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->cashOut : '0'; ?></td>
                                        <td><?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->castig : '0'; ?></td>
                                        <td><?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->dtServer : ''; ?></td>
                                        <td><?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->nick : 'neintrodus'; ?></td>
                                    </tr>
                                    <?php
                                } else {
                                    ?> 
                                    <tr class="blocat">
                                        <td><?php echo $i; ?></td>
                                        <td><?php
                                            $timp = strtotime($an . '-' . $luna . '-' . $i);
                                            $ziua = date('w', $timp);
                                            echo $page->getLiteraZilei($ziua);
                                            ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" name="submit" class="btn btn-main btn-primary">Salveaza Index</button>
            </form>
        </div>
        <script type="text/javascript">
            $(document).on('change', '#an', function () {
                var an = $('#an').val();
                var luna = $('#luna').val();
                var idAparat = <?php echo $get['idAparat']; ?>;
                window.location.href = '?idAparat=' + idAparat + '&luna=' + luna + '&an=' + an;
            });
            $(document).on('change', '#luna', function () {
                var an = $('#an').val();
                var luna = $('#luna').val();
                var idAparat = <?php echo $get['idAparat']; ?>;
                window.location.href = '?idAparat=' + idAparat + '&luna=' + luna + '&an=' + an;
            });
        </script>
    </body>
</html>