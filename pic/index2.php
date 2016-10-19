<?php
require_once '../includes/dbFull.php';
$db = new dbFull('localhost', 'shorek', 'BudsSql7', null);
if (isset($_GET['idAparat'])) {
    $get = $db->sanitizePost($_GET);
    $id = $get['idAparat'];
} else {
    $id = null;
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
        $errori_in = 0;
        $errori_out = 0;
        foreach ($erori as $obj) {
            if (strpos($obj->exceptia,'IN')!== FALSE) {
                $errori_in++;
            }
            if (strpos($obj->exceptia,'OUT')!== FALSE) {
                $errori_out++;
            }
        }
        echo 'Erori IN = '.$errori_in.'<br />';  
        echo 'Erori OUT = '.$errori_out.'<br />';
        if (count($erori) != 0) {
            $class_buttons = 'mergeinout';
            $attr_button = '';
        } else {
            $class_buttons = 'btn-disabled';
            $attr_button = 'disabled="disabled"';
        }
    ?>
        <div class="container-fluid">
            <div class="col-md-12">
                <form method="POST">
                    <fieldset>
                        <input type="submit" name="submit" value="Sterge Exceptiile" class="btn btn-lg btn-primary"/>
                        <button class="<?php echo $class_buttons;?> btn btn-lg btn-info" data-type="IN" <?php echo $attr_button;?>> Suprascrie IN </button>
                        <button class="<?php echo $class_buttons;?> btn btn-lg btn-info" data-type="OUT" <?php echo $attr_button;?>> Suprascrie OUT</button>
                        <input type='hidden' value="<?php echo $_GET['idAparat'];?>" id="idAparat" />
                    </fieldset>
                </form>
            </div>
            <table class="table table-bordered col-md-12">
                <thead >
                    <tr>
                        <th rowspan="2">Id Pachet</th>
                        <th rowspan="2">serieAparat</th>
                        <th rowspan="2" width="350px">Exceptia</th>
                        <th rowspan="2">T</th>
                        <th colspan="2" style='text-align: center'>Manual</th>
                        <th colspan="2" style='text-align: center'>Electronic</th>
                        <th rowspan="2">idAparat</th>
                        <th rowspan="2">idLoc</th>
                        <th rowspan="2">Soft</th>
                        <th rowspan="2">Data Server</th>
                    </tr>
                    <tr>
                        <th>IN Pachet</th>
                        <th>OUT Pachet</th>
                        <th>IN Pachet</th>
                        <th>OUT Pachet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    	
                    	// foreach ($erori as $obj) {
                    ?>
	                        <tr>
	                            <td><?php echo $obj->idpachet; ?></td>
	                            <td><?php echo $obj->serieAparat; ?></td>
	                            <td width="350px"><?php echo $obj->exceptia; ?></td>
	                            <td><a href="<?php echo 'http://' . $obj->ip . ':' . (60 + $obj->pozitieLocatie); ?>">T</a></td>
	                            <td><?php echo $obj->indexInM; ?></td>
	                            <td><?php echo $obj->indexOutM; ?></td>
                                <td><?php echo $obj->indexInE; ?></td>
                                <td><?php echo $obj->indexOutE; ?></td>
	                            <td><?php echo $obj->idAparat; ?></td>
	                            <td><?php echo $obj->idLocatie; ?></td>
	                            <td><?php echo $obj->verSoft; ?></td>
	                            <td><?php
	                                $time = strtotime($obj->dataServer);

	                                $newformat = date('d-M-y H:i:s', $time);

	                                echo $newformat;
	                                ;
	                                ?></td>
	                        </tr>
                    <?php
                    	// }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>