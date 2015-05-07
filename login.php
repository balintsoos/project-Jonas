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

	session_start();

	if(isset($_SESSION['logedin'])) {
		header('Location: index.php');
	}

	$errs = array();
	
	if ($_POST) {
		$email = trim($_POST['email']);
		$password = $_POST['password'];
		$users = fajlbol_betolt('users.json');

		if (!(array_key_exists($email, $users) &&
			$users[$email][1] == md5($password))) {
			$errs[] = 'Invalid username or password';
		}
		if (!$errs) {
			$_SESSION['logedin'] = true;
			$_SESSION['email'] = $email;
			$_SESSION['username'] = $users[$email][0];
			header('Location: index.php');
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Log in</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1">
	<meta name="author" content="Balint Soos">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/substyle.css">
</head>

<body>
	<div id="card">
		<header>
			<h1>Log In</h1>
			<p>Log in with your existing account</p>
		</header>
		<div>
			<form action="" method="post">
				<table>
					<tr>
						<td>Email: </td>
						<td><input type="email" name="email"></td>
					</tr>
					<tr>
						<td>Password: </td>
						<td><input type="password" name="password"></td>
					</tr>
				</table>
				<?php if($errs) : ?>
					<ul>
						<?php foreach ($errs as $err) : ?>
							<li><?= $err ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<input type="submit" name="login" value="Log In">
			</form>
			<a href="reg.php">New to the game? Sign up!</a>
		</div>
	</div>
</body>