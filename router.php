<?php
require_once "autoloader.php";


$session = new SessionClass();

$db = new dbFull(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
$database = new DataConnection();

$post = $db->sanitizePost($_POST);
$aparateMapper = new AparateMapper($database,$session);

if(isset($post['search'])){
    $search = strtoupper($post['search']);
    $aparat = new AparatEntity($database,$session);
    $locatie = new LocatiiEntity($database,$session);
    $aparat = $aparateMapper->getAparatBySerie($_POST['search']);
    if($aparat->getIdAparat() != NULL){
        $locatie->getLocatie($aparat->getIdLocatie());
        $session->setIdresp($locatie->getIdresp());
        $session->setOperator($locatie->getIdOperator());
        $session->setIdLocatie($locatie->getIdlocatie());
    }else{
        if($locatie->getLocatieByDenumire(strtoupper($post['search']))){
            $session->setIdresp($locatie->getIdresp());
            $session->setIdLocatie($locatie->getIdlocatie());
            $session->setOperator($locatie->getIdOperator());
        }

    }
}

if(isset($post['username'])){
    $session->setUsername($post['username']);
}

if(isset($post['operator'])){
    $session->setOperator($post['operator']);
}

if(isset($post['idLocatie'])){
    $session->setIdLocatie($post['idLocatie']);
}

if(isset($post['grad'])){
    $session->setGrad($post['grad']);
}

if(isset($post['userId'])){
    $session->setUserId($post['userId']);
}

if(isset($post['flag'])){
    $session->setFlag($post['flag']);
}

if(isset($post['idresp'])){
    $session->setIdresp($post['idresp']);
    $personalMapper = new PersonalMapper($database,$session);
    $session->setIdLocatie($personalMapper->getIdPrimaLocatieResponsabil($post['idresp']));
}

if(isset($post['an'])){
    $session->setAn($post['an']);
}

if(isset($post['luna'])){
    $session->setLuna($post['luna']);
    if ($post['luna'] != date('n')) {
        $_SESSION['changed-luna'] = time();
    } else {
        unset($_SESSION['changed-luna']);
    }
    
}

if(isset($post['zi'])){
    $session->setZi($post['zi']);
}

if(isset($post['order'])){
    $session->setOrder($post['order']);
}

if(isset($post['direction'])){
    $session->setDirection($post['direction']);
}

if(isset($post['zona']))
{
    echo $post['zona'];
    $session->setZona($post['zona']);
}
print_r($_SESSION);