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

	$logedin = false;

	if(isset($_SESSION['logedin'])) {
		if($_SESSION['logedin']) {
			$logedin = true;
		}
		else{
			$logedin = false;
		}
	}

	$skins = fajlbol_betolt('skins.json');
	$objs = array_keys($skins);
	$skin = isset($_GET['skin']) ? $_GET['skin'] : "default";
	
	$name = $skins[$skin]["name"];
	$bg = $skins[$skin]["bg"];
	$color = $skins[$skin]["color"];
	$player = $skins[$skin]["player"];
	$bot = $skins[$skin]["bot"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= $name ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1">
	<meta name="author" content="Balint Soos">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/skin.php?skin=<?=$skin?>">
	<link rel="stylesheet" type="text/css" href="css/keys.css">
</head>

<body>
	<header>
		<?php if($logedin) : ?>
			<span>Welcome, <?= $_SESSION['username'] ?></span><a href="logout.php"><button>Log out</button></a>
		<?php else : ?>
			<a href="login.php"><button>Log in</button></a>
			<a href="reg.php"><button>Sign up</button></a>
		<?php endif; ?>
	</header>
	<h1><?= $name ?></h1>
	<nav>
		<div id="startButton" class="button">start</div>
		<div id="settingsButton" class="button">settings</div>
		<div id="settingsPanel" class="panel">
			<ul>
				<li>Gameboard:<input id="inM" type="text" size="2" value="10">X<input id="inN" type="text" size="2" value="10"></li>
				<li>Safe Teleports:<input id="inT" type="text" size="2" value="3"></li>
				<li>Themes:
					<ul id="themes">
						<?php foreach ($objs as $item) : ?>
							<li><a href="index.php?skin=<?= $item ?>"><?= $skins[$item]["name"] ?></a></li>
						<?php endforeach; ?>
					</ul>	
				</li>
			</ul>
			<ul id="errors"></ul>
		</div>
		<div id="controlsButton" class="button">controls</div>
		<div id="controlsPanel" class="panel" style="display:none">
			<span>8 directions:</span>
			<table class="keybs">
				<tr>
					<td><kbd class="light">Q</kbd></td>
					<td><kbd class="light">W</kbd></td>
					<td><kbd class="light">E</kbd></td>
				</tr>
				<tr>
					<td><kbd class="light">A</kbd></td>
					<td><kbd class="light">S</kbd></td>
					<td><kbd class="light">D</kbd></td>
				</tr>
				<tr>
					<td colspan="3"><kbd class="light">Y</kbd><kbd class="light">X</kbd></td>
				</tr>
			</table>
			<br>
			<span>4 directions:</span>
			<table class="keybs">
				<tr>
					<td></td>
					<td><kbd class="light">&uparrow;</kbd></td>
					<td></td>
				</tr>
				<tr>
					<td><kbd class="light">&leftarrow;</kbd></td>
					<td><kbd class="light">&downarrow;</kbd></td>
					<td><kbd class="light">&rightarrow;</kbd></td>
				</tr>
			</table>
			<br>
			<span>Teleport: </span><kbd class="light">T</kbd>
		</div>
	</nav>
	<div id="play">
		<div id="level"></div>
		<div id="points"></div>
		<div id="teleports"></div>
		<table id="board"></table>
		<div id="status"></div>
		<div id="teleError"></div>
	</div>
	
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
</body>
</html>