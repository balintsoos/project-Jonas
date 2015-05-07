<?php
	header("Content-type: text/css");
	

	function fajlbol_betolt($fajlnev, $alap = array()) {
		$s = @file_get_contents($fajlnev);
		return ($s === false
			? $alap
			: json_decode($s, true));
	}
	
	$skins = fajlbol_betolt('../skins.json');
	$skin = isset($_GET['skin']) ? $_GET['skin'] : "default";
	
	$name = $skins[$skin]["name"];
	$bg = $skins[$skin]["bg"];
	$color = $skins[$skin]["color"];
	$invert = $skins[$skin]["invert"];
	$player = $skins[$skin]["player"];
	$bot = $skins[$skin]["bot"];
?>

body {
	background-image: url("../<?= $bg ?>");
	color: <?= $color ?>;
}

:link, a:visited {
	color: <?= $color ?>;
}

header {
	background-color: <?= $color ?>;
	color: <?= $invert ?>;
	border-bottom: 2px solid <?= $invert ?>;
}

header a:link, header a:visited {
	color: <?= $invert ?>;
}

button {
	background-color: <?= $invert ?>;
	color: <?= $color ?>;
}

.button, .panel, input[type="text"], select {
	border: 2px solid <?= $color ?>;
}

.button:hover {
	background-color: <?= $color ?>;
	box-shadow: 0 0 6px 2px <?= $color ?>;
	color: <?= $invert ?>;
}

.button:active {
	box-shadow: 0 0 12px 6px <?= $color ?>;
	color: <?= $invert ?>;
}

.player {
	background-image: url("../<?= $player ?>");
}

.bot {
	background-image: url("../<?= $bot ?>");
}

.wreck {
	background-image: url("../skins/wreck.png");
}

input[type="text"], select {
	color: <?= $color ?>;
}

input[type="text"]:focus {
	box-shadow: 0 0 6px 2px <?= $color ?>;
}

#board, #board td {
	border: 1px solid <?= $color ?>;
}