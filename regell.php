<?php
	function fajlbol_betolt($fajlnev, $alap = array()) {
		$s = @file_get_contents($fajlnev);
		return ($s === false
			? $alap
			: json_decode($s, true));
	}

	$email = $_GET['email'];
	$users = fajlbol_betolt('users.json');

	$result['unique'] = !(array_key_exists($email, $users));

	echo json_encode($result);
?>