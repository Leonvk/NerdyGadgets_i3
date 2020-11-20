<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";

if(isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    echo("<meta http-equiv='refresh' content='0'>");
}

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
    <h2>Log in met je e-mailadress</h2>
      <div class = "container form-signin">
      </div>
      <div class = "container">
         <form class="form-signin" role="form" action="verify.php" method="post">
            <input type="text" class="form-control" name="username" placeholder="Gebruikersnaam" <?php if(isset($_SESSION['fieldValues'])) {echo("value=\"" . $_SESSION['fieldValues']['username'] . "\"");} else {echo("autofocus");} ?> required></br>
            <input type="password" class="form-control" name="password" placeholder="Wachtwoord" <?PHP if(isset($_SESSION['fieldValues'])) {echo("autofocus");} unset($_SESSION['fieldValues']); ?> required>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
         </form>
         <?php if(isset($_SESSION['error'])) {echo("<p style=\"color: red;\">" . $_SESSION['error'] . "</p>"); unset($_SESSION['error']);} ?>
         Nog geen account?<a href="AccountMaken.php"> Klik hier om er een aan te maken!</a>
      </div>
   </body>
</html>