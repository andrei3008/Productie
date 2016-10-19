<?php
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>For Printing</title>
    <?php require_once('includes/header.php'); ?>
    <style>
        td{
            padding:0 !important;
        }
    </style>
</head>
</html>
<body>
<div class="container">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nr. Crt.</th>
            <th>Responsabil</th>
            <th>Locatie</th>
            <th>Serie Aparat</th>
            <th>Contor In</th>
            <th>Contor Out</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        $nick = $locatie = '';
        $randuri = $db->getAllResponsabiliAparate();
        foreach ($randuri as $rand) {
            if ($locatie != $rand->denumire) {
                ?>
                <tr>
                    <td colspan="6" class="delimiter"></td>
                </tr><?php
            }
            ?>
            <tr>
                <td><?php echo $i;
                    $i++; ?></td>
                <td><?php
                    if ($locatie != $rand->denumire) {
                        $nick = $rand->nick;
                        echo $rand->nick;
                    }
                    ?></td>
                <td><?php
                    if ($locatie != $rand->denumire) {
                        $locatie = $rand->denumire;
                        echo $rand->denumire;
                    } ?></td>
                <td><?php echo $rand->seria; ?></td>
                <td><?php echo $rand->lastIdxInM; ?></td>
                <td><?php echo $rand->lastIdxOutM; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
</body>
