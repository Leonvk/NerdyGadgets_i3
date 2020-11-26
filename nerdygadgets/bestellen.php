<?php
/*include __DIR__ . "/header.php";*/
//header
session_start();
if(isset($_SESSION['username'])) {
    header("Location: confimatieBestelling.php");
    die();
}

include "connect.php";
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html lang="en" style="background-color: rgb(35, 35, 47);">
<head>
    <script src="Public/JS/fontawesome.js" crossorigin="anonymous"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/Resizer.js"></script>
    <script src="Public/JS/jquery-3.4.1.js"></script>
    <style>
        @font-face {
            font-family: MmrText;
            src: url(/Public/fonts/mmrtext.ttf);
        }
    </style>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/nha3fuq.css">
    <link rel="apple-touch-icon" sizes="57x57" href="Public/Favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="Public/Favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Public/Favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Public/Favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Public/Favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Public/Favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Public/Favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Public/Favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Public/Favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Public/Favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Public/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Public/Favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Public/Favicon/favicon-16x16.png">
    <link rel="manifest" href="Public/Favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="Public/Favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <ul id="ul-class-navigation">
            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search" style="color:#ffffff;"></i>  Zoeken</a>
            </li>
            <li>
            <?php
                    if(isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                        echo("<a href=\"account.php\" class=\"HrefDecoration\"><i class=\"fas fa-user\" style=\"color:#ffffff;\"></i> $username</a>");
                    } else {
                        echo("<a href=\"inloggen.php\" class=\"HrefDecoration\"><i class=\"fa fa-sign-in\" style=\"color:#ffffff;\"></i>  Inloggen</a>");
                    }
                ?>            </li>
            <li>
                <a href="winkelwagen.php" class="HrefDecoration"><i class="fas fa-shopping-basket" style="color:#ffffff;"></i>  Winkelwagen</a>
            </li>
        </ul>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
<!--einde header-->
      <div class = "container form-signin">
      </div>
      <div class = "container">
          <h2>Kassa</h2>
         <form class = "form-signin" role = "form" action = "confimatieBestelling.php" method = "post">
             <div class = "Naam">
             Naam*<br>
             <input type = "text" class = "form-control"
               name = "shipment_address_first_name" placeholder = "Voornaam" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['firstName'] . "\"");} ?> required autofocus>
             <input type = "text" class = "form-control"
                    name = "shipment_address_name_addition" placeholder = "Tussenv." <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['middleName'] . "\"");} ?>>
             <input type = "text" class = "form-control"
                    name = "shipment_address_last_name" placeholder = "Achternaam" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['lastName'] . "\"");} ?> required><br>
             </div>
             <div class = "Postcode">
             Postcode*<br>
                 <input type = "text" class = "form-control" name = "shipment_address_post_code" placeholder = "Postcode" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['postcode'] . "\"");} ?> required>
             </div>
             <div class = "Huisnummer">
             Huisnummer*<br>
                 <input type = "text" class = "form-control"
                    name = "shipment_address_house_number" placeholder = "Nr." <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['huisnummer'] . "\"");} ?> required>
             </div>
             <div class = "email">
                 E-maildres*<br>
                 <input type = "email" class = "form-control"
                        name = "email" placeholder = "example@nerdygadgets.com" <?php if(isset($_SESSION['error'])) {echo("value=\"" . $_SESSION['fieldValues']['email'] . "\""); unset($_SESSION['fieldValues']);} ?> required>
             </div>
             <br>
             <button class = "btn btn-lg btn-primary btn-block" type = "submit" name = "confirmOrder">Doorgaan</button>
         </form>
         <?php if(isset($_SESSION['error'])) {echo("<p style=\"color: red\">" . $_SESSION['error'] . "</p>"); unset($_SESSION['error']);} ?>
      </div>
   </body>
</html>



















































































