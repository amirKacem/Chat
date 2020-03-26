<?php
namespace MyChat\Api;
header("Access-Control-Allow-Origin: http://localhosts");

require dirname(dirname(__DIR__) ). '/vendor/autoload.php';
use MyChat\Messages;


$messages = new Messages();
if(isset($_POST['action'])){
    if($_POST['action']=='send_msg' && isset($_POST['user_id']) && isset($_POST['content'])){
        $user_id = $_POST['user_id'];
        $content = $_POST['content'];
        $messages->setContent($content);
        $messages->setUserId($user_id);
        if($data = $messages->save()){
            echo json_encode($data);
            header("HTTP/1.1 200 Created");
            exit();
        }else{
            echo json_encode("failed");
            header("HTTP/1.1 404 Not Found");
            exit();
        }

    }else{
        header("HTTP/1.1 404 Not Found");
        exit();
    }

}
