<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    die();
} else {
    $username = $_SESSION['username'];
    $userID = $_SESSION['userID'];
}

if(array_key_exists('logOut', $_POST)) {
    unset($_SESSION['username']);
    unset($_SESSION['userID']);
    header("Location: inloggen.php");
    die();
}

$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');

if(array_key_exists('deleteConfirm', $_POST)) {
    // Voorkom dat het admin test account verwijderd kan worden
    if($_SESSION['username'] == 'admin') {
        $error = "<p style=\"color: red;\">Dit account kan niet verwijderd worden</p><br>";
    } else {
        // 'remove' account
        $query = "UPDATE `user` SET `username` = '[Deleted]', `password` = NULL WHERE `userID` = ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "s", $userID);
        mysqli_stmt_execute($statement);
        unset($_SESSION['username']);
        unset($_SESSION['userID']);
        header("Location: index.php");
        die();
    }
} elseif(array_key_exists('deleteCancel', $_POST)) {
    header("Location: account.php");
    die();
}
include __DIR__ . "/header.php";

$userID = $_SESSION['userID'];
// Get all the user information
$query = "SELECT `firstName`, `middleName`, `lastName`, `email`, `userSince`, `phone`, `addressID` FROM `user` WHERE `userID` = ?";
$statement = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($statement, "i", $userID);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
$firstName = $result[0]['firstName'];
$middleName = $result[0]['middleName'];
$lastName = $result[0]['lastName'];
$email = $result[0]['email'];
$userSince = $result[0]['userSince'];
$phone = $result[0]['phone'];
$addressID = $result[0]['addressID'];
$query = "SELECT `postalCode`, `number` FROM `address` WHERE `addressID` = ?";
$statement = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($statement, "i", $addressID);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
$postalCode = $result[0]['postalCode'];
$number = $result[0]['number'];

if(isset($error)) {echo($error);}
?>
<h2>Overzicht van het account <?php echo($username); ?></h2>
<button><a href="orders.php">Mijn bestellingen</a></button>
<div id="accountOverview">
<?php

    echo("Gebruikersnaam: $username<br>Voornaam: $firstName<br>Tussenvoegsel: $middleName<br>Achternaam: $lastName<br>e-mail: $email<br>Gebruiker sinds: $userSince<br>Telefoonnummer: $phone<br>Postcode: $postalCode<br>Huisnummer: $number");
?>
</div>
<form method="post">
    <input type="submit" value="Uitloggen" name="logOut" id="AccountUitlogKnop">
</form>

<?php
if(!isset($_POST['delete'])) {
    echo("
    <form method=\"post\">
        <input type=\"submit\" value=\"Account verwijderen\" name=\"delete\" id=\"AccountVerwijderKnop\">
    </form>
    ");
} else {
    echo("
    <div style=\"\">
    <h3>Weet je zeker dat je je account wilt verwijderen? Dit kan <b><u>niet</u></b> ongedaan worden gemaakt!</h3>
        <form method=\"post\">
            <input type=\"submit\" value=\"Ik bevestig hiermee dat ik al mijn accountgegevens kwijt raak en ik geen toegang meer heb tot mijn account.\" name=\"deleteConfirm\" style=\"background-color: red\">
            <input type=\"submit\" value=\"Annuleren\" name=\"deleteCancel\" style=\"background-color: green\">
        </form>
    </div>
    "); 
}
?>