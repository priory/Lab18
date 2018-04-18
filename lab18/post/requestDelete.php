<?php
//REQUEST REMOVAL OF A THREAD OR REACTION

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//Logged user sends a request to delete a thread or reaction 
//All errors are compiled and sent back to the user.
//If no error is occured the data is stored and a success message is sent back.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.
//'threads.json' and 'posts.txt'. If the threads.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], integer $_POST['thread'], integer $_FILE['reaction']].

//Returns: 
//'error' protocol with $errors containing deletion error messages, 
//'responseDeleteThread' protocol $results containing deletion success message.

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
	if (isset($_POST['thread']) && isset($_POST['reaction'])) {
		//load the files
		$threads = file('../data/posts.txt');
		//If the author of the thread then delete the entire thread and all posted data for that thread
		if ($_POST['reaction'] == 0) {
			array_splice($threads, $_POST['thread'], 1);
			$file = fopen('../data/posts.txt', 'w+');
			fwrite($file, implode($threads));
			fclose($file);

			$file = json_decode(file_get_contents('../data/threads.json'));
			array_splice($file, $_POST['thread'], 1);
			file_put_contents('../data/threads.json', json_encode($file));

			array_push($results, urlencode('deleteThread'));
		} else {
			//Else, just delete the reaction
			$thread = json_decode($threads[$_POST['thread']]);
			array_splice($thread, $_POST['reaction'], 1);
			$threads[$_POST['thread']] = json_encode($thread) . "\r\n";
			$file = fopen('../data/posts.txt', 'w+');
			fwrite($file, implode($threads));
			fclose($file);
		}
	} else {
		array_push($errors, urlencode('Corrupt package.'));
	}
}

if (sizeof($errors) == 0) {
	echo json_encode((Object)array_merge(array('protocol' => 'responseDeleteThread'), array('messages' => $results)));
} else {
	//Errors, send the error messages
	echo json_encode((Object)array_merge(array('protocol' => 'error'), array('messages' => $errors)));
}
?>