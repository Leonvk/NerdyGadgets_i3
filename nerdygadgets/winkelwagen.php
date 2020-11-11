<?php
include __DIR__ . "/header.php";
?>

<div>
    <?php 
    if(isset($_POST['delete'])) {
        unset($_SESSION['cart']);
    } else {
        print_r($_SESSION['cart']);
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

