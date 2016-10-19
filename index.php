<!DOCTYPE>
<?php
require_once "autoloader.php";
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
require_once('classes/PageClass.php');
require_once('classes/DataConnection.php');
require_once('classes/Mappers/PersonalMapper.php');
$page = new PageClass();
$db = new dbFull(DB_HOST, DB_USER, DB_PASS, null);
$database = new DataConnection();
if (isset($_SESSION['database'])) {
    header('location:main.php');
}
$session = new SessionClass();

$personalMapper = new PersonalMapper($database,$session);
unset($_SESSION['username']);
unset($_SESSION['operator']);

if (isset($_POST['infoPc'])) {
    $_SESSION['secret'] = $_POST['infoPc'];
}

if (isset($_POST['go'])) {
    $invalid_characters = array("$", "%", "#", "<", ">", "|");
    $username = str_replace($invalid_characters, "", $_POST['user']);
    $utilizator = $db->getUserByUserName($username);
    if (!is_bool($db->verifyUser($username, $_POST['pass']))) {
        $_SESSION['username'] = $username;
        $user_info = $db->verifyUser($username, $_POST['pass']);
        if (strpos($username, 'user') !== false) {
            $_SESSION['idresp'] = $db->getResponsabilId($user_info->idlocatie);
        } elseif ($user_info->idlocatie == NULL){
            $responsabil = $personalMapper->getPersonal($user_info->idpers);
            if($responsabil->getNumarLocatii() > 0){
                $_SESSION['idresp'] = $user_info->idpers;
            }else{
                $_SESSION['idresp'] = 1;
            }
        }else{
            $_SESSION['idresp'] = 1;
        }
        $_SESSION['grad'] = $user_info->grad;
        $_SESSION['flag'] = $user_info->resetPass;
        $_SESSION['userId'] = $user_info->idpers;
        $_SESSION['idLocatie'] = $user_info->idlocatie;
        $_SESSION['operator'] = (($username == 'adi' ? 1 : $db->getOperatorLocatie($user_info->idlocatie)));
        $_SESSION['com_name'] = 'Ampera Games SRL';
        if($user_info->grad == 5){
            header("location:mainLocatie.php");
        }elseif($user_info->grad == 3){
            header("location:mainResponsabil.php");
        }
        elseif($user_info->grad == 0)
        {
            header('location:main.php');
        }
    } else {
        $rezultat = "Username sau parola gresita";
    }
}
?>
<html>
<head>
    <?php require_once('includes/header.php'); ?>
    <title>Bine ati venit va rugam sa va logati!</title>
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
                    <img src="css/amperaLogo.png" class="img-responsive" alt=""/>

                    <input type="text" name="user" placeholder="User Name" required class="form-control input-lg"/>

                    <input type="password" class="form-control input-lg" id="password" placeholder="Password"
                           required="" name="pass"/>
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
                </form>
            </section>
        </div>

        <div class="col-md-4">
        </div>


    </div>

</div>
</body>
</html>