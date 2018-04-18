<?php
session_start();
if (!isset($_SESSION['id'])) {
	$_SESSION['id'] = session_id();
}
if (!isset($_SESSION['username'])) {
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/lab18/home.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Lab 18 - Instellingen</title>
	<link rel="shortcut icon" type="image/x-icon" href="/lab18/resources/lab18favicon.ico">
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
						case ('responseChange'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						window.location.href = window.location.origin + '/lab18/home.php?thread=' + packet['messages'][0];
						break;
						case ('error'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						break;
						case ('result'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						window.location.reload();
						break;
						case ('logout'):
						document.cookie = 'save=""; max-age=0;';
						window.location.pathname = '/lab18/home.php';
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
			document.querySelectorAll('.colors.atmos')[atmos].style.border = '2px solid white';
			document.querySelectorAll('.colors.theme')[theme].style.border = '2px solid white';
			remBorderAtmos = () => {
				var e = document.querySelectorAll('.colors.atmos');
				for (i in e) {
					if (typeof e[i] == 'object') {
						e[i].style.border = 'none';
					}
				}
			}
			remBorderTheme = () => {
				var e = document.querySelectorAll('.colors.theme');
				for (i in e) {
					if (typeof e[i] == 'object') {
						e[i].style.border = 'none';
					}
				}
			}
			var e = document.querySelectorAll('.colors.atmos')
			for (i in e) {
				if (typeof e[i] == 'object') {
					e[i].style.left = (50 + 55 * i) + 'px';
				}
			}
			e = document.querySelectorAll('.colors.theme')
			for (i in e) {
				if (typeof e[i] == 'object') {
					e[i].style.left = (50 + 55 * i) + 'px';
				}
			}
			document.getElementById('change').onclick = () => {
				var file = document.getElementById('profilePicture').files[0];
				var o = [
				['theme',(atmos * 10 + theme)],
				];
				if (file) {
					o.push(['profilePicture', file, file.name]);
				}
				request('./post/requestSettings.php', o);
			}
			document.getElementById('profilePictureButton').onclick = () => {
				document.getElementById('profilePicture').click();
			}

			window.onresize = () => {
				document.getElementsByClassName('menuBar')[0].style.display = '';
				document.getElementsByClassName('menuBar')[0].style.display = console.log(window.getComputedStyle(document.getElementsByClassName('menuBar')[0]).getPropertyValue('display'));
			}
			document.getElementsByClassName('stackButton')[0].onclick = () => {
				if (document.getElementsByClassName('menuBar')[0].style.display == 'block') {
					document.getElementsByClassName('menuBar')[0].style.display = 'none';
				} else {
					document.getElementsByClassName('menuBar')[0].style.display = 'block';
				}
			}

			document.getElementById('profilePicture').onchange = () => {
				document.getElementById('imageStatus').innerHTML = document.getElementById('profilePicture').files[0].name;
			}
		}

	</script>
</head>
<body>
	<?php
	$logged = false;
	//Check if the user is logged in
	if (isset($_SESSION['username']) && isset($_SESSION['theme'])) {
		$username = $_SESSION['username'];
		if ($_SESSION['theme'] >= 10) {
			$theme = array(1, $_SESSION['theme'] - 10);
		} else {
			$theme = array(0, $_SESSION['theme']);
		}
		$logged = true;
		echo '<script>var atmos = ' . ($theme[0]) . '; var theme = ' . ($theme[1]) . '</script>';
	}

	//styling
	include('./print/css/style.php');
	$menu = '';
	//If the user is logged draw the menu
	if ($logged) {
		$menu = sprintf(file_get_contents('./print/homeMenuLogged.html'), ucfirst($username) . '<div style="width: 50px; height: 50px; position: absolute; right: -60px; top: -15px;
			background-image: url(\'' . glob('./images/' . ($username) . '.*')[0] . '\'); background-size: cover; background-position: center;"></div>');
	} else {
		header('Location: http://' . $_SERVER['HTTP_HOST'] . '/lab18/home.php');
	}
	$threadMain = '
	<div class="preferences">

	<div class="label atmosphere">Kies je sfeerkleur: </div>
	<div class="colors atmos" onclick="atmos = 0; remBorderAtmos(); this.style.border = \'2px solid white\';" style="background-color: #333;"></div>
	<div class="colors atmos" onclick="atmos = 1; remBorderAtmos(); this.style.border = \'2px solid white\';" style="background-color: #EEE;"></div>

	<div class="label theme">Kies je themakleur:</div>
	<div class="colors theme" onclick="theme = 0; remBorderTheme(); this.style.border = \'2px solid white\';" style="background-color: #0078d7;"></div>
	<div class="colors theme" onclick="theme = 1; remBorderTheme(); this.style.border = \'2px solid white\';" style="background-color: #ffb900;"></div>
	<div class="colors theme" onclick="theme = 2; remBorderTheme(); this.style.border = \'2px solid white\';" style="background-color: #e81124;"></div>
	<div class="colors theme" onclick="theme = 3; remBorderTheme(); this.style.border = \'2px solid white\';" style="background-color: #7a7574;"></div>
	<div class="colors theme" onclick="theme = 4; remBorderTheme(); this.style.border = \'2px solid white\';" style="background-color: #10893e;"></div>

	<input type="file" id="profilePicture" accept="image/png,image/jpg,image/jpeg" style="display: none;">
	<div class="label file">Kies een andere profiel foto:</div>
	<div class="button file" id="profilePictureButton">Kies een bestand</div>
	<div id="imageStatus" class="label status">Geen bestand gekozen</div>
	<input type="text" id="theme" value="' . ($_SESSION['theme']) . '" style="display: none;"><br>
	<div class="button update" id="change">Aanpassen</div>
	</div>';
	echo sprintf(file_get_contents('./print/homeMain.html'), $menu, $threadMain);
	?>

</body>
</html>
