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
	<title>Lab18 - Logins</title>
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
						if (document.getElementById('saveUsername').checked) {
							var c = encodeURIComponent(JSON.stringify({name: document.getElementById('username').value}));
							var date = new Date();
							date.setTime(date.getTime() + (1000 * 60 * 60 * 24 * 365 * 10));
							date = date.toUTCString();
							document.cookie = 'save=' + c + '; expires=' + (date);
						} else {
							document.cookie = 'save=""; max-age=0;';
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
		parseCookie = () => {
			var c = document.cookie.split('; ');
			for (i in c) {
				c[i] = c[i].split('=');
			}
			var o = {};
			for (i in c) {
				o[c[i][0]] = decodeURIComponent(c[i][1]);
			} 
			return (o);
		}

		window.onload = () => {
			var cookie = parseCookie();
			if (typeof cookie['save'] == 'string') {
				cookie = JSON.parse(cookie['save']);
				if (typeof cookie['name'] == 'string') {
					document.getElementById('username').value = cookie['name'];
					document.getElementById('saveUsername').checked = true;
				}
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
	<div class="panel">
		<div class="label name">Gebruikersnaam: </div>
		<input type="text" id="username" class="name">
		<div class="label password">Wachtwoord: </div>
		<input type="password" id="password" class="password">
		<div id="login" class="button login" onclick="
		request('./post/evaluateLogin.php', [
			['username', document.getElementById('username').value],
			['password',document.getElementById('password').value]
			]);
			">
		Inloggen</div><br>
		<input type="checkbox" id="saveUsername" class="checkbox remember"><div class="remember">Onthou mijn gebruikersnaam.</div>
		<div class="button home" onclick="window.location.href = window.location.origin + '/lab18/home.php';">Home</div>
		<div class="button register" onclick="window.location.pathname = './lab18/register.php';">Registreren</div>
		<div class="errors"></div>
	</div>
</body>
</html>