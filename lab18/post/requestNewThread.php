<?php
//REQUEST TO MAKE A NEW THREAD

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//Logged user sends a request to create a new thread
//All errors are compiled and sent back to the user.
//If no error is occured the data is stored and a success message is sent back.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.
//'threads.json' and 'posts.txt'. If the threads.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], string $_POST['message'], string $_FILE['reaction']].

//Returns: 
//'error' protocol with $errors containing thread creation error messages,
//'responseChange' protocol $results containing new thread creation success message.

//Disclaimer: 
//This code should not be used when data needs to be sent and stored securely and in large quantities.

session_start();
$errors = [];
$results = [];

$users = json_decode(file_get_contents('../data/users.json'));
//Check if the client's session id macthes the id of the logged user
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
	if (isset($_POST['title']) && isset($_POST['message'])) {
		//Add the thread to the threads file.
		$file = json_decode(file_get_contents('../data/threads.json'));
		array_push($file, [$users[$userIndex][0], urlencode($_POST['title']), date('Y-m-d H:i:s')]);
		file_put_contents('../data/threads.json', json_encode($file));
		//Add the thread message to posts.txt
		$threads = file('../data/posts.txt');
		array_push($results, urlencode(sizeof($threads)));
		array_push($threads, json_encode([[$users[$userIndex][0], date('Y-m-d H:i:s'), urlencode($_POST['message'])]]) . "\r\n");
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