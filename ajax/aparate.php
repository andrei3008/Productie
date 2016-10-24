<?php
    require_once "../includes/_db.inc.php";
    require_once "../includes/class.db.php";
    require_once "../includes/class.databFull.php"; 
    require_once "../classes/Aparate.php";
    require_once "../classes/PageClass.php";
    header('Content-Type: application/json');
    $err = 0;
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $aparate = new Aparate($databFull);
    $page = new PageClass();
    $_post = $databFull->sanitize($_POST);

    if (isset($_post['type']) && ($_post['type'] == 'updatePozitie')) {
    	$out =  $aparate->updatePozitie(array('idAparat' => intval($_post['idAparat']), 'pozitieNoua' => intval($_post['pozitieNoua'])));
    	$mesaj = ($out != 0) ? 'Pozitie updatata cu succes!' : 'Pozitia nu s-a putut updata!';
    } elseif (isset($_post['type']) && ($_post['type'] == 'set_cititor_bit13')) {
        $return =  $aparate->set_cititor_bit13(array('idAparat' => intval($_post['idAparat']), 'seria' => $databFull->sanitize($_post['pozitieNoua']), 'valoare' => intval($_post['valoare'])), $page);
        $mesaj = $return[0];
        $out = $return[1];
        $err = $return[2];
    } elseif (isset($_post['type']) && ($_post['type'] == 'get_record_bit12')) {
        $return =  $aparate->get_record_bit12(array('idAparat' => intval($_post['idAparat']), 'seria' => $databFull->sanitize($_post['pozitieNoua']), 'valoare' => intval($_post['valoare'])), $page);
        $mesaj = $return[0];
        $out = $return[1];
        $err = $return[2];
    }

    $result = array(
    	'err' => $err,
    	'out' => $out,
    	'mesaj' => $mesaj
    );
    echo json_encode($result);
?>