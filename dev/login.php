<!DOCTYPE>
<?php
session_regenerate_id(true);
session_start();
session_destroy();
require_once('includes/dbConnect.php');
if (isset($_SESSION['operator']) AND $_SESSION['operator'] == 'r') {
    header('location:main.php');
}
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
if(!isset($_SESSION['id_vizita'])){
    $_SESSION['id_vizita'] = uniqid();
}
$logger = new Logger('logs/logs'.date('Y-m-d').'.txt');
$logger->writeToFile('Vizita de la ip '.$ip, 'VISIT',  $_SESSION['id_vizita']);
$con->select_db('operatori');
if (isset($_POST['go'])) {
    $invalid_characters = array("$", "%", "#", "<", ">", "|");
    $username = str_replace($invalid_characters, "", $_POST['user']);
    if (($_POST['pass'] == 'ReLo2015' AND $username == "red") OR ( $_POST['pass'] == 'K20152712WATS' AND $username) == 'KMRLIBWATS') {
        $logger->writeToFile('Utilizatorul '.$username. ' s-a logat pe aplicatie', 'LOGIN',  $_SESSION['id_vizita']);
        $_SESSION['username_redlong'] = $username;
        $_SESSION['operator'] = '2';
        $_SESSION['com_name'] = 'Red Long';
        $_SESSION['destroy_at_close'] = 1;
        header('location:main.php');
    } else {
        $logger->writeToFile('Utilizatorul '.$username. ' nu a reusit sa se logheze pe aplicatie', 'ERROR',  $_SESSION['id_vizita']);
        $rezultat = "Username sau parola gresita";
    }
}
?>
<html>
    <head>
        <link rel = "stylesheet" type = "text/css" href = "css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/loginStyle.css"/>
        <script src="http://mymaplist.com/js/vendor/TweenLite.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row vertical-offset-100">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">                                
                            <div class="row-fluid user-row">
                                <img src="http://s11.postimg.org/7kzgji28v/logo_sm_2_mr_1.png" class="img-responsive" alt="Conxole Admin"/>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form accept-charset="UTF-8" role="form" class="form-signin" method="POST">
                                <fieldset>
                                    <label class="panel-login">
                                        <div class="login_result"></div>
                                    </label>
                                    <input class="form-control" placeholder="Username" id="username" name="user" type="text">
                                    <input class="form-control" placeholder="Password" id="password" name="pass" type="password">
                                    <br></br>
                                    <input class="btn btn-lg btn-success btn-block" type="submit" id="login" name="go" value="Login »">
                                </fieldset>
                            </form>
                            <div>
                                <p class="text-center"><strong>Numai IP Minister</strong></p>
                                <p class="text-center">IP-ul dumneavoastra este : <strong> 
                                        <?php
                                        echo $ip;
                                        ?>
                                    </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>