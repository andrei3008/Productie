<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
require_once('classes/FileClass.php');
$appSettings = $session = new SessionClass();
$page = new PageClass();
$file = new FileClass('text/contracte.txt');
$post = $db->sanitizePost($_POST);
$get = $db->sanitizePost($_GET);
if(isset($get['linia'])){
    $file->deleteLine($get['linia']);
    header('Location: '.$_SERVER['PHP_SELF']);
}
if (isset($post['submit'])) {
    $linieFisier = '';
    foreach ($post as $key => $value) {
        $linieFisier .= $value . ';';
    }
    $linieFisier .= "\n";
    $file->writeToFile($linieFisier);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contracte</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<?php require_once('includes/menu.php'); ?>
<div class="col-md-12">
    <div class="panel panel-primary">
        <button id="adaugaContract" class="btn btn-sm btn-primary">Adauaga Contract</button>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#adaugaContract').click(function (event) {
                    event.preventDefault();
                    $('#formContract').toggle();
                });
            });
        </script>
        <form method="POST" id="formContract" style="display: none;">
            <input type="hidden" value="<?php echo $file->getNumberOfLastRow()+1; ?>" name="nrContracte"/>
            <fieldset>
                <label for="adresa">Adresa</label>
                <input type="text" name="adresa" class="form-control"/>
            </fieldset>
            <fieldset>
                <label for="senmatar">Cine a semnat contractul</label>
                <input type="text" name="semnatar" class="form-control">
            </fieldset>
            <fieldset>
                <label for="data">Data</label>
                <input type="text" name="data" class="form-control"/>
            </fieldset>
            <fieldset>
                <label for="regiune">Regiune</label>
                <input type="text" name="regiune" class="form-control"/>
            </fieldset>
            <fieldset>
                <button type="submit" name="submit" value="1" class="btn btn-sm btn-primary">Salveaza</button>
            </fieldset>
        </form>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th>Nr. Crt</th>
                    <th>Adresa</th>
                    <th>Cine a semnat</th>
                    <th>Data</th>
                    <th>Regiune</th>
                    <th>Actiuni</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $linii = $file->getText();
                foreach ($linii as $linie) {
                    if ($linie != '') {
                        $coloane = explode(';', $linie);
                        ?>
                        <tr>
                            <td><?php echo $i;
                                $i++; ?></td>
                            <td><?php echo $coloane[1]; ?></td>
                            <td><?php echo $coloane[2]; ?></td>
                            <td><?php echo $coloane[3]; ?></td>
                            <td><?php echo $coloane[4]; ?></td>
                            <td>
                                <a href="?linia=<?php echo $coloane[0]; ?>" class="btn btn-sm btn-primary">Sterge
                                </a>
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
