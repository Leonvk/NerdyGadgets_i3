<?php
session_start();
include "connect.php";

$error = "";

if(empty($_POST['username'])) {
    $error .= " De gebruikersnaam is niet ingevuld!";
}

if(empty($_POST['password'])) {
    $error .= " Het wachtwoord is niet ingevuld!";
}

if($error != "") {
    $_SESSION['error'] = $error;
    header("Location: inloggen.php");
    die();
}
?>