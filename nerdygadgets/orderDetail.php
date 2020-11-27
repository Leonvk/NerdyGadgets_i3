<?php
session_start();
if(!isset($_SESSION['userID'])) {
    header("Location: index.php");
    die();
}
if(!isset($_GET['orderID'])) {
    header("Location: orders.php");
    die();
}
// Get the order information
$userID = $_SESSION['userID'];
$orderID = $_GET['orderID'];
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
$query = "SELECT `userID`, `orderDateTime`, `total` FROM `userorder` WHERE `orderID` = ?";
$statement = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($statement, "i", $orderID);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
if ($result && mysqli_num_rows($result) == 1) {
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
} else {
    $result = NULL;
}
if($result['userID'] != $userID) {
    header("Location: orders.php");
}
include __DIR__ . "/header.php";

// Display all the order information
if($result == NULL) {
    echo("Er is iets foutgegaan. ERROR: invalid orderID");
} else {
    $date = date("d-m-Y", strtotime(substr($result['orderDateTime'], 0, 10)));
    echo("<h4>");
    echo("Bestelnummer: " . $_GET['orderID'] . " / Besteld op: " . $date . " om " . substr($result['orderDateTime'], -8, 5) . " / Totaalprijs: €" . $result['total']);
    echo("</h4>");

    $cart = array();
    $prices = array();
    $query = "SELECT `itemID`, `amount`, `sellPrice` FROM `order` WHERE `orderID` = ?";
    $statement = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($statement, "i", $orderID);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    foreach($result as $row) {
        $cart[$row['itemID']] = $row['amount'];
        $prices[$row['itemID']] = $row['sellPrice'];
    }

    if(count($cart)==0) {
        print("Er zijn geen gegevens beschikbaar over deze bestelling :(");
    }

    foreach($cart as $productID => $count) {
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
        $price = $prices[$productID];
        $productName = $Result['StockItemName'];
        if($count != 0) {
            //begin div
            echo("<div id =\"CartItem\">");
            //load image:
            if(isset($Result['ImagePath'])) { ?>
                <div class="ImgFrameCart" id="ImgFramCart"
                        style="background-image: url('<?php print "Public/StockItemIMG/" . $Result['ImagePath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
            <?php } else if (isset($Result['BackupImagePath'])) { ?>
                <div class="ImgFrameCart" id="ImgFramCart"
                        style="background-image: url('<?php print "Public/StockGroupIMG/" . $Result['BackupImagePath'] ?>'); background-size: 200%; background-repeat: no-repeat; background-position: center;"></div>
            <?php }
            //echo ID, productnaam,en prijs
            echo("<div style=\"font-size: 20px;\"><b>$productName</b></div>");
            echo("$count x ");
            echo("&euro;$price");
            echo("
            <div style=\"text-align:right; font-size:25px;\"><b>&euro;".$price * $count."</b></div>
            </div>");
        }
    }
}
?>
</div>


<?php
include __DIR__ . "/footer.php";
?>
