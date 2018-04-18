<?php
//ELAVUALTE LOGIN POST ERQUEST

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//the logging user sends login data to this url. 
//All errors are compiled and sent back to the user.
//If no error is occured the user is logged in successfully and the session id is saved.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], array(size=2) $_POST['password'], array() $_FILE['profilePicture'].

//Returns: 
//'error' protocol with $errors containing login error messages, 
//'result' protocol $results containing login success message.

//Disclaimer: 
//this code should not be used when data needs to be sent and read securely and in large quantities.
session_start();
$errors = [];
$results = [];

//Checking the username
if (!empty($_POST['username'])) {
	//Checking if the username is between 3 and 24 characters
	if (strlen($_POST['username']) >= 3 && strlen($_POST['username'] <= 24 )) {
		$_POST['username'] = strtolower($_POST['username']);
		//Load the JSON formated users database
		$users = json_decode(file_get_contents('../data/users.json'));
		//Check if the username exists and cache the user index
		foreach ($users as $i => $v) {
			if ($_POST['username'] === $v[0]) {
				$userIndex = $i;
			}
		}
		if (!isset($userIndex)) {
			array_push($errors, urlencode('Geen gebruiker gevonden met de gebruikersnaam: ' . $_POST['username'] . '.'));
		}
	} else {
		array_push($errors, urlencode('De gebruikersnaam moet minimaal uit 3 en maximaal 24 tekens bestaan.'));
	}
} else {
	array_push($errors, urlencode('Voer uw gebruikersnaam in.'));
}

//if user is found, check the password and session id
if (isset($userIndex)) {
	//Checking the password
	if (!empty($_POST['password'])) {
		//Checking if the requested login password matches the password of 'users.json' file
		if ($users[$userIndex][1] !== $_POST['password']) {
			array_push($errors, urlencode('Het wachtwoord is onjuist.'));
		}
	} else {
		array_push($errors, urlencode('Voer uw wachtwoord in.'));
	}
	//Check the session id
	if (isset($_SESSION['id'])) {
		if (is_string($_SESSION['id'])) {
			if (strlen($_SESSION['id']) < 16) {
				array_push($errors, urlencode('Invalid session id format.'));
			}
		} else {
			array_push($errors, urlencode('Session is not a string.'));
		}
	} else {
		array_push($errors, urlencode('Failed to get session id.'));
	}
}


//Evaluation result
if (sizeof($errors) == 0) {
	//No errors, log the user in by saving the session id
	$users[$userIndex][3] = $_SESSION['id'];
	file_put_contents('../data/users.json', json_encode($users));
	$_SESSION['username'] = $users[$userIndex][0];
	$_SESSION['theme'] = $users[$userIndex][2];
	//send the result message
	array_push($results, urlencode('Inlogeen is gelukt, ' . ucfirst($_POST['username']) . '.'));
	echo json_encode((Object)array_merge(array('protocol' => 'result'), array('messages' => $results)));
} else {
	//Errors, send the error messages
	echo json_encode((Object)array_merge(array('protocol' => 'error'), array('messages' => $errors)));
}
?>