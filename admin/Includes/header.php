<?php
session_start();
use MyChat\Accounts\Admin;
use MyChat\Users;
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
$static_root ="http://localhost/chat".DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR;
if(!isset($_SESSION['admin'])) {
    header("location:connexion.php");
}else{
    $currentUser = $_SESSION['admin'];
}
if (isset($_POST['logout']) && isset($_POST['id'])) {
    $admin = new \MyChat\Accounts\Admin();
    $id = $_POST['id'];
    $admin->setId($id);

    $admin->setLoginStatus(0);
    $admin->setLastLogin(date('Y-m-d h:i:s'));
    $admin->updateLoginStatus();

    session_unset();
    session_destroy();
    header("Location:./");

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title> Admin Dashboard </title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet"/>
    <link href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="assets/css/switcher.css">
    <!-- style CSS -->
    <link id="sleek-css" rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />

</head>
