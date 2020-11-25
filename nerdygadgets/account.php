<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    die();
}

if(array_key_exists('logOut', $_POST)) {
    unset($_SESSION['username']);
    unset($_SESSION['userID']);
    header("Location: index.php");
}

$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
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
?>
<h2>Overzicht van het account <?php echo($username); ?></h2>
<div id="accountOverview">
<?php 
    echo("Gebruikersnaam: $username<br>Voornaam: $firstName<br>Tussenvoegsel: $middleName<br>Achternaam: $lastName<br>e-mail:$email<br>Gebruiker sinds: $userSince<br>Telefoonnummer: $phone<br>Postcode: $postalCode<br>Huisnummer: $number");
?>
</div>
<form method="post">
    <input type="submit" value="Uitloggen" name="logOut">
</form>