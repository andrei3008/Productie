<?php
	require_once "../autoloader.php";

	$session = new SessionClass();
	$timp = 0;
	if (isset($_SESSION['changed-luna'])) {
		// timpul de la schimbarea lunii din select
		$timp = time()-$_SESSION['changed-luna'];
		$msg = 'Timp trecut de la schimbarea lunii :';
	}

	header('Content-Type: application/json');
	echo json_encode(array('timp' => $timp, 'mesaj' => $msg, 'an' => date('Y'), 'luna' => date('n')));
?>