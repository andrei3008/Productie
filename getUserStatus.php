<?php
require_once('classes/SessionClass.php');
require_once('include/dbConnect.php');
$session = new SessionClass();
$userid = $con->real_escape_string($_POST['id']);
$user_info = getUserInfoById($userid, $con);
echo $user_info->resetPass;