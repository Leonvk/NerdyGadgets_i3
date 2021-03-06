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
            if (count($_SESSION['cart'])==0) {print("<h1><b>Uw Winkelwagentje is momenteel nog leeg</b></h1>
                <br>U kunt producten toevoegen door op de 'toevoegen' knop te klikken wanner u een product bekijkt");}
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

            //----------------------// coupon code korting (start) \\----------------------\\
            //default discount values
            $amount_discount = 0;
            $percentage_discount = 0;
            $code = "";

            //apply coupon code if applicable.
            if (isset($_POST['coupon_code'])) {
            
                $connection = mysqli_connect("localhost", "root", "", "nerdygadgets");

                //get code from post
                $code = $_POST['coupon_code'];

                //check if coupon exists and is active in DB
                $query = " 
                    SELECT percentage, amount
                    FROM coupon_codes 
                    WHERE active = 1 and code = ? and price <= ?";
                
                $statement = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($statement, "sd",$code,$totalPrice);
                mysqli_stmt_execute($statement);

                //apply coupon code
                $ReturnableResult = mysqli_stmt_get_result($statement);
                //no idea why I used a foreach here. there must an easier way for this. but I'm tired and this works...
                foreach ($ReturnableResult as $key => $value) {
                    $amount_discount = $value['amount'];
                    $percentage_discount = $value['percentage'];
                }

            }
            //----------------------\\ coupon code korting (end) //----------------------\\

            //----------------------// costs calculation (start) \\----------------------\\
            $TheActualTotalPrice = 0;
            if ($totalPrice <= 50){ //free shipping costs when the price is above ...
                $shippingcosts = 6.50; //shipping costs
            } else {
                $shippingcosts = 0;
            }
            
            //calculate the actual price. (the price which will be requested from the ideal stuff)
            $TheActualTotalPrice += $shippingcosts + $totalPrice;
            $TheActualTotalPrice = ($TheActualTotalPrice * (1 - ($percentage_discount / 100)))  - $amount_discount;

            if ($TheActualTotalPrice < 0) {$TheActualTotalPrice = 0;} //just in case

            //----------------------\\ costs calculation (end) //----------------------\\

            ?>
        </div>

        <!-- -----------// side menu shopping cart (start) \\----------- -->
        <div id="side_menu_shoppingcart">
        <!--side menu shopping cart price-->
        <div id="window_background">
            <table style="font-size:20px; width: 50%; border-spacing: 50px;">
                <td>totaal artikelen:</td><td><?php echo("&euro;".number_format($totalPrice,2));?><br></td><tr>
                <td>verzendkosten:</td><td><?php echo("&euro;".number_format($shippingcosts,2));?><br></td><tr>
                <?php if ($amount_discount > 0) {echo("<td>korting:</td><td>&euro;-".number_format($amount_discount,2)."<br></td><tr>");}?>
                <?php if ($percentage_discount > 0) {echo("<td>korting:</td><td>".$percentage_discount."%<br></td><tr>");}?>
                <td><b>totaal:</b></td><td><b><?php echo("&euro;".number_format($TheActualTotalPrice,2));?></b><br></td><tr>
            </table>
            <br><br>
            <?php if (count($_SESSION['cart'])==0) {print("U kunt nog niet bestellen omdat er geen artikelen in het Winkelwagentje staan");} else{ ?>
            <button type="button" class="buttonempty"> <a class="bestelbutton" href="bestellen.php">Bestellen</a></button>
            <?php } ?>
        </div>
        

        <!--side menu shopping cart coupon code-->
        <div id="window_background">
            <h3> coupon code </h3> <br>
            
            <form action="winkelwagen.php" method = "post">
                <input id = home_page_search type="text" value = "<?php print("$code");?>" name="coupon_code" style="float:left;">  
                <input id = coupon_submit_button type="submit" value = "toepassen" style="width:auto;float:left;">
            </form>
            <?php
            if (isset($_POST['coupon_code']) and $amount_discount == 0 and $percentage_discount == 0) {
                if ($_POST['coupon_code'] != "") {print("<br><br><br>De ingevulde couponcode is onjuist");}
            } else if (isset($_POST['coupon_code'])) {
                print("<br><br><br>De ingevulde couponcode is momenteel actief");
            }
            ?>
        </div>
        </div>
        <!-- -----------\\ side menu shopping cart (end) //----------- -->

        <!--bottom of shopping cart-->
        <div class="totaalBedrag">
            <?php echo("<br>Totaal prijs: &euro;$totalPrice"); ?>
            
        </div>
    </form>
</div>


<br><br>
<br><br>

<?php
include __DIR__ . "/footer.php";
?>

