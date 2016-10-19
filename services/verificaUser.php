<?php 
require_once('../includes/dbFull.php');
$post = $db->sanitizePost($_POST);
if($db->verifyUser($post['username'],$post['password'])){
	echo "Login a reusit";
}else{
	echo "Login esuat";
}