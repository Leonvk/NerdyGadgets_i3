<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";

//functions winkelmand
function add($id){
    $getal = $_SESSION['cart'][$id];
    $getal++;
    $_SESSION['cart'][$id] = $getal;
}
function remove($id){
    $getal = $_SESSION['cart'][$id];
    if($getal > 0) {
        $getal--;
        $_SESSION['cart'][$id] = $getal;
    }
}
?>
<div class="wrapperWinkelmand">
    <h1>Winkelwagentje</h1>
    <br>
    <form action="winkelwagen.php" method="post">
        <div class="mandItemsOverzicht">
            <!--komt in een foreach loop-->
            <?php
            $totalPrice = 0;
            foreach($_SESSION['cart'] as $productID => $count) {
                $Query = " 
                SELECT SI.StockItemID, 
                (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
                StockItemName,
                QuantityOnHand AS QuantityOnHand,
                SearchDetails, 
                (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
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
                $totalPrice += $price;
                echo("<div> (id=$productID) $productName - &euro;$price Aantal:$count <button onclick='add($productID)'>+</button><button onclick='remove($productID)'>-</button></div><br>");
            }
            if(isset($_POST["coupons"])) {
                $totalPrice = (100-$_POST["coupons"])/100*$totalPrice;
            }
            $totalPrice = number_format($totalPrice,2);
            ?>
        </div>
        <div class="couponOverzicht">
            <p>Couponcode (% korting op het moment)</p>
            <input type="number" value="" name="coupons" class="couponNumber">
        </div>
        <div class="totaalBedrag">
            <?php echo("<br>Totaal prijs: &euro;$totalPrice"); ?>
        </div>
        <input type="submit" name="submit" value="Betaal pagina">
        <input type="checkbox" name="actieMail" class="actieMail"><label for="reclameMail">Ik zou graag acties via de e-mail willen ontvangen</label>
    </form>
</div>

<div>
    <?php 
    if(isset($_POST['delete'])) {
        unset($_SESSION['cart']);
    }
    ?>
    <br>
    <form method="post">
        <input type="submit" name="delete" value="Winkelwagen leegmaken">
    </form>
</div>

<?php
include __DIR__ . "/footer.php";
?>

