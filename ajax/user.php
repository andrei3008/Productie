<?php
require_once('../classes/SessionClass.php');
require_once '../includes/dbFull.php';
$session = new SessionClass();
if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
    header('location:index.php');
}
?>

    <div class="panel-heading"><?php $pers = $db->getResponsabilPersonalInfo($_POST['om']);
        echo $pers->nick; ?></div>
    <div class="panel-body">
        <form class="inline-form" method="POST">
            <filelist>
                <label for="nick">Pseudonim : </label>
                <input type="text" name="nick" value="<?php echo $pers->nick ?>" class="form-control"/>
            </filelist>
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
