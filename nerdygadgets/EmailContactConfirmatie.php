<?php
include __DIR__ . "/header.php";
?>

<h1>Wij hebben uw bericht ontvangen</h1>

<h5>Wij gaan zo snel mogelijk aan de slag om uw vragen te beantwoorden <br><br>
U kunt binnen 5 werkdagen een reactie van ons verwachten. <br>
Als u binnen 5 werkdagen nog niet van ons te horen heeft gekregen
    neem dan contact met ons op via het telefoon nummer: 030 123 12 34 <br>
<br> Hieronder kunt u zien wat u heeft verzonden.</h5>

<div class="EmailHerhaling">
    <form action="Email.php" method="get">
        <?php
        print ($_POST["EmailOnderwerp"]);
        ?>
        <br><br>
        <?php
        echo ($_POST["EmailText"]);
        ?>
    </form>
</div>

<?php
include __DIR__ . "/footer.php";
?>