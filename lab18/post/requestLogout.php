<?php
//Ends the session if the users logs out.
session_start();
$_SESSION = array();
session_destroy();
echo json_encode((Object)array('protocol' => 'logout'));
?>