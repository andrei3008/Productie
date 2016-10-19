<!DOCTYPE>
<?php
session_start();
session_destroy();
require_once('includes/dbConnect.php');
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

$logger->writeToFile('Vizita de la ip '.$ip, 'VISIT', $_SESSION['id_vizita']);
$con->select_db('operatori');
if (isset($_POST['go'])) {
    $invalid_characters = array("$", "%", "#", "<", ">", "|");
    $username = str_replace($invalid_characters, "", $_POST['user']);
    if (($_POST['pass'] == 'Amper220' AND $username == "amp") OR ( $_POST['pass'] == 'S20152812LRMK' AND $username == 'STAWBILRMK')) {
        $logger->writeToFile('Utilizatorul '.$username. ' s-a logat pe aplicatie', 'LOGIN',$_SESSION['id_vizita']);
        $_SESSION['username_ampera'] = $username;
        $_SESSION['operator'] = '1';
        $_SESSION['com_name'] = 'Ampera Games SRL';
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
        <?php require_once('includes/header.php'); ?>
        <link rel="stylesheet" type="text/css" href="css/loginStyle.css"/>
        <script type="text/javascript" src="js/login.js"></script>
    </head>
    <body>
        <div class="container">

            <div class="row" id="pwd-container">
                <div class="col-md-4"></div>

                <div class="col-md-4">
                    <section class="login-form">
                        <form method="post" role="login">
                            <img src="css/amperaLogo.png" class="img-responsive" alt="" />

                            <input type="text" name="user" placeholder="User Name" required class="form-control input-lg" />

                            <input type="password" class="form-control input-lg" id="password" placeholder="Password" required="" name="pass" />
                            <?php
                            if (isset($result)) {
                                ?>
                                <ul class="error-list">
                                    <li class="red"><?php echo $rezultat; ?></li>
                                </ul>
                                <?php
                            }
                            ?>

                            <div class="pwstrength_viewport_progress"></div>


                            <button type="submit" name="go" class="btn btn-lg btn-primary btn-block">Logare</button>
                            <div>
                                <p><strong>Numai IP Minister</strong></p>
                                <p>IP-ul dumneavoastra este : <strong>
                                        <?php
                                        echo $ip;
                                        ?></strong></p>
                            </div>

                        </form>
                    </section>  
                </div>

                <div class="col-md-4">
                </div>


            </div> 

        </div>
    </body>
</html>