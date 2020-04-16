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
                    <?php  foreach ($voyants as $user) {
                        if($user->loginStatus==0){
                            $status  ='offline';
                        }else{
                            $status = 'online';
                        }
                        ?>


                    <?php } ?>


                </ul>
            </div>

                <a href="https://www.lumierevoyance.com/forfaits-voyance/" target="_blank">
                    <img class="forfait" src="img/forfaitEco.jpg" alt="FORFAIT" >
                </a>

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
    <footer>

        <div class="footer-content">

            <div class="col3 col-sm-12">

                <div id="numeros">
                    <div class="block-num">
                        <img src="img/su.png" alt="drapeau suisse" >
                        <a href="tel:0901858858" class="btn-su">0901 858 858 (2Fr/min)</a>
                    </div>
                    <div class="block-num">
                        <img src="img/be.png" alt="drapeau belgique">
                        <a href="tel:090336288" class="btn-be">0903 36 288  (1.5€/min)</a>
                    </div>
                </div>
            </div>
            <div class="col9 col-sm-12">
                <div class="col6">
                    <a href="tel:0170956898" class="banner"><img src="img/audiotel.jpg" alt="voyance audiotel"></a>
                </div>
                <div class="col6">
                    <a href="tel:0890100015" class="banner"><img src="img/privée.jpg" alt="voyance privée"></a>
                </div>
                <div class="clear"></div>


            </div>

            <div class="clear"></div>
        </div>
    </footer>
</div> <!-- end container -->

<script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
<script src="js/app.js"></script>

</body>
</html>