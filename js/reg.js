function $ (id) {
	return document.getElementById(id);
}

function init () {
	$('email').addEventListener('change', regEll, false);
}
window.addEventListener('load', init, false);

function regEll (e) {
	var email = this.value;
	ajax({
		url: 'regell.php',
		getadat: 'email='+ encodeURIComponent(email),
		siker: function	(xhr, data) {
			//console.log(data);
			var json = JSON.parse(data);
			var unique = json.unique;
			$('spanreg').innerHTML = unique ? 'OK' : 'Already exist';
		}
	});
}