<?php
    require_once "../includes/_db.inc.php";
    require_once "../includes/class.db.php";
    require_once "../includes/class.databFull.php"; 
    require_once "../classes/Aparate.php";
    header('Content-Type: application/json');
    $err = 0;
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $aparate = new Aparate($databFull);
    $_post = $databFull->sanitize($_POST);

    if (isset($_post['type']) && ($_post['type'] == 'updatePozitie')) {
    	$out =  $aparate->updatePozitie(array('idAparat' => intval($_post['idAparat']), 'pozitieNoua' => intval($_post['pozitieNoua'])));
    	$mesaj = ($out != 0) ? 'Pozitie updatata cu succes!' : 'Pozitia nu s-a putut updata!';
    }

    $result = array(
    	'err' => $err,
    	'out' => $out,
    	'mesaj' => $mesaj
    );
    echo json_encode($result);
?>