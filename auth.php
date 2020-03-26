<?php
session_start();
use MyChat\Users;
require dirname(__DIR__) . '/Chat/vendor/autoload.php';
if(isset($_SESSION['user'])) {
    header("location:/chat");
}
$user = new Users();
if (isset($_POST['login'])) {
    if(isset($_POST['username']) && isset($_POST['password'])){
    $user->setName($_POST['username']);
    $user->setPassword($_POST['password']);
    $user->setLoginStatus(1);
    $user->setLastLogin(date('Y-m-d h:i:s'));
    $userData = $user->login();
    if ($userData) {
        $user->setId($userData->id);
        if ($user->updateLoginStatus()) {
            $key = 'password';
            unset($userData->$key);
            $_SESSION['user'] = $userData;
            header("location: index.php");
        } else {
            $erreur = "Failed to login.";
        }

    }
    }else{
        $erreur =  "Invalid nom d'utilisateur ou Mot de Passe";
    }
}

if(isset($_POST['Register'])) {
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $image = $_POST['image'];
    $user->setPassword($password);
    $user->setEmail($email);
    $user->setName($username);
    $user->setImagePath($image);
    $user->setLoginStatus(1);
    $user->setLastLogin(date('Y-m-d h:i:s'));
    if ($userData = $user->save()) {
        $_SESSION['user'] = $userData;
        header("location:/Chat");
    } else {
        $erreur = "nom d'utilisateur existe déjà";
    }
}
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <link href="assets/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="assets/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>
<body>
<div class="page-wrapper bg-gra-02 p-t-30 p-b-100 font-poppins">
    <div class="wrapper wrapper--w680">

        <div class="card card-4" >
            <div class="tabs">
                <button class="btn btn--green" id="tab1">Inscription</button>
                <button class="btn btn--blue" id="tab2">Connexion</button>
            </div>

            <div class="card-body" id="main-content">
                <div id="Inscription">
                    <hr/>
                    <h2 class="title">Inscription</h2>
                    <form method="POST" id="formRegister">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Pseudo</label>
                                    <input class="input--style-4" type="text" name="username">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Email</label>
                                    <input class="input--style-4" type="email" name="email">
                                </div>
                            </div>

                        </div>

                        <div class="row row-space">



                            <div class="input-group" style="margin: auto;text-align: center;">
                                <label class="label">Image</label>
                                <div class="p-t-10">
                                    <label class="radio-container m-r-45">
                                        <input type="radio" checked="checked" name="image" value="female">
                                        <img src="img/female.png" alt="female">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-container m-r-45">
                                        <input type="radio" name="image" value="chat">
                                        <img src="img/chat.png" alt="chat">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-container m-r-45">
                                        <input type="radio" name="image" value="woman">
                                        <img src="img/woman.png" alt="woman">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-container m-r-45">
                                        <input type="radio" name="image" value="conversation">
                                        <img src="img/conversation.png" alt="conversation">
                                        <span class="checkmark"></span>
                                    </label>

                                </div>
                            </div>


                        </div>
                        <div class="row row-space m-t-20">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Mot De Passe</label>
                                    <input class="input--style-4" type="password" name="password" id="pass">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label">Confirm Mot De Passe</label>
                                    <input class="input--style-4" type="password" name="cpassword">
                                </div>
                            </div>
                        </div>
                        <div class="p-t-15" style="text-align: center;">
                            <button name="Register" class="btn btn--radius-2 btn--blue" type="submit">Envoyer</button>
                        </div>
                    </form>
                </div>
                <div id="Connexion">
                    <hr/>
                    <h2 class="title">Connexion</h2>
                    <form method="post" id="formLogin">
                        <div class="input-group">
                        <label for="username" class="label">Pseudo</label>
                        <input class="input--style-4 my-2"  type="text" name="username" id="username">
                        <label for="password" class="label">Mot De Passe</label>
                        <input class="input--style-4 my-2"  type="password" name="password">

                        </div>
                        <?php
                        if(isset($erreur)){
                            echo "  <p class='text-danger text-center p-t-20 '>{$erreur}</p>";
                        }
                        ?>
                        <div class="p-t-15" style="text-align: center;">
                            <button class="btn btn--radius-2 btn--blue" name="login">Connexion</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jquery JS-->

<script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<!-- Main JS-->
<script src="js/global.js"></script>



</body>
</html>
