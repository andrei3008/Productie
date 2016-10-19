<?php

function connectToFtp($server,$username,$pass,$port) {
    $server = "rodiz.ro";
    $port = 21;
    $user = "catalin";
    $pass = "catag";
    $folder = "ftp://rodiz.ro/FisierePDF/";
    $conn_id = ftp_connect($server, $port);
    $login_result = ftp_login($conn_id, $user, $pass);
    if (!is_array(ftp_nlist($conn_id, "."))) {
        $error = 'not Connected! :(';
    }
}

function uploadFile($files, $uploadDirectory, $postInfo) {
    $error = '';
    $extensieFisier = $newstring = substr($files['upload']['name'], -3);
    if (strtolower($extensieFisier) != "pdf") {
        $error .= 'Fisierul nu este de tip pdf.';
    } else {
        $server = "rodiz.ro";
        $port = 21;
        $user = "catalin";
        $pass = "catag";
        $folder = "ftp://rodiz.ro/FisierePDF/";
        $conn_id = ftp_connect($server, $port);
        $login_result = ftp_login($conn_id, $user, $pass);
        if (!is_array(ftp_nlist($conn_id, "."))) {
            $error = 'not Connected! :(';
        }
        ftp_chdir($conn_id, 'FisierePDF');
        ftp_chdir($conn_id, $uploadDirectory);
        $folder_exists = is_dir('ftp://catalin:catag@rodiz.ro/FisierePDF/' . $uploadDirectory . '/' . date('Y'));
        if ($folder_exists) {
            ftp_chdir($conn_id, date('Y'));
        } else {
            ftp_mkdir($conn_id, date('Y'));
        }
        if (ftp_put($conn_id, $postInfo['serie'] . '.pdf', $files['upload']['tmp_name'], FTP_BINARY)) {
            
        } else {
            $error.='Fisierul nu a putut fi uploadat';
        }
    }
    return $error;
}

?>