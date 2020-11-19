<?php
/*include __DIR__ . "/header.php";*/
//header
session_start();
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
                <a href="inloggen.php" class="HrefDecoration"><i class="fa fa-sign-in" style="color:#ffffff;"></i>  Inloggen</a>
            </li>
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
                        if ($totalPrice <= 50){ //<----condition whether or not to include shipping costs
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
                    <!--info opslaan in variablen-->
                    <?php
                    $naam = $_POST["shipment_address_first_name"];
                    ?>
                    <div class="PersoonlijkeInfoOverzicht">
                        <h1>Persoonlijke Informatie:</h1>
                        <div class="Pinfo">
                            <label>Naam: <?php echo $naam?></label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>