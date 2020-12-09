<?php
session_start();
include "functions/verify.php";
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
include "connect.php";

$error = "";
$validUsername = FALSE;
$validFirstName = FALSE;
$validMiddleName = FALSE;
$validLastName = FALSE;
$validEmail = FALSE;
$validPassword = FALSE;
$validPassword1 = FALSE;
$passwordMatch = FALSE;
$validPostcode = FALSE;
$validHuisnummer = FALSE;
$validPhone = FALSE;

// Checks if the username field has been filled
if(empty($_POST['username'])) {
    $error .= "-Er is geen gebruikersnaam ingevuld.<br>";
} else {
    // Save the user input
    $username = $_POST['username'];
    // Checks if the username has the right length
    if(!(3 < strlen($username) && strlen($username) < 21)) {
        $error .= "-De gebruikersnaam moet minimaal 4 karakters lang zijn en maximaal 20.<br>";
    } else {
        // Checks if the username contains valid characters
        if(!validUsernameChar($username)) {
            $error .= "-De gebruikersnaam mag de volgende karakters bevatten: a-z, 0-9, '_', '-', '.'.<br>";
        } else {
            // Checks if the username is unique
            if(usernameExists($username)) {
                $error .= "-Deze gebruikersnaam bestaat al<br>";
            } else {
                $validUsername = TRUE;
            }
        }
    }
}

// Checks if the firstName field has been filled
if(empty($_POST['firstName'])) {
    $error .= "-Er is geen voornaam ingevuld.<br>";
} else {
    // Save the user input
    $firstName = $_POST['firstName'];
    // Checks if the first name is shorter or equal to 35 char
    if(strlen($firstName) > 36) {
        $error .= "-De voornaam mag niet langer zijn dan 35 karakters.<br>";
    } else {
        // Checks if the first name contains only letters
        if(preg_match("/^[a-zA-Z]{0,}$/", $firstName)) {
            $validFirstName = TRUE;
        } else {
            $error .= "-De voornaam mag alleen letters bevatten.<br>";
        }
    }
}

// Checks if the middleName field has been filled
if(empty($_POST['middleName'])) {
    $middleName = NULL;
    $validMiddleName = TRUE;
} else {
    // Save the user input
    $middleName = $_POST['middleName'];
    // Checks if the middle name is shorter or equal to 35 char
    if(strlen($middleName) > 36) {
        $error .= "-Het tussenvoegsel mag niet langer zijn dan 35 karakters.<br>";
    } else {
        // Checks if the middle name contains only letters or spaces
        if(preg_match("/^[a-zA-Z ]{0,}$/", $middleName)) {
            $validMiddleName = TRUE;
        } else {
            $error .= "-Het tussenvoegsel mag alleen letters bevatten.<br>";
        }
    }
}

// Checks if the lastName field has been filled
if(empty($_POST['lastName'])) {
    $error .= "-Er is geen achternaam ingevuld.<br>";
} else {
    // Save the user input
    $lastName = $_POST['lastName'];
    // Checks if the last name is shorter or equal to 35 char
    if(strlen($lastName) > 35) {
        $error .= "-De achternaam mag niet langer zijn dan 35 karakters.<br>";
    } else {
        // Checks if the last name contains only letters
        if(preg_match("/^[a-zA-Z]{0,}$/", $lastName)) {
            $validLastName = TRUE;
        } else {
            $error .= "-De achternaam mag alleen letters bevatten.<br>";
        }
    }
}

// Checks if the email field has been filled
if(empty($_POST['email'])) {
    $error .= "-Er is geen e-mailadres ingevuld.<br>";
} else {
    // Save the user input
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "-Het emailadres heeft niet het juiste formaat: example@nerdygadgest.com<br>";
    } else {
        $validEmail = TRUE;
    }
}

// Checks if the password field has been filled
if(empty($_POST['password'])) {
    $error .= "-Er is geen wachtwoord ingevuld.<br>";
} else {
    // Save the user input
    $password = $_POST['password'];
    // Check if the password is longer or equal to 8 characters
    if(strlen($password) < 8) {
        $error .= "-Het wachtwoord moet een minimale lengte van 8 karakters hebben.<br>";
    } else {
        $validPassword = TRUE;
    }
}

// Checks if the password1 field has been filled
if(empty($_POST['password1'])) {
    $error .= "-Het wachtwoord is niet herhaald.<br>";
} else {
    // Save the user input
    $password1 = $_POST['password'];
    // Check if the password is longer or equal to 8 characters
    if(strlen($password1) < 8) {
        $error .= "-Het wachtwoord moet een minimale lengte van 8 karakters hebben.<br>";
    } else {
        $validPassword1 = TRUE;
    }
}

// Checks if the password match
if($validPassword && $validPassword1) {
    if($password != $password1) {
        $error .= "-De wachtwoorden komen niet overeen.</br>";
    } else {
        $passwordMatch = TRUE;
    }
}

// Checks if the postcode field has been filled
if(empty($_POST['postcode'])) {
    $error .= "-Er is geen postcode ingevuld.<br>";
} else {
    // Save the user input
    $postcode = $_POST['postcode'];
    // Checks if the postal code format is right
    if(preg_match("/^[0-9]{4}[A-Z]{2}$/", $postcode)) {
        $validPostcode = TRUE;
    } else {
        $error .= "-De postcode heeft een onjuist formaat. (1234AB)<br>";
    }
}

// Check if the huisnummer field has been filled
if(empty($_POST['huisnummer'])) {
    $error .= "-Er is geen huisnummer ingevuld.<br>";
} else {
    // Save the user input
    $huisnummer = $_POST['huisnummer'];
    // Check if the number is correct
    if(preg_match("/^[0-9a-zA-Z]{0,}$/", $huisnummer)) {
        $validHuisnummer = TRUE;
    } else {
        $error .= "-Het huisnummer is onjuist ingevuld<br>";
    }
}

// Check if the phone field has been filled
if(empty($_POST['phone'])) {
    $error .= "-Er is geen telefoonnummer ingevuld.<br>";
} else {
    // Save the user input
    $phone = $_POST['phone'];
    // Check if the phone number is correct
    if(preg_match("/^[0-9]{8,12}$/", $phone)) {
        $validPhone = TRUE;
    } else {
        $error .= "-Het telefoonnummer is onjuist ingevuld.<br>";
    }
}

if($error != "") {
    $_SESSION['error'] = "Het volgende ging er mis:<br>" . $error;
    $_SESSION['fieldValues'] = array('username' => $username, 'firstName' => $firstName, 'middleName' => $middleName, 'lastName' => $lastName, 'email' => $email, 'password' => $password, 'password1' => $password1, 'postcode' => $postcode, 'huisnummer' => $huisnummer, 'phone' => $phone);
    header("Location: AccountMaken.php");
    die();
} elseif($validUsername && $validFirstName && $validMiddleName && $validLastName && $validEmail && $validPassword && $validPassword1 && $passwordMatch && $validPostcode && $validHuisnummer && $validPhone) {
    // If every field has been deemed worthy put everything into the database
    // First store the date in the user table
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO `user`(`username`, `firstName`, `middleName`, `lastName`, `email`, `password`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "ssssssi", $username, $firstName, $middleName, $lastName, $email, $hashedPassword, $phone);
    mysqli_stmt_execute($statement);
    // Get the new userID
    $query = "SELECT `userID` FROM `user` WHERE `username` = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $userID = $result[0]['userID'];
    // Store the data in the address table
    $query = "INSERT INTO `address`(`userID`, `postalCode`, `number`) VALUES (?, ?, ?)";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "iss", $userID, $postcode, $huisnummer);
    mysqli_stmt_execute($statement);
    // Get the new addressID
    $query = "SELECT `addressID` FROM `address` WHERE `userID` = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "i", $userID);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $addressID = $result[0]['addressID'];
    // Update the addressID
    $query = "UPDATE `user` SET `addressID` = ? WHERE `userID` = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "ii", $addressID, $userID);
    mysqli_stmt_execute($statement);
    $_SESSION['fieldValues'] = array('username' => $username);
    header("Location: inloggen.php");
    die();
} else {
    echo("Something went wrong");
    echo("<br>Username:$validUsername<br>First name:$validFirstName<br>Middle name:$validMiddleName<br>Last name:$validLastName<br>Email:$validEmail<br>Password:$validPassword<br>Password1:$validPassword1<br>Password match:$passwordMatch<br>Postcode:$validPostcode<br>Number:$validHuisnummer<br>Phone:$validPhone");
}
?>