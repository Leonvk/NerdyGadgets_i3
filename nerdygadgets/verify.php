<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
include "connect.php";
include "functions/verify.php";

$error = "";
$validUsername = FALSE;

if(empty($_POST['username'])) {
    $error .= "-De gebruikersnaam is niet ingevuld.<br>";
} else {
    $username = $_POST['username'];
    // Check if the user exists in the user table
    if(usernameExists($username)) {
        $validUsername = TRUE;
    } else {
        $error .= "-Deze gebruiker bestaat niet. Weet je zeker dat je de goede gebruikersnaam hebt gebruikt?<br>";
    }
}

if(empty($_POST['password'])) {
    $error .= "-Het wachtwoord is niet ingevuld.<br>";
} elseif($validUsername) {
    $password = $_POST['password'];
    // Check if the password matches the password in the database
    // Get the password from the db
    $query = "SELECT `password` FROM `user` WHERE `username` = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $userPassword = $result[0]['password'];
    // check if the passwords match
    if(password_verify($password, $userPassword)) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        die();
    } else {
        $error .= "-Onjuist wachtwoord.<br>";
    }
}

if($error != "") {
    $_SESSION['error'] = "Het volgende ging er mis:<br>" . $error;
    $_SESSION['fieldValues'] = array('username' => $username);
    header("Location: inloggen.php");
    die();
}
?>