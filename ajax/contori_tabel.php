<?php
	require_once "../autoloader.php";
	error_reporting(0);
    require_once('../includes/class.db.php');
    require_once "../includes/class.databFull.php";
    require_once('../classes/PageClass.php');
    require_once('../classes/SessionClass.php');
    $session =  $appSettings = new SessionClass();
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $page = new PageClass();
    $luna = intval($_POST['luna']);
    $an = intval($_POST['an']);
    $idAparat = intval($_POST['idAparat']);

    $aparat = $databFull->getAparatInfo($idAparat);
    $indexi = $databFull->getContoriAparat($idAparat, $an, $luna);
	$nrZile = date('t');
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
<table class="table table-bordered">
	<thead>
	    <tr>
	        <th colspan='9'>
	            <fieldset style="width: 50%; display: inline-block">
	                <select name="an" id="an" class="form-control">
	                    <option value="<?php echo $an ?>">
	                        <?php echo $an ?>
	                    </option>
	                    <?php
	                for ($z = 2015; $z < 2020; $z++) {
	                    if ($z != $an) {
	                        ?>
	                        <option value="<?php echo $z ?>">
	                            <?php echo $z; ?>
	                        </option>
	                        <?php
	                    }
	                }
	                ?>
	                </select>
	            </fieldset>
	            <fieldset style="width: 49%; display: inline-block">
	                <select name="luna" id="luna" class="form-control">
	                    <option value="<?php echo $luna ?>">
	                        <?php echo $page->getLuna($luna) ?>
	                    </option>
	                    <?php
	                for ($i = 1; $i < 13; $i++) {
	                    if ($i != $_GET['luna']) {
	                        ?>
	                        <option value="<?php echo $i; ?>">
	                            <?php echo $page->getLuna($i) ?>
	                        </option>
	                        <?php
	                        }
	                    }
	                    ?>
	                </select>
	            </fieldset>
	        </th>
	        <th colspan='7' style="text-align: center;"> </th>
	        <th colspan='2' style="text-align: center;">
	             <div id ="btn-contori" class="btn-group btn-print-group-contori">
	                <button type="button" class="btn btn-primary btn-main dropdown-toggle btn-print-current" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> PRINT <span class="caret"></span> </button>
	                <ul class="dropdown-menu">
	                    <li><a href="#" data-ext="pdf">PDF</a></li>
	                    <li><a href="#" data-ext="xls">.xls</a></li>
	                </ul>
	            </div>
	            
	        </th>
	        <th colspan='2' style="text-align: center;">
	          <button type="reset" name="reset" id="reset" class="btn btn-main btn-primary">RESET</button>
	        </th>
	    </tr>
	    <tr>
	        <th colspan="20" style="text-align: center;"> <span style=" width: 50%;">
	            Responsabil :<strong><?php echo $aparat->nick; ?></strong> |
	            Locatia : <strong><?php echo $aparat->denumire; ?></strong> |
	            Seria : <strong><?php echo $aparat->seria; ?></strong>
	        </span> </th>
	    </tr>
		<tr>
			<th colspan="2" rowspan="2" style="text-align: center;"></td>
			<th colspan="8" style="text-align: center;">Mecanic</td>
			<th colspan="8" style="text-align: center;">Electronic</td>
			<th colspan="2" rowspan="2" style="text-align: center;">SAS General</td>
		</tr>
		<tr>
			<th colspan="3" style="text-align: center;">Citire</td>
			<th colspan="3" style="text-align: center;">Reparatii</td>
			<th colspan="2" style="text-align: center;">Error</td>
			<th colspan="3" style="text-align: center;">Citire</td>
			<th colspan="3" style="text-align: center;">Reparatii</td>
			<th colspan="2" style="text-align: center;">Error</td>
		</tr>
	    <tr>
	        <th colspan="2" style="text-align: center;">Zi</th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th style="text-align: center;">Zilnic</th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th></th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th style="text-align: center;">Zilnic</th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th></th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	        <th style="text-align: center;">In</th>
	        <th style="text-align: center;">Out</th>
	    </tr>
	</thead>
	<tbody>
	          
<?php
	    $indexInStandard = $precedent[$key]->idxInM;
	    $indexOutStandard = $precedent[$key]->idxOutM;

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
				$indexinM = (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxInM : '-';
				$indexOutM = (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxOutM : '-';
				$indexInE = (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxInE : '-';
				$indexOutE = (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxOutE : '-';

				$idmec = $indexZile[$zi]->idmec;
				$idel = $indexZile[$zi]->idel;
?>
	            <tr class='deblocat'>
	                <td style="text-align: center;">
	                    <?php echo $i; ?>
	                </td>
	                <td style="text-align: center;">
<?php
						$zi_curenta = $an . '-' . $luna . '-' . $i;
	                    $timp = strtotime($an . '-' . $luna . '-' . $i);
	                    $ziua = date('w', $timp);
	                    echo $page->getLiteraZilei($ziua);
?>
	                </td>
					
	                <td style="text-align: center; width: 77px;" class='editable' id="idxInM_<?php echo $zi;?>" data-val="<?php echo $indexinM; ?>" data-idtabel="<?php echo $idmec;?>">
	                    <?php echo $indexinM; ?>
	                </td>
	                <td style="text-align: center; width: 77px;" class='editable' id="idxOutM_<?php echo $zi;?>" data-val="<?php echo $indexOutM; ?>" data-idtabel="<?php echo $idmec;?>">
	                    <?php echo $indexOutM; ?>
	                </td>
	                <td style="text-align: center;" id="cashM_<?php echo $zi;?>">
	                    <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->cashInM.' / '.$indexZile[$zi]->cashOutM.' / '.$indexZile[$zi]->castigM : '-'; ?>
	                </td>
					
	                <td style="text-align: center;"> - </td>
	                <td style="text-align: center;"> - </td>
	                <td style="text-align: center;"> - </td>
					
	                <td style="text-align: center; width: 77px"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->indexInM : '-'; ?> </td>
	                <td style="text-align: center; width: 77px;"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->indexOutM : '-'; ?> </td>
	                
					<td style="text-align: center; width: 77px;" class='editable' id="idxInE_<?php echo $zi;?>"  data-val="<?php echo $indexInE; ?>" data-idtabel="<?php echo $idel;?>">
	                    <?php echo $indexInE; ?>
	                </td>
	                <td style="text-align: center; width: 77px;" class='editable' id="idxOutE_<?php echo $zi;?>" data-val="<?php echo $indexOutE; ?>" data-idtabel="<?php echo $idel;?>">
	                    <?php echo $indexOutE; ?>
	                </td>
					
	                <td style="text-align: center;" id="cashE_<?php echo $zi;?>">
	                    <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->cashInE.' / '.$indexZile[$zi]->cashOutE.' / '.$indexZile[$zi]->castigE : '-'; ?>
	                </td>
					
	                <td style="text-align: center;"> - </td>
	                <td style="text-align: center;"> - </td>
	                <td style="text-align: center;"> - </td>
					
	                <td style="text-align: center; width: 77px;"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->indexInE : '-'; ?> </td>
	                <td style="text-align: center; width: 77px;"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->indexOutE : '-'; ?> </td>
					
	                <td style="text-align: center; width: 77px;"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxineSUM + $indexZile[$zi]->idxInE : '-'; ?> </td>
	                <td style="text-align: center; width: 77px;"> <?php echo (key_exists($zi, $indexZile)) ? $indexZile[$zi]->idxouteSUM + $indexZile[$zi]->idxOutE: '-'; ?> </td>
	            </tr>
<?php
	        } else {
?>
                <tr class="blocat">
                    <td>
                        <?php echo $i; ?>
                    </td>
                    <td>
                        <?php
	                    $timp = strtotime($an . '-' . $luna . '-' . $i);
	                    $ziua = date('w', $timp);
	                    echo $page->getLiteraZilei($ziua);
                    ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
<div class="loading"><img src="../css/AjaxLoader.gif" /></div>
<style type="text/css">
	.loading {
	    position: absolute;
	    width: 100%;
	    height: 100%;
	    display: none;
	    top: 0;
	    right: 0;
	    bottom: 0;
	    left: 0;
	    z-index: 1050;
	    background-color: #000;
	    opacity: 0.5;
	    filter: alpha(opacity=50);
	}
	.loading img {
	    width: 50px;
	    position: absolute;
	    top: 0;
	    right: 0;
	    bottom: 0;
	    left: 0;
	    margin: auto;
	}
</style>