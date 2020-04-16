<?php
session_start();
use MyChat\Users;
require dirname(__DIR__) . '/Chat/vendor/autoload.php';
if(isset($_POST['logout']) && isset($_POST['id'])){
    $user = new Users();
    $id= $_POST['id'];
    $user->setId($id);

    $user->setLoginStatus(0);
    $user->setLastLogin(date('Y-m-d h:i:s'));
    $user->updateLoginStatus();

    session_unset();
    session_destroy();
    header("Location:auth.php");

}
