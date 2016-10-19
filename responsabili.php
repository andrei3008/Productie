<?php

require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once ('classes/PageClass.php');
$appSettings = $session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
$page = new PageClass();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Editare Responsabili</title>
    <?php require_once('includes/header.php') ?>
</head>
<body>
<?php require_once('includes/menu.php'); ?>
<div class="container-fluid">
    <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading">Lista responsabili</div>
            <div class="panel-body">
                <ul class="list-group">
                    <?php
                    $responsabili = $db->getResponsabiliInfo();
                    foreach ($responsabili as $responsabil) {
                        ?>
                        <li class="list-group-item"
                            data-id="<?php echo $responsabil->idpers ?>"><?php echo $responsabil->nick; ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-9" >
        <?php
        if(isset($_POST) AND !empty($_POST['submit'])) {
            if (!is_bool($db->updateResponsabil($_SESSION['username'],$_POST))){
                echo $page->printDialog('danger', $db->updateResponsabil($_SESSION['username'],$_POST));
            }else{
                echo $page->printDialog('success',"Userul <strong>".$_POST['nick']."</strong> a fost editat cu success!!");
            }
        }
        ?>
        <div class="panel panel-primary" id="infoPersonal">
            <?php
            $id = (isset($_POST['id'])) ? $_POST['id'] : 1;
            ?>
                    <div class="panel-heading"><?php $pers = $db->getResponsabilPersonalInfo($id);
                echo $pers->nick; ?></div>
            <div class="panel-body">
                <form class="inline-form" method="POST">
                    <fieldset>
                        <label for="nick">Pseudonim : </label>
                        <input type="text" name="nick" value="<?php echo $pers->nick ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="nume">Nume : </label>
                        <input type="text" name="nume" value="<?php echo $pers->nume; ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="prenume">Prenume</label>
                        <input type="text" name="prenume" value="<?php echo $pers->prenume; ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="cnp">CNP</label>
                        <input type="text" name="cnp" value="<?php echo $pers->cnp; ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="adresa">Adresa</label>
                        <input type="text" name="adresa" value="<?php echo $pers->adresa ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="telefon">Telefon</label>
                        <input type="text" name="telefon" value="<?php echo $pers->telefon; ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="email">Email : </label>
                        <input type="email" name="email" value="<?php echo $pers->email ?>" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="user">Nume utilizator : </label>
                        <input type="text" name="user" value="<?php echo $pers->user; ?>" class="form-control">
                    </fieldset>
                    <fieldset>
                        <label for="pass">Password : </label>
                        <input type="password" name="pass" class="form-control"/>
                    </fieldset>
                    <fieldset>
                        <label for="pass2">Re-type password : </label>
                        <input type="password" name="pass2" class="form-control"/>
                        <input type="hidden" name="id" value="<?php echo $pers->idpers; ?>"/>
                    </fieldset>
                    <fieldset>
                        <label for="submit"></label>
                        <input type="submit" name="submit" value="Editeaza Responsabil" class="btn btn-lg btn-success"/>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.list-group-item').click(function () {
                var om = $(this).attr('data-id');
                $.ajax({
                    type: "POST",
                    url: 'ajax/user.php',
                    data: {'om': om},
                    success: function (result) {
                        $('#infoPersonal').html(result);
                    }
                });
            });
        });
    </script>
</div>
</body>
</html>
