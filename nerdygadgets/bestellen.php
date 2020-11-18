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
         <form class = "form-signin" role = "form"
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
             <div class = "Naam">
             Naam<br>
             <input type = "text" class = "form-control"
               name = "Voornaam" placeholder = "Voornaam" required autofocus>
             <input type = "text" class = "form-control"
                    name = "Tussenv" placeholder = "Tussenv." required>
             <input type = "test" class = "form-control"
                    name = "Achternaam" placeholder = "Achternaam" required><br>
             </div>
             <div class = "Postcode">
             Postcode<br>
                 <input type = "text" class = "form-control"
                        name = "Postcode" placeholder = "Postcode" required>
             </div>
             <div class = "Huisnummer">
             Huisnummer<br>
                 <input type = "text" class = "form-control"
                    name = "Huisnummer" placeholder = "Nr." required>
             </div>
         </form>
      </div>
   </body>
</html>