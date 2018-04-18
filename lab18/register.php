<?php
session_start();
if (!isset($_SESSION['id'])) {
	$_SESSION['id'] = session_id();
}
if (isset($_SESSION['username'])) {
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/lab18/home.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo '<link rel="shortcut icon" type="image/x-icon" href="http://' . ($_SERVER['HTTP_HOST']) . '/lab18/resources/lab18favicon.ico">'?>
	<title></title>
	<script type="text/javascript">
		request = (url, fd = []) => {
			req = new XMLHttpRequest();
			var data = new FormData();
			for (var i in fd) {
				data.append(...fd[i]);
			}
			req.onreadystatechange = (res) => {
				res = res.currentTarget;
				if (res.status == 200 && res.readyState == 4) {
					var packet;
					try {
						packet = JSON.parse(res.responseText);
					} catch (error) {
						packet = res.responseText;
					}
					switch (packet['protocol']) {
						case ('error'):
						document.getElementsByClassName('errors')[0].innerHTML = '';
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
							document.getElementsByClassName('errors')[0].innerHTML += '- ' + urldecode(packet['messages'][i]) + '<br>';
						}
						break;
						case ('result'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						window.history.back();
						break;
						default:
						console.log(res.responseText);
					}
				}
			}
			req.open('POST', url, true);
			req.send(data);
		}
		urldecode = (url) => {
			return decodeURIComponent(url.replace(/\+/g, ' '));
		}
		urlencode = (url) => {
			return encodeURIComponent(url).replace(/%20/g, '+');
		}

		window.onload = () => {
			document.getElementById('register').onclick = () => {
				var file = document.getElementById('profilePicture').files[0];
				var o = [
				['username', document.getElementById('username').value],
				['password', JSON.stringify([
					document.getElementById('password[0]').value, 
					document.getElementById('password[1]').value
					])]
				];
				if (file) {
					o.push(['profilePicture', file, file.name]);
				}
				request('./post/evaluateRegister.php', o);
			}
			document.getElementById('profilePictureButton').onclick = () => {
				document.getElementById('profilePicture').click();		
			}
			document.getElementById('profilePicture').onchange = () => {
				document.getElementById('imageStatus').innerHTML = document.getElementById('profilePicture').files[0].name;
			}
		}
	</script>
</head>
<body>
	<img id="bodyBackground" src="https://wallpapertag.com/wallpaper/full/2/0/6/235574-best-wallpaper-windows-10-1920x1080-for-1080p.jpg";>
	<?php
	$theme = array(0, 0);
	include('./print/css/style.php');
	?>
	<img src="./resources/lab18logo.png" width=100 id="headerLogo" style="left: 50%; transform: translateX(-50%);">
	<div class="registration">
		<div class="label name">Gebruikersnaam: </div>
		<input type="text" id="username" class="text name">
		<div class="label password">Wachtwoord: </div>
		<input type="password" id="password[0]" class="text password">
		<div class="label repeat">Opnieuw wachtwoord: </div>
		<input type="password" id="password[1]" class="text repeat">
		<div class="label profile">Profiel foto: </div>
		<input type="file" id="profilePicture" accept="image/png,image/jpg,image/jpeg" style="display: none;">
		<div class="button file" id="profilePictureButton">Kies een bestand</div>
		<div id="imageStatus" class="label status">Geen bestand gekozen</div>
		<div class="button registrate" id="register" onclick="
		var file = document.getElementById('profilePicture').files[0];
		var o = [
		['username', document.getElementById('username').value],
		['password', JSON.stringify([
			document.getElementById('password[0]').value, 
			document.getElementById('password[1]').value
			])]
		];
		if (file) {
			o.push(['profilePicture', file, file.name]);
		}
		request('./post/evaluateRegister.php', o);
		">
	Registreren</div>
	<div class="button home" onclick="window.location.href = window.location.origin + '/lab18/home.php';">Home</div>
	<div class="errors"></div>
</div>
</body>
</html>