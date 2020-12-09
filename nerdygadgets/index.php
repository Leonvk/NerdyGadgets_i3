<?php
include __DIR__ . "/header.php";
?>






<div method = "get" class="IndexStyle">
    <!--search tile-->
    <div id = "homepage_search_tile">
        <div id = "hompage_search_text">
            <b>Begin met het zoeken <br>van een product:</B>
        </div>
        <form action = "browse.php">
            <input id = home_page_search_submit type="submit" value = "zoek">
            <input id = home_page_search type="text" name="search_string">   
        </form>
    </div>
    <!--catogory tiles-->
    <div id = "homepage_catogory_tiles">
        <h2 class = "home_page_text" >Zoek in categorieën:</h2>
        <?php
        foreach ($HeaderStockGroups as $HeaderStockGroup) {
            ?>
            <div id = "homepage_catogory_tile">
                <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                   class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
            </div>
            <?php
        }
        ?>
    </div>
    <!--sub tiles-->
    <div id = "homepage_sub_tiles">
        <h2 class = "home_page_text"> Bekijk ook deze producten:</h2>
        <?php
        //aantal producten op hoofdpagina:
        for ($i=0;$i<  6  ;$i++){
            echo ("<div id = \"homepage_product_tile\">");
            
            // select which products to showcase
            $productID = rand(1,227); //currently just selects a random product
            
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
                <div class="homepage_image"
                     style="background-image: url('<?php print "Public/StockItemIMG/" . $Result['ImagePath']; ?>'); background-size: 100%; background-repeat: no-repeat; background-position: center;"></div>
            <?php } else if (isset($Result['BackupImagePath'])) { ?>
                <div class="homepage_image"
                     style="background-image: url('<?php print "Public/StockGroupIMG/" . $Result['BackupImagePath'] ?>'); background-size: 200%; background-repeat: no-repeat; background-position: center;"></div>
            <?php }

            
            //display product information
            echo("<div style=\"font-size : 23px;\"><b>$productName</b></div><br>");
            echo("<div style=\"float:right;font-size : 25px; color: #3161c2\"><b>&euro;$price</b></div>");
            echo("</div>");
            //display product page button
            echo("<div id = \"product_tile_button\" ><button type=\"button\" class=\"buttonempty\"> <a class=\"product_tile_button\" href=\"view.php?id=$productID.php\">Bekijk</a></button></div>");
            

            echo ("</div>");
        }?>
            
    
    </div>









<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <!--<div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=93">
                
                
                <div class="TextMain">
                    "The Gu" red shirt XML tag t-shirt (Black) M
                </div>
                <ul id="ul-class-price">

                    <li class="HomePagePrice">€30.95</li>
                </ul>

        </div>
        </a>
        <div class="HomePageStockItemPicture"></div>
    </div>-->
</div>
<?php
include __DIR__ . "/footer.php";
?>

