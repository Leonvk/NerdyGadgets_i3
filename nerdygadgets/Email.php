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
        <textarea id="EmailText" name="EmailText" rows="15" cols="360" style="resize: none"></textarea>
        <input type="submit" value="Send" id="SendKnopEmail">
    </form>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<!-- </div> -->

<?php
include __DIR__ . "/footer.php";
?>