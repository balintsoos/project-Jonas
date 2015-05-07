//Made by Balint Soos

function init () {
	$('startButton').addEventListener('click', checkData, false);
	$('settingsButton').addEventListener('click', function(){togglePanel('settings');}, false);
	$('controlsButton').addEventListener('click', function(){togglePanel('controls');}, false);
	document.addEventListener('keydown', step, false);
}
window.addEventListener('load', init, false);

var M; //row
var N; //column
var T; //teleport
var start = false;
var end = false;
var points;
var teleports;
var level;
var playerPos;
var botsPos = [];
var wrecksPos = [];

function checkData () {
	var errs=[];
	M = $('inM').value;
	N = $('inN').value;
	T = $('inT').value;

	if(M==='' || N==='' || T===''){
		errs.push('All fields are required!');
	}
	if(isNaN(M) || isNaN(N) || isNaN(T) || T<1){
		errs.push('You have to use positive numbers!');
	}
	if(N<4 || M<4){
		errs.push('The board has to be at least 4x4!');
	}
	if(errs.length){
		$('errors').innerHTML = errArray(errs);
	}
	else{
		$('errors').innerHTML = '';
		gameInit();
	}
}

function gameInit () {
	start = true;
	end = false;
	M = $('inM').value;
	N = $('inN').value;
	T = $('inT').value;
	level = 0;
	points = 0;
	$('startButton').innerHTML = 'restart';
	$('settingsPanel').style.display = 'none';
	$('controlsPanel').style.display = 'none';
	$('board').innerHTML = createBoard();
	$("points").innerHTML = 'Points: ' + points;
	$('status').innerHTML = '';

	nextLevel();
}

function nextLevel () {
	level++;
	teleports = T;
	$('level').innerHTML = 'Level: ' + level;
	$('teleports').innerHTML = 'Safe Teleport jumps: ' + teleports;
	$('teleError').innerHTML = '';
	playerInit();
	display(playerPos.y,playerPos.x,'player');
	botsPos = [];
	wrecksPos = [];
	botsInit();
}

function createBoard () {
	var b = '';
	for(var i=0; i<M; i++)
	{
		b+='<tr>';
		for(var j=0; j<N; j++){
			b+='<td></td>';
		}
		b+='</tr>';
	}
	return b;
}

function playerInit () {
	playerPos = {
		x: random(N),
		y: random(M)
	};
}

function botsInit () {
	var sameCell;
	for (var i = 0; i < level*10; i++) {
		do {
			sameCell = false;
			botsPos[i] = {
				x: random(N),
				y: random(M),
				dead: false
			};
			if (botsPos[i].x === playerPos.x &&
				botsPos[i].y === playerPos.y){
				sameCell = true;
			}
			for(var j=0; j < i; j++){
				if (botsPos[j].x === botsPos[i].x &&
					botsPos[j].y === botsPos[i].y){
					sameCell = true;
					break;
				}
			}
		}
		while(sameCell);
		display(botsPos[i].y,botsPos[i].x,'bot');
	}
}

function step (e) {
	if(start === true && end === false){
		var key = e.which;
		if(goodKey(key)) {
			if(key === 37 || key === 65) move(e, 'left');
			else if(key === 38 || key === 87) move(e, 'up');
			else if(key === 39 || key === 68) move(e, 'right');
			else if(key === 40 || key === 83) move(e, 'down');
			else if(key === 81) move(e, 'left-up');
			else if(key === 69) move(e, 'right-up');
			else if(key === 88) move(e, 'right-down');
			else if(key === 89) move(e, 'left-down');
			else if(key === 84) move(e, 'teleport');

			moveBots();
			collisionBots();
			$('points').innerHTML = 'Points: ' + points;
			for(var i in botsPos){
				display(botsPos[i].y,botsPos[i].x,'bot');
			}
			for(var i in wrecksPos){
				display(wrecksPos[i].y,wrecksPos[i].x,'wreck');
			}
			if(collisionPlayer())
			{
				start = false;
				end = true;
				$('status').innerHTML = 'GAME OVER!';
				highscore(points);
			}
			else if(botsPos.length === 0){
				clearBoard();
				nextLevel();
			}
		}
	}
}

function move (e, where) {
	e.preventDefault();
	hide(playerPos.y,playerPos.x);
	switch (where){
		case 'left':
			if(playerPos.x>0) 
			{
				playerPos.x--;
				points--;
				rotate(270);
			}
			break;
		case  'up':
			if(playerPos.y>0) 
			{
				playerPos.y--;
				points--;
				rotate(0);
			}
			break;
		case 'right':
			if(playerPos.x<N-1) 
			{
				playerPos.x++;
				points--;
				rotate(90);
			}
			break;
		case 'down':
			if(playerPos.y<M-1) 
			{
				playerPos.y++;
				points--;
				rotate(180);
			}
			break;
		case 'left-up':
			if(playerPos.x>0 && playerPos.y>0)
			{
				playerPos.x--;
				playerPos.y--;
				points--;
				rotate(315);
			}
			break;
		case 'right-up':
			if(playerPos.x<N-1 && playerPos.y>0)
			{
				playerPos.x++;
				playerPos.y--;
				points--;
				rotate(45);
			}
			break;
		case 'right-down':
			if(playerPos.x<N-1 && playerPos.y<M-1)
			{
				playerPos.x++;
				playerPos.y++;
				points--;
				rotate(135);
			}
			break;
		case 'left-down':
			if(playerPos.x>0 && playerPos.y<M-1)
			{
				playerPos.x--;
				playerPos.y++;
				points--;
				rotate(225);
			}
			break;
		case 'teleport':
			teleport();
			points-=100;
			teleports--;
			break;
	}
	display(playerPos.y,playerPos.x,'player');
	if(teleports>=0){
		$('teleports').innerHTML = 'Safe Teleport jumps: ' + teleports;
	}
}

//Made by BS

function teleport () {
	if(teleports < 1){
		playerInit();
	}
	else{
		var wrongCell;
		var teleStepCounter = 0;
		var currentPlayerPos = playerPos;
		do{
			teleStepCounter++;
			wrongCell = false;
			playerInit();
			for(var i in botsPos){
				if ((Math.abs(botsPos[i].x - playerPos.x) < 2) &&
					(Math.abs(botsPos[i].y - playerPos.y) < 2)){
					wrongCell = true;
					break;
				}
			}
			for(var i in wrecksPos){
				if (wrecksPos[i].x === playerPos.x &&
					wrecksPos[i].y === playerPos.y){
					wrongCell = true;
					break;
				}
			}
		}
		while(wrongCell && (teleStepCounter < (N*M*4)));
		if(wrongCell === true){
			playerPos = currentPlayerPos;
			$('teleError').innerHTML = "I can't calculate a safe Teleport jump :(";
		}
	}
}

function moveBots () {
	for(var i in botsPos){
		hide(botsPos[i].y,botsPos[i].x);
		if(botsPos[i].x > playerPos.x) {
			botsPos[i].x--;
		}
		if(botsPos[i].x < playerPos.x) {
			botsPos[i].x++;
		}
		if(botsPos[i].y > playerPos.y) {
			botsPos[i].y--;
		}
		if(botsPos[i].y < playerPos.y) {
			botsPos[i].y++;
		}
	}
}

function collisionPlayer () {
	var collision = false;
	for(var i in botsPos){
		if (botsPos[i].x === playerPos.x &&
			botsPos[i].y === playerPos.y){
			collision = true;
			break;
		}
	}
	for(var i in wrecksPos){
		if (wrecksPos[i].x === playerPos.x &&
			wrecksPos[i].y === playerPos.y){
			collision = true;
			break;
		}
	}
	return collision;
}

function collisionBots () {
	for (var i=0; i<botsPos.length; i++){
		for (var j=0; j<i; j++){
			if (botsPos[i].x === botsPos[j].x &&
				botsPos[i].y === botsPos[j].y){
				wrecksPos.push(botsPos[i]);
				botsPos[i].dead = true;
				botsPos[j].dead = true;
				points+=40;
			}
		}
	}
	for (var i = botsPos.length - 1; i >= 0; i--) {
		if (botsPos[i].dead) {
			botsPos.splice(i,1);
		}
	}
	if(botsPos.length > 0){
		for (var i in botsPos){	
			for (var k in wrecksPos){
				if (botsPos[i].x === wrecksPos[k].x &&
					botsPos[i].y === wrecksPos[k].y){
					botsPos[i].dead = true;
					points+=20;
				}
			}
		}
	}
	for (var i = botsPos.length - 1; i >= 0; i--) {
		if (botsPos[i].dead) {
			botsPos.splice(i,1);
		}
	}
}

//---------------- Helper functions --------------------------------

function rotate (deg) {
	var cell = $('board').rows[playerPos.y].cells[playerPos.x];
	cell.style.transform = "rotate(" + deg + "deg)";
}

function display (row,column,what) {
	var cell = $('board').rows[row].cells[column];
	if(what === 'player'){
		cell.className = "player";
	}
	if(what === 'bot'){
		cell.style.transform = "";
		cell.className = "bot";
	}
	if(what === 'wreck'){
		cell.className = "wreck";
	}
}

function hide (row,column) {
	var cell = $('board').rows[row].cells[column];
	cell.className = "";
}

function clearBoard () {
	hide(playerPos.y,playerPos.x);
	for(var i in botsPos){
		hide(botsPos[i].y,botsPos[i].x);
	}
	for(var i in wrecksPos){
		hide(wrecksPos[i].y,wrecksPos[i].x);
	}
}

function errArray (x) {
	return '<li>' + x.join('</li><li>') + '</li>';
}

function $ (id) {
	return document.getElementById(id);
}

function random (x) {
	return Math.floor(Math.random() * x);
}

function goodKey (key) {
	return ([37,65,38,87,39,68,40,83,81,69,88,89,84].indexOf(key) >= 0);
}

function togglePanel (which) {
	var panel = $(which+'Panel');
	if(panel.style.display == 'none'){
		panel.style.display = 'block';
	}
	else{
		panel.style.display = 'none';
	}
}

function highscore (e, point) {
	var score = point;
	ajax({
		url: 'highscore.php',
		getadat: 'score='+ encodeURIComponent(score),
		siker: function	(xhr, data) {
			//console.log(data);
			var json = JSON.parse(data);
			//var userHighscore = json.userHighscore;
			//$('spanreg').innerHTML = unique ? 'OK' : 'Already exist';
		}
	});
}

//Made by Balint Soos