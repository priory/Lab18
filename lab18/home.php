<?php
session_start();
if (!isset($_SESSION['id'])) {
	$_SESSION['id'] = session_id();
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo '<link rel="shortcut icon" type="image/x-icon" href="http://' . ($_SERVER['HTTP_HOST']) . '/lab18/resources/lab18favicon.ico">'?>
	<script type="text/javascript">
		//This is the request function that makes requests to the server. Other pages have the same principle
		//All response php files are location in 'post' folder
		//url = requested url(the response php file), fd = form data in array format. 
		request = (url, fd = []) => {
			req = new XMLHttpRequest();
			//spread the fd array into FormData object
			var data = new FormData();
			for (var i in fd) {
				data.append(...fd[i]);
			}
			//response event
			req.onreadystatechange = (res) => {
				res = res.currentTarget;
				if (res.status == 200 && res.readyState == 4) {
					//parses the packet if it's json formatted
					var packet;
					try {
						packet = JSON.parse(res.responseText);
					} catch (error) {
						packet = res.responseText;
					}
					//first value is always to protocol id. From it is determined which request was made
					switch (packet['protocol']) {
						//Response if the thread deletion was successful
						case ('responseDeleteThread'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						if (packet['messages'][0] == 'deleteThread') {
							window.location.search = '';
						} else {
							location.reload();
						}
						break;
						//Response if the changes of reactions were successful
						case ('responseChange'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						location.reload();
						break;
						//Display error messages incase login or registration fails
						case ('error'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						break;
						//If the login or register reuqest is successful
						case ('result'):
						for (var i in packet['messages']) {
							console.log(urldecode(packet['messages'][i]));
						}
						break;
						//The logout response
						case ('logout'):
						window.location.pathname = '/lab18/home.php';
						break;
						default:
						console.log(res.responseText);
					}
				}
			}
			//Send the packet with data to the server
			req.open('POST', url, true);
			req.send(data);
		}
		//Encoding and decoding of php url formatted strings
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
	$theme = array(1, 0);
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
		//Draw guest menu if no user is logged
		$menu = sprintf(file_get_contents('./print/homeMenuMain.html'));
	}
	//See if the query string has a specific thread.
	if (isset($_GET['thread'])) {
		//Thread header
		$thread = json_decode(file_get_contents('./data/threads.json'));
		$title = urldecode($thread[$_GET['thread']][1]);
		$thread = '<div id="threadTitle"><h2>' . htmlspecialchars(urldecode($thread[$_GET['thread']][1])) . '</h2><div>aangemaakt door: <strong style="overflow: hidden;">' . ucfirst($thread[$_GET['thread']][0]) . '</strong> op ' . htmlspecialchars(urldecode($thread[$_GET['thread']][2])) . '</div></div>';

		$posts = new SplFileObject('./data/posts.txt');
		$posts->seek($_GET['thread']);
		$posts = json_decode($posts->current());

		$reactions = '';
		//All reactions
		$totalReactions = 0;
		foreach ($posts as $i => $v) {
			$totalReactions ++;
			$reactions .= '<div class="reaction" style="top: ' . (-2 * $i - 20) . 'px;" id="r' . ($i) . '">' . '<div class="name"><strong style="overflow: hidden;">' . htmlspecialchars(ucfirst($v[0])) . '</strong></div>' . 
			'<div class="timeStampReact">' . ($v[1]) . '</div>' .
			'<div class="message">' . (str_replace(chr(10), '<br>', htmlspecialchars(urldecode($v[2])))) . '</div>' .
			'<div style="width: 50px; height: 50px; position: absolute; top: 2px; left: 2px; background-image: url(\'' . glob('./images/' . ($v[0]) . '.*')[0] . '\'); background-size: cover; background-position: center;"></div>'
			;
			if (isset($_SESSION['username'])) {
				//First reaction
				if ($_SESSION['username'] == $v[0]) {
					if ($i != 0) {
						$reactions .= '<div class="button change" id="b' . ($i) . '" onclick="
						document.getElementById(\'s' . ($i) . '\').style.display = \'inline-block\';
						this.style.display = \'none\';
						">Wijzigen</div>
						<div class="settings" id=s' . ($i) . '>
						<textarea class="messageEdit">' . urldecode($v[2]) . '</textarea>
						<div class="button send"
						onclick="
						request(\'./post/requestChange.php\', [
							[\'thread\', ' . ($_GET['thread']) . '],
							[\'reaction\', ' . ($i) . '],
							[\'message\', document.getElementById(\'s' . ($i) . '\').children[0].value],
						]);
						">Verzenden</div>';
					//Remaining reactions
					} else {
						$reactions .= '<div class="button change" id="b' . ($i) . '" onclick="
						document.getElementById(\'s' . ($i) . '\').style.display = \'inline-block\';
						this.style.display = \'none\';
						">Wijzigen</div><div class="settings" id=s' . ($i) . '>
						<input type="text" class="editTitle" value="' . ($title) . '"></input><br>
						<textarea class="messageEditTitle">' . urldecode($v[2]) . '</textarea>
						<div class="button send" 
						onclick="
						request(\'./post/requestChange.php\', [
							[\'thread\', ' . ($_GET['thread']) . '],
							[\'reaction\', ' . ($i) . '],
							[\'message\', document.getElementById(\'s' . ($i) . '\').children[2].value],
							[\'title\',  document.getElementById(\'s' . ($i) . '\').children[0].value]
						]);
						">Verzenden</div>';
					}
					//Additional properties for all reactions
					$reactions .= '<div class="button cancel" onclick="
					document.getElementById(\'b' . ($i) . '\').style.display = \'inline-block\';
					document.getElementById(\'s' . ($i) . '\').style.display = \'none\';
					">X</div>';
					$reactions .= '<div class="button delete"
					onmouseover="this.style.backgroundColor = \'red\';" 
					onmouseleave="this.style.backgroundColor = \'#00000000\';"
					onclick="
					request(\'./post/requestDelete.php\', [
						[\'thread\', ' . ($_GET['thread']) . '],
						[\'reaction\', ' . ($i) . ']
					]);
					">Verwijderen</div>';
					$reactions .= '</div>';
				}
			}
			$reactions .= '</div>';
		}
		if ($logged) {
			$reactions .= '<br><textarea id="reaction" style="bottom: ' . (35 + $totalReactions * 2) . 'px"></textarea>
			<div class="button react" style="bottom: ' . (15 + $totalReactions * 2) . 'px" onclick="
			request(\'./post/requestReact.php\', [
				[\'thread\', ' . ($_GET['thread']) . '],
				[\'message\', document.getElementById(\'reaction\').value],
			]);
			">Reageer</div>';
		}
		echo sprintf(file_get_contents('./print/homeMain.html'), $menu, $thread . '<br>' . $reactions);

	} else {
	//Thread index, all threads are shown here
		$threads = json_decode(file_get_contents('./data/threads.json'));
		$threadMain = '';
		foreach ($threads as $i => $v) {
			$i = sizeof($threads) - 1 - $i;
			$threadMain .= '<div onclick="
			window.location.search = \'?thread=' . ($i) . '\';
			"class="thread" id="t' . ($i) . '" style="position: relative; top: ' . ($i * 2) . 'px">' . '<div style="width: 50px; height: 50px; display: inline-block; position: relative; left: 2px; top: 2px;
			background-image: url(\'' . glob('./images/' . ($threads[$i][0]) . '.*')[0] . '\'); background-size: cover; background-position: center;"></div><span style="position: relative; top: -15px;left: 10px;"><strong>' . 
			ucfirst($threads[$i][0]) . '</strong>' . 
			'<span class="timeStamp">, ' . ($threads[$i][2]) . '</span>: <span class="threadTitle">' . 
			htmlspecialchars(urldecode($threads[$i][1])) . 
			'</span></span></div>';
		}
		echo sprintf(file_get_contents('./print/homeMain.html'), $menu, $threadMain);
	}
	?>
</body>
</html>
