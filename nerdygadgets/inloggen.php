<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";
?>
<html lang = "en">
<head>
    <title>NerdyGadgets</title>
    <link href = "css/bootstrap.min.css" rel = "stylesheet">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
        }

        h2{
            text-align: center;
            color: #212529;
        }
    </style>
</head>
<body>
    <h2>Log in met je e-mailadress</h2>
      <div class = "container form-signin">
         <?php
            if (isset($_POST['login']) && !empty($_POST['username'])
               && !empty($_POST['password'])) {
               if ($_POST['username'] == 'Test' && $_POST['password'] == 'Test') {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = 'Test';

                  echo 'U heeft een geldende naam en wachtwoord ingevuld!';
               }else {
                  echo 'Onjuiste naam of wachtwoord.';
               }
            }
         ?>
      </div>
      <div class = "container">
         <form class = "form-signin" role = "form"
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
            <input type = "text" class = "form-control"
               name = "username" placeholder = "Naam = Test" required autofocus></br>
            <input type = "password" class = "form-control"
               name = "password" placeholder = "Wachtwoord = Test" required>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" name = "login">Login</button>
            nog geen acctount?  <a href="AccountMaken.php" ><i class="fas fa-accountMaken" style="color:#313161;"></i>  klik hier om te beginnen </a>
         </form>
      </div>
   </body>
</html>