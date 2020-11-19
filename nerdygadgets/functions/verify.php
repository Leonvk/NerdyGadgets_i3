<?php
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
include "connect.php";

// Checks if the given parameter cointains valid username characters
function validUsernameChar($username) {
    if(preg_match("/^[a-zA-Z0-9_\-.]{4,20}$/", $username)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// Checks if the username already exists in the database
function usernameExists($username) {
    global $connection;
    $query = "SELECT username FROM user WHERE username = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $returnableResult = mysqli_stmt_get_result($statement);
    if(mysqli_num_rows($returnableResult) == 0) {
       return TRUE;
    } else {
        return FALSE;
    }
}
?>