<?php
session_start();
use MyChat\Users;
use MyChat\Accounts\Admin;
require dirname(__DIR__) . '/vendor/autoload.php';
if(isset($_SESSION['admin'])) {
    header("location:./");
}
$admin = new Admin();
if (isset($_POST['login'])) {
    if(isset($_POST['username']) && isset($_POST['password'])){
        $admin->setName($_POST['username']);
        $admin->setPassword($_POST['password']);
        $admin->setLoginStatus(1);
        $admin->setLastLogin(date('Y-m-d h:i:s'));
        $admin->setType('A');
        $userData = $admin->login();
        if ($userData) {
            $admin->setId($userData->id);
            if ($admin->updateLoginStatus()) {
                $key = 'password';
                unset($userData->$key);

                $_SESSION['admin'] = $userData;
                unset($_SESSION['erreur']);
                header("location:./");
            }

        }else {
            $_SESSION['erreur']  =  "Invalid nom d'utilisateur ou Mot de Passe";
            header("location:./");
        }
    }else{
        $_SESSION['erreur']  =  "Invalid nom d'utilisateur ou Mot de Passe";
        header("location:./");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Admin Connexion</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet"/
  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="assets/css/style.css" />

</head>

</head>
  <body class="bg-light-gray" id="body">
      <div class="container d-flex flex-column justify-content-between vh-100">
      <div class="row justify-content-center mt-5">
        <div class="col-xl-5 col-lg-6 col-md-10">
          <div class="card">

            <div class="card-body p-5">

              <h4 class="text-dark mb-5 text-center">Connexion</h4>
              <form method="POST" >
                <div class="row">
                  <div class="form-group col-md-12 mb-4">
                    <input type="text" name="username" class="form-control input-lg" id="email" placeholder="Nom d'utilisateur" required>
                  </div>
                  <div class="form-group col-md-12 ">
                    <input type="password" name="password" class="form-control input-lg" id="password" placeholder="Mot de passe" required>
                  </div>

                    <button name='login' type="submit" class="btn btn-lg btn-primary btn-block mb-4">Connexion</button>

                  </div>
                  <?php if(isset($_SESSION['erreur'])){
                      echo "<p class='text-center' style='color:red'>".$_SESSION['erreur']."</p>";
                  }
                  ?>
                </div>
              <div>


              </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
</body>
</html>