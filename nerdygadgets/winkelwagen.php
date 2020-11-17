<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";
$moreID = NULL;
if(array_key_exists('count', $_POST)) {
    $moreID = NULL;
    if($_POST['count'] != "more") {
        $_SESSION['cart'][$_POST['id']] = $_POST['count'];
    } else {
        $moreID = $_POST['id'];
    }
}

if(array_key_exists('number', $_POST)) {
    $_SESSION['cart'][$_POST['id']] = $_POST['number'];
    $moreID = NULL;
}
 
if(array_key_exists('delete', $_POST)) {
    $_SESSION['cart'] = array();
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
                $totalPrice += $price  * $count;
                if($count != 0) {
                    //load image:
                    if (isset($Result['ImagePath'])) { ?>
                        <div class="ImgFrameCart" id="ImgFramCart"
                            style="background-image: url('<?php print "Public/StockItemIMG/" . $Result['ImagePath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
                    <?php } else if (isset($Result['BackupImagePath'])) { ?>
                        <div class="ImgFrameCart" id="ImgFramCart"
                            style="background-image: url('<?php print "Public/StockGroupIMG/" . $Result['BackupImagePath'] ?>'); background-size: 200%; background-repeat: no-repeat; background-position: center;"></div>
                    <?php }
                
                    echo("<div id =\"CartItem\"> nr:$productID  $productName <br> &euro;$price");
                    if($moreID != $productID) {echo("Aantal:
                    <form method=\"post\" action=\"winkelwagen.php\"><input type=\"hidden\" name=\"id\" value=\"$productID\">
                    <select name=\"count\" style=\"width: 100px;\" onchange=\"this.form.submit()\">
                        <option value=\"1\">1</option>
                        <option value=\"2\">2</option>
                        <option value=\"3\">3</option>
                        <option value=\"4\">4</option>
                        <option value=\"5\">5</option>
                        <option value=\"6\">6</option>
                        <option value=\"7\">7</option>
                        <option value=\"8\">8</option>
                        <option value=\"9\">9</option>
                        <option value=\"10\">10</option>
                        <option value=\"more\">meer...</option>
                        <option value=\"$count\" selected hidden>$count</option>
                    </select>
                    </form><br></div>");} else {
                        echo("<form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$productID\">Aantal: <input type=\"number\" name=\"number\" style=\"width: 100px;\"></form>");
                    }
                } else {
                    unset($_SESSION['cart'][$productID]);
                }
            }
            if(isset($_POST["coupons"])) {
                $totalPrice = (100-$_POST["coupons"])/100*$totalPrice;
            }
            $totalPrice = number_format($totalPrice,2);
            ?>
        </div>
        <div class="couponOverzicht">
            <p>Couponcode (% korting op het moment)</p>
            <form method="post">
                <input type="number" value="0" max="99" min="0" name="coupons" class="couponNumber"><br><br>
                <input type="checkbox" name="actieMail" class="actieMail"><label for="reclameMail">Ik zou graag acties via de e-mail willen ontvangen</label><br>
                <input class="winkelbutton" type="submit" name="submit" value="Verder naar bestellen">
                
            </form>
        </div>
        <div class="totaalBedrag">
            <?php echo("<br>Totaal prijs: &euro;$totalPrice"); ?>
            
        </div>
        <div class="reclameMail">
            <!--<input type="checkbox" name="actieMail" class="actieMail"><label for="reclameMail">Ik zou graag acties via de e-mail willen ontvangen</label>
        -->
        </div>
    </form>
</div>

<div class="wrapperWinkelmand2">
    <br>
    <form method="post">
        <input class="winkelbutton" type="submit" name="delete" value="Winkelwagen leegmaken">
    </form>
</div>

<?php
include __DIR__ . "/footer.php";
?>

