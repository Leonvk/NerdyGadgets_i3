<?php
include __DIR__ . "/header.php";
?>

<div method = "get" class="IndexStyle">
    <!--settings menu-->
    <div>
        
    </div>
    <!--load items-->
        <?php
        //aantal producten op hoofdpagina:
        for ($i=1;$i<  228  ;$i++){
            echo ("<div id = \"admin_product_tile\">");
            
            // select which products to showcase
            $productID = $i; //currently just selects a random product
            
            //get data from product database
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
            //product information
            $price = number_format($Result['SellPrice'], 2);
            $productName = $Result['StockItemName'];
            
            echo("<div style=\"width:100%;height:80%\">");
            //display product image
            if (isset($Result['ImagePath'])) { ?>
                <div class="admin_page_image"
                     style="background-image: url('<?php print "Public/StockItemIMG/" . $Result['ImagePath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
            <?php } else if (isset($Result['BackupImagePath'])) { ?>
                <div class="admin_page_image"
                     style="background-image: url('<?php print "Public/StockGroupIMG/" . $Result['BackupImagePath'] ?>'); background-size: 200%; background-repeat: no-repeat; background-position: center;"></div>
            <?php }

            
            //display product information
            echo("<div style=\"font-size : 13px;\"><b>$productName</b></div><br>");
            echo("<div style=\"float:left;font-size : 15px; color: #3161c2\"><b>&euro;$price</b></div>");
            echo("</div>");
            //display edit button
            echo("<div id = \"admin_product_tile_button\" >
            <button type=\"button\" class=\"buttonempty\"> 
            <a class=\"admin_product_tile_button\" href=\"view.php?id=$productID.php\">Bewerk</a></button></div>");
            

            echo ("</div>");
        }?>
</div>
