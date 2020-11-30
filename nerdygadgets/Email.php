<?php
include __DIR__ . "/header.php";
?>

<!--<div class="EmailBox"> -->
    <form action="EmailContactConfirmatie.php" method="post"?>

        <div id="EmailForSubject">
            <label for="EmailAdres">For:</label>
            <input id="EmailAdres" name="EmailBox" value="Nerdy.gadgets@nerdygadgets.nl" disabled> <br><br>

            <label for="EmailOnderwerp">Subject:</label>
            <input id="EmailOnderwerp" name="EmailOnderwerp"><br><br>
        </div>

        <label for="EmailText"></label>
        <textarea id="EmailText" name="EmailText" rows="15" cols="361" style="resize: none"></textarea>

        <div id="SendKnopEmail">
            <input type="submit" value="Send">
        </div>
    </form>
<!-- </div> -->

<?php
include __DIR__ . "/footer.php";
?>