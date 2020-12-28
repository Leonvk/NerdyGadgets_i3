<?php
include __DIR__ . "/header.php";
?>

<div class="EmailBox">
    <form action="EmailContactConfirmatie.php" method="post"?>

        <div id="EmailForSubject">
            <label for="EmailAdres">Deze email wordt verzonden naar:</label>
            <input id="EmailAdres" name="EmailBox" type="email" value="Nerdy.gadgets@nerdygadgets.nl" disabled style="color: dodgerblue;"> <br><br>

            <label for="EmailVan">Wij kunnen U bereiken via het volgende e-mailadres:</label>
            <input id="EmailVan" name="EmailVan" type="email" required placeholder="voornaam.achternaam@mail.com">

            <label for="EmailOnderwerp">Onderwerp:</label>
            <input id="EmailOnderwerp" name="EmailOnderwerp" required><br><br>
        </div>
        <div id="TextBoxEmail">
        <label for="EmailText"></label>
        <textarea id="EmailText" name="EmailText" required="true" rows="15" style="width : 100%; border-radius: 25px; border: 0px;"></textarea>
        </div>

        <br>
        <input type="submit" value="verzenden" id="SendKnopEmail">
        </div>
    </form>
</div>
<?php
include __DIR__ . "/footer.php";
?>