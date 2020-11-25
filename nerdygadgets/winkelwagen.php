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
    if($_POST['number'] > 0) {
        $_SESSION['cart'][$_POST['id']] = $_POST['number'];
        $moreID = NULL;
    } else {

    }
}

function remove($item) {
    unset($_SESSION['cart'][$item]);
}

if(array_key_exists('remove', $_POST)) {
    remove($_POST['id']);
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
                    //begin div
                    echo("<div id =\"CartItem\">");
                    //load image:
                    if (isset($Result['ImagePath'])) { ?>
                        <div class="ImgFrameCart" id="ImgFramCart"
                             style="background-image: url('<?php print "Public/StockItemIMG/" . $Result['ImagePath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
                    <?php } else if (isset($Result['BackupImagePath'])) { ?>
                        <div class="ImgFrameCart" id="ImgFramCart"
                             style="background-image: url('<?php print "Public/StockGroupIMG/" . $Result['BackupImagePath'] ?>'); background-size: 200%; background-repeat: no-repeat; background-position: center;"></div>
                    <?php }
                    //echo ID, productnaam,en prijs
                    echo("<div style=\"font-size: 20px;\"><b>$productName</b></div>");
                    
                    //echo("<div><form method=\"post\" id=\"DeleteButton\"><input type=\"hidden\" name=\"id\" value=\"$productID\"><input type=\"submit\" name=\"remove\" value=\"\"><i class=\"fa fa-trash\" style=\"position: relative;left: 10px;bottom:35px;\"></i></form></div>");
                    // Delete button //
                    echo("
                    <div style=\"float: right;\">
                    <form method=\"post\">
                    <input type=\"hidden\" name=\"id\" value=\"$productID\">
                    <button type=\"submit\" name=\"remove\" value=\"\"  id=\"DeleteButton\";>
                    <i class=\"fa fa-trash\"></i></button>
                    </form>
                    </div>");
                    echo("&euro;$price");
                    //echo aantal
                    if($moreID != $productID) {echo("<br>Aantal:
                    <form method=\"post\" action=\"winkelwagen.php\"><input type=\"hidden\" name=\"id\" value=\"$productID\">
                    <select name=\"count\" style=\"width: 100px;\" vertical-align: -10px; height: 35px;\" onchange=\"this.form.submit()\">
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
                    </form>
                    <div style=\"text-align:right; font-size:25px;\"><b>&euro;".$price * $count."</b></div>
                    </div>");} else {
                        echo("<br>Aantal:<form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$productID\"><input type=\"number\" name=\"number\" style=\"width: 100px;\" min=\"1\" autofocus></form>
                              <div style=\"text-align:right; font-size:25px;\"><b>&euro;".$price * $count."</b></div></div>");
                    }
                    


                } else {
                    unset($_SESSION['cart'][$productID]);
                }
            }
            if(isset($_POST["coupons"])) {
                $totalPrice = (100-$_POST["coupons"])/100*$totalPrice;
            }

            //shipping costs calculation
            $TheActualTotalPrice = 0;
            if ($totalPrice <= 50){ //<----condition whether or not to include shipping costs
                $shippingcosts = 6.50; //<---------------- verzendkosten
            } else {
                $shippingcosts = 0;
            }    
            $TheActualTotalPrice += $shippingcosts + $totalPrice 

            ?>
        </div>
        <!--side menu shopping cart price-->
        <div class="Bestellen" id="window_background">
            <table style="font-size:20px; width: 50%; border-spacing: 50px;">
                <td>totaal artikelen:</td><td><?php echo("&euro;".number_format($totalPrice,2));?><br></td><tr>
                <td>verzendkosten:</td><td><?php echo("&euro;".$shippingcosts);?><br></td><tr>
                <td><b>totaal:</b></td><td><b><?php echo("&euro;".number_format($TheActualTotalPrice,2));?></b><br></td><tr>
            </table>
            <br><br>
            <button type="button" class="buttonempty"> <a class="bestelbutton" href="bestellen.php">Bestellen</a></button>
        </div>
        
        <!---------------- super slechte fix, MOET nog ff beter gedaan worden, maar css is gemeen aan het doen --------------->
        <br><br><br><br><br><br><br><br><br><br><br><br><br>
        <!---------------- super slechte fix, MOET nog ff beter gedaan worden, maar css is gemeen aan het doen --------------->

        <!--side menu shopping cart coupon code-->
        <div  id="window_background">
            <h3> coupon code </h3>
            <form action="winkelwagen.php">
                <input id = home_page_search_submit type="submit" value = "toepassen" style="width:auto;">
                <input id = home_page_search type="text" name="search_string"> 
            </form>
        </div>

        <!--bottom of shopping cart-->
        <div class="totaalBedrag">
            <?php echo("<br>Totaal prijs: &euro;$totalPrice"); ?>
            
        </div>
        <!-- ----------< delete shoppingcart >----------
        <div class="wrapperWinkelmand2">
            <form method="post">
                <input type="submit" name="delete" value="Winkelwagen leegmaken" id = delete_shopping_cart style="background-color: #00000000 ">
            </form>
        </div>
        -->
    </form>
</div>


<br><br>
<br><br>

<?php
include __DIR__ . "/footer.php";
?>

