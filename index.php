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
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>
<link rel="stylesheet" href="css/style.css">

</head>
<body>
<div class="container clearfix">

    <div class="rowChat">
        <div class="col-3" id="peoples">
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
        </div>
        <div class="col-9" id="blockChat">
            <div class="chat">
                <div class="chat-header clearfix">
                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg" alt="avatar" />

                    <div class="chat-about">
                        <div class="chat-with"> <?= $currentUser->username ?></div>
                        <form action="logout.php" method="POST">
                            <input type="hidden" name="id" value="<?= $currentUser->id ?>">
                        <button name="logout" class="btn btn-sm btn-danger">Deconnexion</button>

                        </form>
                    </div>
                    <i class="fa fa-star"></i>

                </div> <!-- end chat-header -->

                <div class="chat-history">
                    <ul class="message-list">
                    <?php

                            foreach ($messages as $message) {

                                if($message->loginStatus==1){
                                    $status="online";
                                }else{
                                    $status="offline";
                                }
                                if ($message->type == "V") {
                                    $result = "<li>
                            <div class='message-data'>
                                <span class='message-data-name'>
                                    <img src='{$message->image_path}' class='chat-img' alt='test'>
                                    <i class='fa fa-circle {$status}'></i>  {$message->username}</span>
                                <span class='message-data-time'>{$message->date_send}</span>
                            </div>
                            <div class='message my-message'>
                                {$message->content}
                            </div>
                        </li>";
                                } else {
                                    $result = "
                        <li class='clearfix' >
                            <div class='message-data align-right' >
                                <span class='message-data-time' > {$message->date_send} </span > &nbsp; &nbsp;
                                <span class='message-data-name' >{$message->username}</span > <i class='fa fa-circle {$status}' ></i >
                                <img src = '{$message->image_path}' class='chat-img' alt = 'test' >
                            </div >
                            <div class='message other-message float-right' >
                             {$message->content}
                            </div >";
                                }
                            echo $result;
                            }
?>
                    </ul>

                </div> <!-- end chat-history -->

                <div class="chat-message clearfix">
                    <form class="message-form">
                        <input type="hidden" name="user_id" id="user_id" value="<?= $currentUser->id ?>">
                    <textarea name="message-to-send" id="message-to-send" placeholder ="message" rows="3"></textarea>

                    <button>Envoye</button>
                    </form>

                </div> <!-- end chat-message -->
            </div>
        </div> <!-- end chat -->
    </div>
    <footer>
        <div class="footer-content">
        <img src="img/audiotel.jpg" alt="voyance audiotel">
        <img src="img/privée.jpg" alt="voyance audiotel">
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
