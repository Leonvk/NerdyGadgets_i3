<?php
session_start();
if(!isset($_SESSION['userID'])) {
    header("Location: index.php");
    die();
}
include __DIR__ . "/header.php";

// Get alle the orders from the user
$userID = $_SESSION['userID'];
$connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($connection, 'latin1');
$query = "SELECT `orderID`, `orderDateTime`, `total` FROM `userorder` WHERE `userID` = ?";
$statement = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($statement, "i", $userID);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
if ($result && mysqli_num_rows($result) == 0) {
    $result = NULL;
} else {
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
// Loop through all the orders from user and display them
if($result == NULL) {
    echo("Je hebt nog geen bestellingen. *cricket noises*");
} else {
    echo("<ul>");
    foreach($result as $order) {
        $date = date("d-m-Y", strtotime(substr($order['orderDateTime'], 0, 10)));
        echo("<li>");
        echo("Bestelnummer: " . $order['orderID'] . " / Besteld op: " . $date . " om " . substr($order['orderDateTime'], -8, 5) . " / Totaalprijs: €" . $order['total'] . " / <a href=\"orderDetail.php?orderID=" . $order['orderID'] . "\">Meer informatie</a>");
        echo("</li>");
    }
    echo("</ul>");
}
?>



<?php
include __DIR__ . "/footer.php";
?>
