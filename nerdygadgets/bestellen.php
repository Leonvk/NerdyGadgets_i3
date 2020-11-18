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
             <div class = "bestellenKolom3">
                 <label>Voornaam:</label><input type = "text" class = "form-control" name = "shipment_address_first_name" placeholder = "Voornaam" required autofocus>
                 <label>Tussenv:</label><input type = "text" class = "form-control" name = "shipment_address_name_addition" placeholder = "Tussenv." required>
                 <label>Achternaam:</label><input type = "text" class = "form-control" name = "shipment_address_last_name" placeholder = "Achternaam" required><br>
             </div>
             <br>
             <div class = "bestellenKolom2">
                 <label>Postcode:</label><input type = "text" class = "form-control" name = "shipment_address_post_code" placeholder = "Postcode" required>
                 <label>Huisnummer:</label><input type = "text" class = "form-control" name = "shipment_address_house_number" placeholder = "Nr." required>
             </div>
             <br>
             <div class = "bestellenKolom1">
                 <label>E-maildres:</label><input type = "email" class = "form-control" name = "email" placeholder = "example@nerdygadgets.com" required>
             </div>
             <br>
             <div class = "bestellenKolom2">
                 <label>Wachtwoord:</label><input type = "password" class = "form-control" name = "wachtwoord" placeholder = "Wachtwoord" required>
                 <label>Wachtwoord:</label><input type = "password" class = "form-control" name = "wachtwoord" placeholder = "Wachtwoord bevestigen" required><br>
             </div>
             <br>
             <input type="checkbox" name="actieMail" class="actieMail" style="float: none"><label for="reclameMail">Ik zou graag acties via de e-mail willen ontvangen</label><br>
             <button class = "btn btn-lg btn-primary btn-block" type = "submit" name = "login">Doorgaan</button>
         </form>
      </div>
   </body>
</html>
