<?php
include __DIR__ . "/header.php";
?>
<div class="wrapperWinkelmand">
    <h1>Winkelwagentje</h1>
    <br>
    <form action="winkelwagen.php" method="post">
        <div class="mandItemsOverzicht">
            <!--komt in een foreach loop-->
            <div><label for="1">item 1</label><input name="1" type="number" min="0" class="mandItem"></div><br>
            <div><label for="2">item 2</label><input name="2" type="number" min="0" class="mandItem"></div><br>
            <div><label for="3">item 3</label><input name="3" type="number" min="0" class="mandItem"></div>
            <!--tot hier-->
        </div>
        <div class="couponOverzicht">
            <p>couponcode</p>
            <input type="number" value="1" name="coupons" class="couponNumber" readonly>
        </div>
            <input type="checkbox" name="reclameMail" class="reclameMail"><label for="reclameMail">Ik zou graag reclame via de e-mail willen ontvangen</label>
            <input type="submit" name="submit" value="Betaal pagina">
    </form>
</div>

<div>
    <?php 
    if(isset($_POST['delete'])) {
        unset($_SESSION['cartID']);
    } else {
        print_r($_SESSION['cartID']);
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

