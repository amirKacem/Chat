<?php
namespace MyChat\Api;

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use MyChat\Helpers\FileUpload;
use MyChat\Users;
if(isset($_SERVER['HTTP_ORIGIN'])  && $_SERVER['HTTP_ORIGIN'] == "http://localhost") {
    header("Access-Control-Allow-Origin: http://localhost");
    if (isset($_POST['action'])) {
        $users = new Users();
        if ($_POST['action'] == 'get_voyants') {

            $voyants = $users->getAllUsers(['type' => "V"]);
            echo json_encode($voyants);
            header("HTTP/1.1 200 Created");
            exit();
        }else if($_POST['action'] == 'get_clients'){
            $clients = $users->getAllUsers(['type' => "C"]);
            echo json_encode($clients);
            header("HTTP/1.1 200 Created");
            exit();
        }
        else if ($_POST['action'] == "add_user" && isset($_POST['username']) && isset($_POST['password']) && isset($_FILES['image'])) {
            $uploadFile = new FileUpload($_SERVER['DOCUMENT_ROOT'] . "/Chat/admin/assets/img/uploads/");
            $name = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $file = $_FILES['image'];
            $type = $_POST['type'];

            $image_path = "";
            $users->setName($name);
            $users->setEmail($email);
            $users->setPassword($password);
            $users->setType($type);
            $users->setLoginStatus(0);

            $uploadRes = $uploadFile->upload($file);

            if ($uploadRes) {
                $users->setImagePath($uploadRes, true);
                $user = $users->save();
                if ($user != false) {
                    echo json_encode(['success', $uploadRes,'user'=>$user]);
                    header("HTTP/1.1 200 Created");
                    exit();
                } else {

                    $errors[] = "user alerday exist";
                    echo json_encode(['errors' => $errors]);
                    header("HTTP/1.1 400 Invalid Parametres");
                    exit();
                }
            } else {
                $errors = $uploadFile->getErrors();
                echo json_encode(['errors' => $errors]);
                header("HTTP/1.1 404 Not Found");
                exit();

            }

        } else if ($_POST['action'] == "update_user" && isset($_POST['user_id'])) {
            $uploadFile = new FileUpload($_SERVER['DOCUMENT_ROOT'] . "/Chat/admin/assets/img/uploads/");
            $user_id = $_POST['user_id'];
            $users->setId($user_id);
            $username= $_POST['username'];
            $email = $_POST['email'];
            $data = [
                'username'=>$username,
                'email'=>$email
            ];
            $file = isset($_FILES['image']) ? $_FILES['image']:'' ;
            $uploadRes = $uploadFile->upload($file);
            if ($uploadRes) {
                $data['image_path']= $uploadRes;
            }

            if ($result = $users->update($data)) {
                echo json_encode($result);
                header("HTTP/1.1 200 Created");
                exit();
            } else {
                $errors[] = "user alerday exist";
                echo json_encode(['errors' => $errors]);
                header("HTTP/1.1 400 Invalid Parametres");
                exit();
            }

        } else if($_POST['action']=="update_user_status" && isset($_POST['user_id'])){
            $user_id = $_POST['user_id'];
            $status = $_POST['status'];
            $users->setId($user_id);

               if($users->update(['status'=>$status])){
                   echo json_encode("updated");
                   header("HTTP/1.1 200 Created");
                   exit();
               }else{
                    echo json_encode("Failed");
                    header("400 Invalid Parametres");
                    exit();
               }
        }
        else if ($_POST['action'] == "delete_user" && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $users->setId($user_id);
            if ($users->deleteUser()) {
                echo json_encode("deleted");
                header("HTTP/1.1 200 Created");
                exit();
            } else {
                echo json_encode("failed");
                header("HTTP/1.1 400 Invalid Parametres");
                exit();
            }

        } else if ($_POST['action'] == "get_user" && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $users->setId($user_id);
            $user = $users->getUser();
            echo json_encode($user);
            header("HTTP/1.1 200 Created");
            exit();
        } else {
            echo json_encode("failed");
            header("HTTP/1.1 404 Not Found");
            exit();
        }

    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }


}else{
    header("HTTP/1.1 403 Unauthorized");
    exit();
}