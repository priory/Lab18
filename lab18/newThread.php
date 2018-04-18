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
	<title>Lab 18 - Nieuw Thread</title>
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
	<div class="newThread">
	<div class="label title">Titel:</div>
	<input type="text" id="title" class="newThreadTitle">
	<div class="label message">Bericht:</div>
	<textarea id="message" class="newThreadMessage"></textarea>
	<div class="button" onclick="
	request(\'./post/requestNewThread.php\', [
		[\'title\', document.getElementById(\'title\').value],
		[\'message\',document.getElementById(\'message\').value]
	]);
	">
	Versturen</div>
	</div>
	';
	echo sprintf(file_get_contents('./print/homeMain.html'), $menu, $threadMain);
	?>

</body>
</html>
