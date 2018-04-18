<?php
//EVALUATE REGISTRATION POST REQUEST

//Author: V.Packo(522513)
//Class: JADICA4A7A

//Design: 
//registrating user sends registration data to this url. 
//All errors are compiled and sent back to the user.
//If no error is occured the data is stored and a success message is sent back.

//Dependencies: 
//'users.json' file is required on relative pathname:'../data/users.json'. If the user.json file is empty, fill the file with '[]'.

//Variables: 
//string $_POST['username'], array(size=2) $_POST['password'], array() $_FILE['profilePicture'].

//Returns: 
//'error' protocol with $errors containing registration error messages, 
//'result' protocol $results containing registration success message.

//Disclaimer: 
//This code should not be used when data needs to be sent and stored securely and in large quantities.

$errors = [];
$results = [];

//Checking the username
if (!empty($_POST['username'])) {
	//Checking if the username is between 3 and 24 characters

	if (strlen($_POST['username']) >= 3 && strlen($_POST['username'] <= 15 )) {
		$_POST['username'] = strtolower($_POST['username']);
		//Checking if the username only contains letters and numbers
		if (preg_match('/^[a-zA-Z0-9]{1,}$/', $_POST['username'])) {
			//Load the JSON formated users database
			$users = json_decode(file_get_contents('../data/users.json'));
			foreach ($users as $v) {
				//Checking if the username is taken or if it's 'default' since default is reserved
				if ($_POST['username'] === $v[0] || $_POST['username'] == 'default') {
					array_push($errors, urlencode('Deze gebruikersnaam is al bezet.'));
				}
			}
		} else {
			array_push($errors, urlencode('Deze gebruikersnaam is ongeldig. Gebruik alleen letters en cijfers.'));
		}
	} else {
		array_push($errors, urlencode('De gebruikersnaam moet minimaal uit 3 en maximaal 15 tekens bestaan.'));
	}
} else {
	array_push($errors, urlencode('Voer uw gebruikersnaam in.'));
}

//Checking the password
//Checking the first password
$_POST['password'] = json_decode($_POST['password']);
if (!empty($_POST['password'][0])) {
	//Checking if the first password is between 6 and 32 characters
	if (strlen($_POST['password'][0]) >= 6 && strlen($_POST['password'][0]) <= 32) {
		//Checking if the first password has ASCII characters, excluding whitespace characters, including space.
		if (!preg_match('/^[:ascii:\S ]{1,}$/', $_POST['password'][0])) {
			array_push($errors, urlencode('De gekozen tekens zijn ongeldig. Alleen tekens van de \'ASCII\' tekenset is geldig. \'Whitespace\' karakters zijn niet toegestaan(behalve \'spatie\').'));
		}
	} else {
		array_push($errors, urlencode('De wachtwoord moet minimaal uit 6 en maximaal 32 tekens bestaan.'));
	}
} else {
	array_push($errors, urlencode('Voer uw wachtwoord in.'));
}
//Check the second password
if (!empty($_POST['password'][1])) {
	//Check if the first password and the second password match
	if ($_POST['password'][1] !== $_POST['password'][0]) {
		array_push($errors, urlencode('De wachtwoorden komen niet overheen.'));
	}
} else {
	array_push($errors, urlencode('Voer het wachtwoord nogmaals in.'));
}

//Checking the file
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
	array_push($errors, urlencode('Er is geen profielfoto geupload. Klik op \'Kies een Bestand\' om een profielfoto te selecteren.'));
}

//Evaluation result
if (sizeof($errors) == 0) {
	//No errors, create user
	$users[] = array($_POST['username'], $_POST['password'][0], 0, '');
	file_put_contents('../data/users.json', json_encode($users));
	move_uploaded_file($_FILES['profilePicture']['tmp_name'], '../images/' . $_POST['username'] . '.' . $ext);
	//send the result message
	array_push($results, urlencode('Registratie is gelukt, ' . ucfirst($_POST['username']) . '!'));
	echo json_encode((Object)array_merge(array('protocol' => 'result'), array('messages' => $results)));
} else {
	//Errors, send the error messages
	echo json_encode((Object)array_merge(array('protocol' => 'error'), array('messages' => $errors)));
}
?>
