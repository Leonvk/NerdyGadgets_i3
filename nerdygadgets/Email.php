<?php
include __DIR__ . "/header.php";
?>

<div class="EmailBox">
    <form action="EmailContactConfirmatie.php" method="post"?>

        <div id="EmailForSubject">
            <label for="EmailAdres">Voor:</label>
            <input id="EmailAdres" name="EmailBox" type="email" value="Nerdy.gadgets@nerdygadgets.nl" disabled style="color: dodgerblue;"> <br><br>

            <label for="EmailVan">Van?</label>
            <input id="EmailVan" name="EmailVan" type="email" required>

            <label for="EmailOnderwerp">Onderwerp:</label>
            <input id="EmailOnderwerp" name="EmailOnderwerp" required><br><br>
        </div>
        <div id="TextBoxEmail">
        <label for="EmailText"></label>
        <textarea id="EmailText" name="EmailText" required="true" rows="15" cols="207" style="resize: none"></textarea>
        </div>

        <input type="submit" value="Send" id="SendKnopEmail">
    </form>
</div>

<?php
include __DIR__ . "/footer.php";
?>