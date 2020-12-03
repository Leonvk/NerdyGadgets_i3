<?php
include __DIR__ . "/header.php";
?>
<div id="EmailRetourBericht">
    <h4>Wij hebben een bericht ontvangen van <?php echo ($_POST["EmailVan"])?>.<br>
        Met als onderwerp <?php echo ($_POST["EmailOnderwerp"]) ?><br><br>
    Als u binnen 5 werkdagen nog geen antwoord van ons heeft ontvangen neem dan telefonisch contact met ons op.<br>
    Wij staan elke werkdag van 9 tot 18 klaar om u verder te helpen</h4>

<?php
include __DIR__ . "/footer.php";
?>
