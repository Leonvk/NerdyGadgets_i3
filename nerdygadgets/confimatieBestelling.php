<?php
/*include __DIR__ . "/header.php";*/
//header
session_start();
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');

// Make sure only when someone has confirmed their order they can get on this page
if((!isset($_POST['confirmOrder']) && !isset($_SESSION['username'])) || !isset($_SESSION['cart'])) {
    header("Location: index.php");
    die();
} else {
    // Check for user input from the bestellen.php page
    if(isset($_POST['confirmOrder'])) {
        // Verify user input
        $error = "";
        $validFirstName = FALSE;
        $validMiddleName = FALSE;
        $validLastName = FALSE;
        $validEmail = FALSE;
        $validPostcode = FALSE;
        $validHuisnummer = FALSE;

        // Checks if the shipment_address_first_name field has been filled
        if(empty($_POST['shipment_address_first_name'])) {
            $error .= "-Er is geen voornaam ingevuld.<br>";
        } else {
            // Save the user input
            $firstName = $_POST['shipment_address_first_name'];
            // Checks if the first name is shorter or equal to 35 char
            if(strlen($firstName) > 35) {
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

        // Checks if the shipment_address_name_addition field has been filled
        if(empty($_POST['shipment_address_name_addition'])) {
            $middleName = NULL;
            $validMiddleName = TRUE;
        } else {
            // Save the user input
            $middleName = $_POST['shipment_address_name_addition'];
            // Checks if the middle name is shorter or equal to 35 char
            if(strlen($middleName) > 35) {
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

        // Checks if the shipment_address_last_name field has been filled
        if(empty($_POST['shipment_address_last_name'])) {
            $error .= "-Er is geen achternaam ingevuld.<br>";
        } else {
            // Save the user input
            $lastName = $_POST['shipment_address_last_name'];
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

        // Checks if the shipment_address_post_code field has been filled
        if(empty($_POST['shipment_address_post_code'])) {
            $error .= "-Er is geen postcode ingevuld.<br>";
        } else {
            // Save the user input
            $postcode = $_POST['shipment_address_post_code'];
            // Checks if the postal code format is right
            if(preg_match("/^[0-9]{4}[A-Z]{2}$/", $postcode)) {
                $validPostcode = TRUE;
            } else {
                $error .= "-De postcode heeft een onjuist formaat. (1234AB)<br>";
            }
        }

        // Check if the shipment_address_house_number field has been filled
        if(empty($_POST['shipment_address_house_number'])) {
            $error .= "-Er is geen huisnummer ingevuld.<br>";
        } else {
            // Save the user input
            $huisnummer = $_POST['shipment_address_house_number'];
            // Check if the number is correct
            if(preg_match("/^[0-9a-zA-Z]{0,}$/", $huisnummer)) {
                $validHuisnummer = TRUE;
            } else {
                $error .= "-Het huisnummer is onjuist ingevuld<br>";
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

        // Write the data into the user table
        if($error != "") {
            $_SESSION['error'] = "Het volgende ging er mis:<br>" . $error;
            $_SESSION['fieldValues'] = array('firstName' => $firstName, 'middleName' => $middleName, 'lastName' => $lastName, 'email' => $email, 'postcode' => $postcode, 'huisnummer' => $huisnummer);
            header("Location: bestellen.php");
            die();
        } elseif($validFirstName && $validMiddleName && $validLastName && $validEmail && $validPostcode && $validHuisnummer) {
            // If every field has been deemed worthy put everything into the database
            // First store the date in the user table
            $query = "INSERT INTO `user`(`username`, `firstName`, `middleName`, `lastName`, `email`, `password`, `phone`) VALUES ('[NoAccount]', ?, ?, ?, ?, '', 0)";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "ssss", $firstName, $middleName, $lastName, $email);
            mysqli_stmt_execute($statement);
            // Get the new userID
            $query = "SELECT LAST_INSERT_ID() AS 'userID'";
            $statement = mysqli_prepare($connection, $query);
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
        }
    }
    //<!--info opslaan in variablen-->
    if(isset($_SESSION['username'])) {
        // Account gegevens ophalen
        $query = "SELECT `userID`, `firstName`, `middleName`, `lastName`, `email`, `addressID` FROM `user` WHERE `username` = ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "s", $_SESSION['username']);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $naam = $result[0]['firstName'];
        $tussenvoegsel = $result[0]['middleName'];
        $achternaam = $result[0]['lastName'];
        $email = $result[0]['email'];
        $addressID = $result[0]['addressID'];
        $userID = $result[0]['userID'];
        $query = "SELECT `postalCode`, `number` FROM `address` WHERE `addressID` = ?";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "i", $addressID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $postcode = $result[0]['postalCode'];
        $huisnummer = $result[0]['number'];
    }               
    // Process order:
    $total = 0;
    foreach($_SESSION['cart'] as $productID => $count) {
        $Query = " 
            SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            QuantityOnHand AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";
         $Statement = mysqli_prepare($connection, $Query);
        mysqli_stmt_bind_param($Statement, "i", $productID);
        mysqli_stmt_execute($Statement);
        $ReturnableResult = mysqli_stmt_get_result($Statement);
        if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
            $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
        } else {
            $Result = null;
        }
        $price = number_format($Result['SellPrice'], 2);
        $total += $price * $count;
    }
    // Write order into db
    if(isset($_SESSION['cart'])) {
        $query = "INSERT INTO `userorder`(`userID`, `total`) VALUES (?, ?)";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($statement, "id", $userID, $total);
        mysqli_stmt_execute($statement);
        $query = "SELECT LAST_INSERT_ID() AS 'orderID'";
        $statement = mysqli_prepare($connection, $query);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $orderID = $result[0]['orderID'];
        foreach($_SESSION['cart'] as $itemID => $amount) {
            $Query = " 
                SELECT SI.StockItemID, 
                (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
                StockItemName,
                QuantityOnHand AS QuantityOnHand,
                SearchDetails, 
                (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
                (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
                FROM stockitems SI 
                JOIN stockitemholdings SIH USING(stockitemid)
                JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
                JOIN stockgroups USING(StockGroupID)
                WHERE SI.stockitemid = ?
                GROUP BY StockItemID";
            $Statement = mysqli_prepare($connection, $Query);
            mysqli_stmt_bind_param($Statement, "i", $itemID);
            mysqli_stmt_execute($Statement);
            $ReturnableResult = mysqli_stmt_get_result($Statement);
            $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
        $sellPrice = number_format($Result['SellPrice'], 2);
            $query = "INSERT INTO `order`(`orderID`, `itemID`, `amount`, `sellPrice`) VALUES (?, ?, ?, ?)";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "iiid", $orderID, $itemID, $amount, $sellPrice);
            mysqli_stmt_execute($statement);
        }
        // Update the amount of stock of the ordered items
        foreach($_SESSION['cart'] as $itemID => $amount) {
            $query = "UPDATE
                        `stockitemholdings`
                    SET 
                    `QuantityOnHand` = CASE WHEN `QuantityOnHand` - ? >= 0 THEN `QuantityOnHand` - ? ELSE 0 END
                    WHERE
                        `StockItemID` = ?";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "iii", $amount, $amount, $itemID);
            mysqli_stmt_execute($statement);
        }
    }
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
                <div class = "container">
                    <h1>Uw bestelling is verwerkt!</h1>
                    <!--overzicht van de producten-->
                    <div class="mandItemsOverzicht">
                        <h1>Producten overzicht:</h1>
                        <?php
                        $totalPrice = 0;
                        if (count($_SESSION['cart'])==0) {print("Er staan geen artikelen in het Winkelwagentje");}
                        foreach($_SESSION['cart'] as $productID => $count) {
                            $Query = " 
                SELECT SI.StockItemID, 
                (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
                StockItemName,
                QuantityOnHand AS QuantityOnHand,
                SearchDetails, 
                (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
                (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
                FROM stockitems SI 
                JOIN stockitemholdings SIH USING(stockitemid)
                JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
                JOIN stockgroups USING(StockGroupID)
                WHERE SI.stockitemid = ?
                GROUP BY StockItemID";
                            $ShowStockLevel = 1000;
                            $Statement = mysqli_prepare($Connection, $Query);
                            mysqli_stmt_bind_param($Statement, "i", $productID);
                            mysqli_stmt_execute($Statement);
                            $ReturnableResult = mysqli_stmt_get_result($Statement);
                            if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
                                $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
                            } else {
                                $Result = null;
                            }
                            $price = number_format($Result['SellPrice'], 2);
                            $productName = $Result['StockItemName'];
                            $totalPrice += $price * $count;
                            if ($count != 0) {
                                //begin div
                                echo("<div id =\"CartItem\">");

                                //echo ID, productnaam,en prijs
                                echo("<div style=\"font-size: 20px;\"><b>$productName</b></div>");
                                echo("p/st: &euro;$price");
                                //echo aantal
                                echo("<br>Aantal:$productID
                                    <div class='confermatieBestelPrijs'><b>&euro;" . $price * $count . "</b></div>
                                    </div>");
                            }
                        }
                        //shipping costs calculation
                        $TheActualTotalPrice = 0;
                        if ($totalPrice <= 50 AND $totalPrice != 0){ //<----condition whether or not to include shipping costs
                            $shippingcosts = 6.50; //<---------------- verzendkosten
                        } else {
                            $shippingcosts = 0;
                        }
                        $TheActualTotalPrice += $shippingcosts + $totalPrice;
                        //echo de kosten onderaan
                        echo ("<div style='padding:8px;border-top: 3px black solid;margin-top: -1px;'>Verzend kosten: <div class='confermatieBestelPrijs'><b>&euro;$shippingcosts</b></div> <hr>");
                        echo ("Totaal prijs: <div class='confermatieBestelPrijs'><b>&euro;$TheActualTotalPrice</b></div></div>");
                        ?>
                    </div>
                    <div class="PersoonlijkeInfoOverzicht">
                        <h1>Persoonlijke Informatie:</h1>
                        <div class="Pinfo">
                            <label>Naam: <?php echo $naam. " ". $tussenvoegsel ." " . $achternaam?></label><br>
                            <label>Postcode: <?php echo $postcode ?></label><br>
                            <label>Huisnummer: <?php echo $huisnummer ?></label><br>
                            <label>E-mail: <?php echo $email ?></label><br>
                        </div>
                    </div>
                    <!--Dit komt niet als er geen producten in de mand staan-->
                    <?php if (count($_SESSION['cart'])!=0) { ?>
                    <div class="confimatieMeldingOverzicht">
                        <h1>confirmatiemail</h1>
                        <p>er is een confirmatiemail is verzonden naar <?php echo $email ?>.
                        Check aub uw mail om uw bestelling te conformeeren.</p>
                        <p id="kleineLettertjes">Als u geen mail heeft ontvangen check nog eens of u het juiste mail-adres heeft opgegeven en check uw SPAM-folder. Als u hierna nog steeds geen mailtje heeft ontvangen neem dan contact met ons op.</p>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    unset($_SESSION['cart']);   
?>