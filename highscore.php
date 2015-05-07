<?php
	function fajlba_ment($fajlnev, $adat) {
		$s = json_encode($adat);
		return file_put_contents($fajlnev, $s, LOCK_EX);
	}
	function fajlbol_betolt($fajlnev, $alap = array()) {
		$s = @file_get_contents($fajlnev);
		return ($s === false
			? $alap
			: json_decode($s, true));
	}

	if(isset($_SESSION['logedin'])) {
		if($_SESSION['logedin']) {
			$email = $_SESSION['email'];
			$score = $_GET['score'];
			
			$highscores = fajlbol_betolt('highscores.json');
			
			if (array_key_exists($email, $highscores)) {
				array_push ($highscores[$email], $score);
				rsort($highscores[$email]);
			} 
			else {
				$highscores[$email] = [$score];
			}

			fajlba_ment('highscores.json', $highscores);

			//$result['unique'] = !(array_key_exists($email, $users));

			echo json_encode($highscores[$email]);
		}
	}
?>