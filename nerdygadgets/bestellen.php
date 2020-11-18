<?php
//Naam/Tussenv/Achternaam
//Postcode/Huisnr
//e-mail
//tel
//
//account aanmaken? ww
include __DIR__ . "/header.php";
?>
      <div class = "container form-signin">
      </div>
      <div class = "container">
          <h2>Kassa</h2>
         <form class = "form-signin" role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
             <div class = "bestellenKolom1">
                Naam:<br>
                <input type = "text" class = "form-control" name = "shipment_address_first_name" placeholder = "Voornaam" required autofocus>
                <input type = "text" class = "form-control" name = "shipment_address_name_addition" placeholder = "Tussenv." required>
                <input type = "text" class = "form-control" name = "shipment_address_last_name" placeholder = "Achternaam" required><br>
             </div>
             <div class = "bestellenKolom2">
                 Postcode:<br>
                 <input type = "text" class = "form-control" name = "shipment_address_post_code" placeholder = "Postcode" required>
                 Huisnummer:<br>
                 <input type = "text" class = "form-control" name = "shipment_address_house_number" placeholder = "Nr." required>
             </div>
             <div class = "bestellenKolom3">
                 E-maildres:<br>
                 <input type = "email" class = "form-control" name = "email" placeholder = "example@nerdygadgets.com" required>
                 Wachtwoord:<br>
                 <input type = "password" class = "form-control" name = "wachtwoord" placeholder = "Wachtwoord" required>
                 <input type = "password" class = "form-control" name = "wachtwoord" placeholder = "Wachtwoord bevestigen" required><br>
             </div>
             <input type="checkbox" name="actieMail" class="actieMail"><label for="reclameMail">Ik zou graag acties via de e-mail willen ontvangen</label><br>
             <button class = "btn btn-lg btn-primary btn-block" type = "submit" name = "login">Doorgaan</button>
         </form>
      </div>
   </body>
</html>
