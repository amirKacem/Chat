<?php
session_start();
use MyChat\Users;
use MyChat\Messages;
require dirname(__DIR__) . '/Chat/vendor/autoload.php';
if(!isset($_SESSION['user'])) {
    header("location: auth.php");
}else{

    $users = new Users();
    $currentUser = $_SESSION['user'];

    $voyants = $users->getAllUsers(['type'=>'V']);
    $MsgImpl= new Messages();
    $messages = $MsgImpl->getAllUserMsg();
}

?>
<html>
<head>
    <title>Chat</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<div class="container clearfix">

    <div class="rowChat">
        <div class="col-md-2 col-sm-3" id="peoples">
            <div class="people-list" >
                <div class="search">
                    <input id="search" type="text" placeholder="search" />
                    <i class="fa fa-search"></i>
                </div>

                <ul class="list" id="usersList">



                </ul>
            </div>


        </div>
        <div class="col-md-10 col-sm-9" id="blockChat">
            <div class="chat">
                <div class="chat-header clearfix">
                    <img src="<?= $currentUser->image_path ?>" alt="avatar" width="50" height="50"/>

                    <div class="chat-about">
                        <div class="chat-with"> <?= $currentUser->username ?></div>

                    </div>

                    <form action="logout.php" method="POST" class="formLogout">
                        <input type="hidden" name="id" value="<?= $currentUser->id ?>">
                        <button name="logout" class="btn btn-sm btn-danger">Deconnexion</button>

                    </form>


                </div> <!-- end chat-header -->

                <div class="chat-history">
                    <ul class="message-list">

                    </ul>

                </div> <!-- end chat-history -->

                <div class="chat-message clearfix">
                    <form class="message-form">
                        <input type="hidden" name="user_id" id="user_id" value="<?= $currentUser->id ?>">
                        <textarea name="message-to-send" id="message-to-send" placeholder ="message" rows="3"></textarea>

                        <button>Envoyer</button>
                    </form>

                </div> <!-- end chat-message -->
            </div>
        </div> <!-- end chat -->
    </div>

</div> <!-- end container -->

<script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="js/app.js"></script>

</body>
</html>