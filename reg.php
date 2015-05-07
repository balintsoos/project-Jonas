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
		$username = trim($_POST['username']);
		$password = $_POST['password'];
		$users = fajlbol_betolt('users.json');
		
		if (strlen($email) == 0) {
			$errs[] = 'Email is required';
		}
		if (strlen($username) == 0) {
			$errs[] = 'Username is required';
		}
		if (strlen($password) == 0) {
			$errs[] = 'Password is required';
		}
		if (array_key_exists($email, $users)) {
			$errs[] = 'Email already exist';
		}
		if (!$errs) {
			$users[$email] = [$username, md5($password)];
			fajlba_ment('users.json', $users);
			header('Location: login.php');
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Sign up</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1">
	<meta name="author" content="Balint Soos">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/substyle.css">
</head>
<body>
<form action="" method="post">
	<div id="card">
		<header>
			<h1>Sign Up</h1>
			<p>Create a new account</p>
		</header>
		<div>
			<form action="" method="post">
				<table>
					<tr>
						<td>Email: </td>
						<td><input type="email" name="email" id="email"></td>
						<td><span id="spanreg"></span></td>
					</tr>
					<tr>
						<td>Username: </td>
						<td><input type="text" name="username"></span></td>
					</tr>
					<tr>
						<td>Password: </td>
						<td><input type="password" name="password"><br></td>
					</tr>
				</table>
				<?php if($errs) : ?>
					<ul>
						<?php foreach ($errs as $err) : ?>
							<li><?= $err ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<input type="submit" name="reg" value="Sign up">
			</form>
		</div>
	</div>

	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/reg.js"></script>
</body>