<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";


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
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$ReturnableResult = mysqli_stmt_get_result($Statement);
if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
} else {
    $Result = null;
}

$moreID = NULL;
if(!empty($_POST['itemID'])) {
    if(!isset($addedItem)) {
        if(isset($_SESSION['cart'][$_GET['id']])) {
           $addedItem = TRUE;
        } elseif(is_numeric($_POST['count']) && $_POST['count'] > 0) {
            // Execute when item gets added
            $_SESSION['cart'][$_POST['id']] = $_POST['count'];
        }
    }
    $addedItem = TRUE;
} elseif(isset($_SESSION['cart'][$_GET['id']])) {
    $addedItem = TRUE;
} else {
    $addedItem = FALSE;
}

//Get Images
$Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$R = mysqli_stmt_get_result($Statement);
$R = mysqli_fetch_all($R, MYSQLI_ASSOC);

if ($R) {
    $Images = $R;
}
?>
<div id="CenteredContent">
    <?php
    if ($Result != null) {
        ?>
        <?php
        if (isset($Result['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $Result['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($Images)) {
                // print Single
                if (count($Images) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $Images[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($Images) >= 2) { ?>
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Left and right controls -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $Result['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $Result["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $Result['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php 
            if ($Result['QuantityOnHand'] <= 1000) {
                print('Voorraad: '. $Result['QuantityOnHand']);
            } 
            else {
                print("Ruime vooraad beschikbaar");
            }
            //get coldroomtemperatures
            if ($Result["StockItemID"] == 220 or $Result["StockItemID"] == 221) {
                $sql = "SELECT Temperature 
                        FROM coldroomtemperatures 
                        ORDER BY ColdRoomTemperatureID DESC 
                        LIMIT 1";
                $conn = mysqli_connect("localhost", "root", "", "nerdygadgets");
                $Statement = mysqli_prepare($conn, $sql);
                mysqli_stmt_execute($Statement);
                $TempResult = mysqli_stmt_get_result($Statement);
                $TempResult = mysqli_fetch_all($TempResult, MYSQLI_ASSOC);
                
                //print_r($TempResult);
                //print_r($TempResult["0"]);
                //print("<br>");
                //print($TempResult["0"]["Temperature"]);
                

                //ik weet ook niet waarom die ["0"] voor de temparatuur moet, maar het moet. de array zit heel raar ofzo
                $temp = $TempResult["0"]["Temperature"];
                print("<div id=\"tempature\">momenteel gekoeld op $temp graden!</div>");
            }
            
            ?>
                
            </div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $Result['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <?php
                        $id = $_GET['id'];
                        if(!$addedItem) {
                            echo("Aantal:
                                <form method=\"post\" action=\"view.php?id=$id\" method=\"post\">
                                <input type=\"hidden\" name=\"id\" value=\"$id\">
                                <select name=\"count\" style=\"width: 100px;\">
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
                                    <option disabled>Grotere aantallen kunnen worden geselecteerd op de winkelmand pagina</option>
                                </select>
                                <input type=\"hidden\" name=\"itemID\" value=\"$id\">
                                <button type=\"submit\" id = \"winkelmandknop\"><i class=\"fas fa-shopping-basket\" style=\"color:white;\"></i> Toevoegen</button>
                                </form><br>");} else {
                                    echo("<button type=\"submit\" id = \"winkelmandknop\"><i class=\"fas fa-check\" style=\"color:white;\"></i> Toegevoegd</button>");
                                }
                                if(isset($productID)){
                                    echo("<form method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$productID\">Aantal: <input type=\"number\" name=\"number\" style=\"width: 100px;\" min=\"1\" autofocus></form></div>");
                                } ?>
                    </div>
                </div>
            </div>
        </div>
        

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $Result['SearchDetails']; ?></p>
        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($Result['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $Result['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>