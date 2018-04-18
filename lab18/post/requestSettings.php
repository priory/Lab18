<?php
//REQUEST TO MAKE A PERSONAL PREFERENCE CHANGE

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//Logged user sends a request to make changes to users' colors and profile picture.
//All errors are compiled and sent back to the user.
//If no error is occured the data is stored and a success message is sent back.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], integer between 0 and 19 $_POST['theme'], [optional array() $_FILE['profilePicture']].

//Returns: 
//'error' protocol with $errors containing settings change error messages, 
//'result' protocol $results containing settings change success message.

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

if (isset($_FILES['profilePicture'])) {
	//Get the file extension.
	$ext = strtolower(pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION));
	//Check the file extension.
	if ($ext !== 'png' && $ext !== 'jpg' && $ext !== 'jpeg') {
		array_push($errors, urlencode('Het bestandsformaat van de profielfoto wordt niet ondersteund. Alleen .png, .jpg en .jpeg wordt ondersteund.'));
	}
	//Check the file size
	if ($_FILES['profilePicture']['size'] > 512000) {
		array_push($errors, urlencode('De bestandsgrootte van de profielfoto mag niet groter zijn dan 500 kilobytes.'));
	}
} else {
	//Not mandatory to change the profile picture
	//array_push($errors, urlencode('Er is geen profielfoto geupload. Klik op \'Kies een Bestand\' om een profielfoto te selecteren.'));
}

if (sizeof($errors) == 0) {
	if (isset($_POST['theme'])) {
		//
		// There is no way to replace a line in a file without loading the entire file.
		//
		if ((integer)$_POST['theme'] < 20) {
			$_SESSION['theme'] = $_POST['theme'];
			$users[$userIndex][2] = $_POST['theme'];
			file_put_contents('../data/users.json', json_encode($users));
		} else {
			array_push($errors, urlencode('Corrupt package.'));
		}
	} else {
		array_push($errors, urlencode('Corrupt package.'));
	}
}

if (sizeof($errors) == 0) {
	if (isset($_FILES['profilePicture'])) {
		unlink(glob('../images/' . ($_SESSION['username']) . '.*')[0]);
		move_uploaded_file($_FILES['profilePicture']['tmp_name'], '../images/' . $_SESSION['username'] . '.' . $ext);
	}
	echo json_encode((Object)array_merge(array('protocol' => 'result'), array('messages' => $results)));
} else {
	//Errors, send the error messages
	echo json_encode((Object)array_merge(array('protocol' => 'error'), array('messages' => $errors)));
}
?>