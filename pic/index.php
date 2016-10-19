<?php
require_once '../includes/dbFull.php';
error_reporting(0);
$db = new dbFull('localhost', 'shorek', 'BudsSql7', null);
if (isset($_GET['idAparat'])) {
    $get = $db->sanitizePost($_GET);
    $id = $get['idAparat'];
} else {
    $id = null;
}
if (isset($_GET['macAparat'])) {
    $get = $db->sanitizePost($_GET);
    $mac = $get['macAparat'];
} else {
    $mac = null;
}
if (isset($_GET['serieAparat'])) {
    $get = $db->sanitizePost($_GET);
    $seria2 = $get['serieAparat'];
} else {
    $seria2 = null;
}

?>
<!doctype>
<html>
    <head>
        <?php require_once 'includes/header.php'; ?>
        <script type='text/javascript' src='pic.js'></script>
        
    </head>
    <body>
    <?php
        if (isset($_POST)) {
            $post = $db->sanitizePost($_POST);
        }
        if (isset($post['submit']) AND $post['submit'] == "Sterge Exceptiile") {
            echo "Se sterge";

            $db->deleteErors($id);
            header('Refresh:0');
        }
        $erori = $db->getErori($id);
        $errori_in = $errori_out = $errori_in_e = $errori_out_e = 0;
        foreach ($erori as $obj) {
            if (strpos($obj->exceptia,'In M')!== FALSE) {
                $errori_in++;
            }
            if (strpos($obj->exceptia,'Out M')!== FALSE) {
                $errori_out++;
            }
            if (strpos($obj->exceptia,'In E')!== FALSE) {
                $errori_in_e++;
            }
            if (strpos($obj->exceptia,'Out E')!== FALSE) {
                $errori_out_e++;
            }
        }
        $class_buttons_out_m = $class_buttons_in_m = $class_buttons_in_e =  $class_buttons_out_e = 'btn-disabled';
        $attr_button_out_m = $attr_button_in_m = $attr_button_in_e = $attr_button_out_e = 'disabled="disabled"';
        // if ($errori_out != 0) {
        //     $class_buttons_out_m = 'mergeinout_mecanic';
        //     $attr_button_out_m = '';
        // } 
        // if ($errori_in != 0) {
        //     $class_buttons_in_m = 'mergeinout_mecanic';
        //     $attr_button_in_m = '';
        // } 
        // if ($errori_out_e != 0) {
        //     $class_buttons_out_e = 'mergeinout_electronic';
        //     $attr_button_out_e = '';
        // } 
        // if ($errori_in_e != 0) {
        //     $class_buttons_in_e = 'mergeinout_electronic';
        //     $attr_button_in_e = '';
        // } 
        // if (count($erori) > 0) {
        // 	$class_buttons_in_e =  $class_buttons_out_e = 'mergeinout_electronic'; 
       	// 	$attr_button_in_e =  $attr_button_out_e = ''; 
        // }
        
    ?>
        <div class="container-fluid">
		<div class="col-md-12">
		<div style="font-size: 130%; margin-bottom: 10px;">ID: <?php echo $id;?> Seria: <?php echo $seria2;?> Mac: <?php echo $mac;?></div>
		</div>
            <div class="col-md-12">
                <form method="POST">
                    <fieldset>
					<div class="row">
        <div class="col-sm-4">
						<input type="submit" name="submit" value="Sterge Exceptiile" class="btn btn-lg btn-primary"/>
		</div>
        <div class="col-sm-4">
						<button class="<?php echo $class_buttons_in_m;?> btn btn-lg btn-info" data-type="IN" <?php echo $attr_button_in_m;?>> 
                        	Suprascrie IN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M
                        </button>
                        <button class="<?php echo $class_buttons_out_m;?> btn btn-lg btn-info" data-type="OUT" <?php echo $attr_button_out_m;?>> Suprascrie OUT M</button>
		</div>
        <div class="col-sm-4">
						<button class="<?php echo $class_buttons_in_e;?> btn btn-lg btn-info" data-type="IN" <?php echo $attr_button_in_e;?>> Suprascrie IN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E</button>
                        <button class="<?php echo $class_buttons_out_e;?> btn btn-lg btn-info" data-type="OUT" <?php echo $attr_button_out_e;?>> Suprascrie OUT E</button>
		</div>
    </div>
                        
                        

                        
                        <input type='hidden' value="<?php echo $_GET['idAparat'];?>" id="idAparat" />
                    </fieldset>
                </form>
            </div>
            <table class="table table-bordered col-md-12">
                <thead >
                    <tr>
                        <th rowspan="3">Id Pachet</th>
                        <th rowspan="3" width="350px">Exceptia</th>
                        <th rowspan="3">T</th>
                        <th colspan="6" style='text-align: center'>Manual</th>
                        <th colspan="6" style='text-align: center'>Electronic</th>
                        <th rowspan="3">Data Server</th>
                    </tr>
                    <tr>
						<td colspan="3" style='text-align: center'>IN</td>
						<td colspan="3" style='text-align: center'>OUT</td>
						<td colspan="3" style='text-align: center'>IN</td>
						<td colspan="3" style='text-align: center'>OUT</td>
                    </tr>
						<tr>
						<td>Pachet</td>
						<td>Baza</td>
						<td>Diferenta</td>
						<td>Pachet</td>
						<td>Baza</td>
						<td>Diferenta</td>						
						<td>Pachet</td>
						<td>Baza</td>
						<td>Diferenta</td>
						<td>Pachet</td>
						<td>Baza</td>
						<td>Diferenta</td>
					</tr>
                </thead>
                <tbody>
                    <?php
                    	
                    	foreach ($erori as $obj) {
                    ?>
	                        <tr>
	                            <td><?php echo $obj->idpachet; ?></td>
	                            <td width="350px"><?php echo $obj->exceptia; ?></td>
	                            <td><a href="<?php echo 'http://' . $obj->ip . ':' . (60 + $obj->pozitieLocatie); ?>">T</a></td>
	                            <td><?php echo $obj->indexInM; ?></td>
	                            <td><?php echo $obj->idxInMB; ?></td>
                                <td><?php echo $obj->indexInM-$obj->idxInMB; ?></td>
                                <td><?php echo $obj->indexOutM; ?></td>
								<td><?php echo $obj->idxOutMB; ?></td>
								<td><?php echo $obj->indexOutM-$obj->idxOutMB; ?></td>
								<td><?php echo $obj->indexInE; ?></td>
								<td><?php echo $obj->idxInEB; ?></td>
								<td><?php echo $obj->indexInE-$obj->idxInEB; ?></td>
								<td><?php echo $obj->indexOutE; ?></td>
								<td><?php echo $obj->idxOutEB; ?></td>
								<td><?php echo $obj->indexOutE-$obj->idxOutEB; ?></td>
	                            <td><?php
	                                $time = strtotime($obj->dataServer);

	                                $newformat = date('d-M-y H:i:s', $time);

	                                echo $newformat;
	                                ;
	                                ?>
	                            </td>
	                        </tr>
                    <?php
                    	}
                    ?>
                </tbody>
            </table>
        </div>
        <style type="text/css">
        	.btn.btn-lg:not(input) {
        		width: 135px;
				white-space: normal;
				font-size: 14px;
				padding: 3px 13px;
        	}
        </style>
    </body>
</html>