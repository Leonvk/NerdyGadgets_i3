<?php
//Naam/Tussenv/Achternaam
//Postcode/Huisnr
//e-mail
//tel
//
//account aanmaken? ww
?>
<html lang = "en">
<head>
    <title>NerdyGadgets</title>
    <link href = "css/bootstrap.min.css" rel = "stylesheet">
</head>
<body>
    <h2>Kassa</h2>
      <div class = "container form-signin">
      </div>
      <div class = "container">
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