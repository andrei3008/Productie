<?php

require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
$appSettings = $session = new SessionClass();
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$page = new PageClass();
if (isset($_POST['submit'])) {
    $post = $db->sanitizePost($_POST);
    if ($post['idUtilizator'] == 'null') {
        $rezultat = $db->insertUser($post);
        if (is_bool($rezultat)) {
            echo $page->printDialog('success', 'Utilizator introdus cu success!');
        } else {
            echo $page->printDialog('danger', $rezultat);
        }
    }else{ 
        $rezult = $db->updateUser($post);
        if(is_bool($rezult)){
            if($rezult==TRUE)
                echo $page->printDialog('success', 'Utilizatorul '.$post['nume'].' a fost editat cu success!');

        }else{
            echo $page->printDialog('danger', $rezult);
        }
    }
}
if (isset($_GET['id'])) {
    $get = $db->sanitizePost($_GET);
    if ($db->deleteUser($get['id'])) {
        echo $page->printDialog('success', 'Userul a fost sters cu success!');
    }
}
?>
<!DOCTYOE html>
<html>
    <head>
        <title>Users</title>
<?php require_once('includes/header.php'); ?>
    </head>
    <body>
<?php require_once('includes/menu.php'); ?>

        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">Utilizatori Existenti</div>
                <div class="panel-body">
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>Nr.</th>
                                <th>Nume Prenume</th>
                                <th>Nick</th>
                                <th>Utilizator</th>
                                <th>Parola</th>
                                <th>Actiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $personal = $db->getUsers();
                            foreach ($personal as $user) {
                                ?>
                                <tr>
                                    <td><?php
                                        echo $i;
                                        $i++;
                                        ?></td>
                                    <td><?php echo $user->nume . ' ' . $user->prenume; ?></td>
                                    <td><?php echo $user->nick; ?></td>
                                    <td><?php echo $user->user; ?></td>
                                    <td><?php echo $user->pass; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary edit" data-id="<?php echo $user->idpers; ?>" data-nume="<?php echo $user->nume; ?>"
                                           data-nick="<?php echo $user->nick ?>" data-user="<?php echo $user->user; ?>" data-pass="<?php echo $user->pass; ?>">Editeaza</a>
                                        <a href="?id=<?php echo $user->idpers ?>" class="btn btn-primary">Sterge</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3 fixed">
            <div class="panel panel-primary">
                <div class="panel-heading">Adauga / Editeaza Utilizator</div>
                <div class="panel-body">
                    <form method="POST">
                        <input type="hidden" name="idUtilizator" value="null" id="idUtilizator"/>
                        <fieldset>
                            <label>Nume</label>
                            <input class="form-control" type="text" name="nume" placeholder="Nume" id="numeUtilizator" required="required"/>
                        </fieldset>
                        <fieldset>
                            <label>Nick</label>
                            <input class="form-control" type="text" name="nick" placeholder="Nick" id="nickUtilizator" required="required"/>
                        </fieldset>
                        <fieldset>
                            <label>Username</label>
                            <input type="text" name="user" placeholder="Username" class="form-control" id="usernameUtilizator" required="required"/>
                        </fieldset>
                        <fieldset>
                            <label>Parola</label>
                            <input class="form-control" type="text" name="parola" placeholder="Parola" id="passwordUtilizator" required="required"/>
                        </fieldset>
                        <fieldset>
                            <button name="submit" class="btn btn-primaty">Salveaza User</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.edit').click(function (event) {
                    event.preventDefault();
                    var nume = $(this).attr('data-nume');
                    var nick = $(this).attr('data-nick');
                    var user = $(this).attr('data-user');
                    var pass = $(this).attr('data-pass');
                    var id = $(this).attr('data-id');
                    $('#idUtilizator').val(id);
                    $('#numeUtilizator').val(nume);
                    $('#nickUtilizator').val(nick);
                    $('#usernameUtilizator').val(user);
                    $('#passwordUtilizator').val(pass);
                });
            });
            setTimeout(function(){
                $('.alert').hide(200);
            },3000);
            $(window).scroll(function () {
                $(".fixed").css("top", $(window).scrollTop() + "px");
            });
        </script>
    </body>
</html>