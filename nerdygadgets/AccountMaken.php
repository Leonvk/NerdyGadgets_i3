<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";
?>
<html lang = "en">
<head>
    <title>NerdyGadgets</title>
    <link href = "css/bootstrap.min.css" rel = "stylesheet">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
        }

        h2{
            text-align: center;
            color: #212529;
        }
    </style>
</head>
<body>
    <h2>Maak hier je nerdygadgets account aan!</h2>
      <div class = "container form-signin">
      </div>
      <div class = "container">
         <form class="form-signin" role="form" action="register.php" method="post">
            <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['username'] . "\"");} ?> required autofocus></br>
            <input type="text" class="form-control" name="firstName" placeholder="Voornaam*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['firstName'] . "\"");} ?> required></br>
            <input type="text" class="form-control" name="middleName" placeholder="Tussenvoegsel" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['middleName'] . "\"");} ?> ></br>
            <input type="text" class="form-control" name="lastName" placeholder="Achternaam*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['lastName'] . "\"");} ?> required></br>
            <input type="email" class="form-control" name="email" placeholder="e-mail*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['email'] . "\"");} ?> required></br>
            <input type="password" class="form-control" name="password" placeholder="Wachtwoord*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['password'] . "\"");} ?> required></br>
            <input type="password" class="form-control" name="password1" placeholder="Herhaal wachtwoord*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['password1'] . "\"");} ?> required></br>
            <input type="text" class="form-control" name="postcode" placeholder="Postcode*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['postcode'] . "\"");} ?> required></br>
            <input type="text" class="form-control" name="huisnummer" placeholder="Huisnummer*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['huisnummer'] . "\"");} ?> required></br>
            <input type="text" class="form-control" name="phone" placeholder="Telefoonnummer*" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['phone'] . "\""); unset($_SESSION['fieldValues']);} ?> required></br>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Registreer</button>
         </form>
         <?php if(isset($_SESSION['error'])) {echo("<p style=\"color: red;\">" . $_SESSION['error'] . "</p>"); unset($_SESSION['error']);} ?>
      </div>
   </body>
</html>