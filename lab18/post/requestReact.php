<?php
//REQUEST CHANGE OF A REACTION OR THREAD

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//Logged user sends a request to make a reaction to a thread.
//All errors are compiled and sent back to the user.
//If no error is occured the data is stored and a success message is sent back.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.
//'threads.json' and 'posts.txt'. If the threads.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], string $_POST['message'], integer $_POST['thread'].

//Returns: 
//'error' protocol with $errors containing reaction error messages, 
//'responseChange' protocol $results containing reaction success message.

//Disclaimer: 
//This code should not be used when data needs to be sent and stored securely and in large quantities.

session_start();
$errors = [];
$results = [];

$users = json_decode(file_get_contents('../data/users.json'));

if (isset($_SESSION['username'])) {
	if (isset($_SESSION['id'])) {
		foreach ($users as $i => $v) {
			if ($_SESSION['username'] === $v[0]) {
				$userIndex = $i;
			}
		}
		if (isset($userIndex)) {
			if ($users[$userIndex][3] !== $_SESSION['id']) {
				array_push($errors, urlencode('Session id mismatch.'));
			}
		} else {
			array_push($errors, urlencode('Username not found.'));
		}
	} else {
		array_push($errors, urlencode('Session \'id\' is not set.'));
	}
} else {
	array_push($errors, urlencode('Session \'username\' is not set.'));
}

if (sizeof($errors) == 0) {
	if (isset($_POST['thread']) && isset($_POST['message'])) {
		$threads = file('../data/posts.txt');

		$thread = json_decode($threads[$_POST['thread']]);
		array_push($thread, [$users[$userIndex][0], date('Y-m-d H:i:s'), urlencode($_POST['message'])]);
		$threads[$_POST['thread']] = json_encode($thread) . "\r\n";
		$file = fopen('../data/posts.txt', 'w+');
		fwrite($file, implode($threads));
		fclose($file);

	} else {
		array_push($errors, urlencode('Corrupt package.'));
	}
}

if (sizeof($errors) == 0) {
	echo json_encode((Object)array_merge(array('protocol' => 'responseChange'), array('messages' => $results)));
} else {
	//Errors, send the error messages
	echo json_encode((Object)array_merge(array('protocol' => 'error'), array('messages' => $errors)));
}
?>