<?php
namespace MyChat;
use MyChat\Database\Db;
use MyChat\Helpers\Format;

class Users {
        private $id;
        private $name;
        private $password;
        private $email;
        private $loginStatus;
        private $lastLogin;
        private $image_path;
        private $type;
        private $db;

        function setId($id) { $this->id = $id; }
        function getId() { return $this->id; }
        function setName($name) { $this->name = $name; }
        function getName() { return $this->name; }
        function setEmail($email) { $this->email = $email; }
        function getEmail() { return $this->email; }
        function setLoginStatus($loginStatus) { $this->loginStatus = $loginStatus; }
        function getLoginStatus() { return $this->loginStatus; }
        function setLastLogin($lastLogin) { $this->lastLogin = $lastLogin; }
        function getLastLogin() { return $this->lastLogin; }
        public function getPassword()
        {
            return $this->password;
        }


        public function getImagePath()
        {
            return $this->image_path;
        }

        public function setImagePath($image_path)
        {
            $this->image_path = "http://localhost/chat/img/".$image_path.".png";
        }
        public function getType()
        {
            return $this->type;
        }


        public function setType($type)
        {
            $this->type = $type;
        }
        public function setPassword($password)
        {
            $this->password = $password;
        }

        public function __construct() {

            $this->db = Db::getInstance();
            $this->type = "C";

        }

        public function save() {
            $sql = "INSERT INTO `users`(`id`, `username`, `email`, `password`,`loginStatus`, `lastLogin`,`image_path`,`type`) 
VALUES (null, ?, ?, ?, ?,?,?,?)";

            $data = [ 'name'=> $this->name,
                    'email'=> $this->email,
                'password'=>$this->password,
                'loginStatus'=>$this->loginStatus,
                'lastlogin'=>$this->lastLogin,
                'image_path'=>$this->image_path,
                'type'=>$this->type
            ];
            $format = new Format();
            $format->allvalidation($data);
            $result = $this->db->query($sql,$data);

            if($result) {
                    $id = $result->getLastInsertId();

                    $user = $this->db->query("SELECT `id`,`username`, `email`,`loginStatus`, `lastLogin`,`image_path`,`type` from users where id=?",
                        ['id'=>$id])->result();
                    return $user;
                } else {
                    return false;
                }

        }

        /*public function getUserByEmail() {
            $data = ['email'=> $this->email];
            $stmt = $this->db->query('SELECT * FROM users WHERE email = :email',$data);

                if($stmt->RowCount()>0) {
                    $user = $stmt->result();
                }else{
                    $user = null;
                }

            return $user;
        }
            */
        public function login() {
            $data = [   'pseudo'=>$this->name,
                        'password'=>$this->password
                ];
            $stmt = $this->db->query('SELECT * FROM users WHERE username =? AND password=?',$data);

            if($stmt->RowCount()>0){
                return $stmt->result();
            }else{
                return false;
            }

        }

        public function updateLoginStatus() {
            $data = [
                'loginStatus'=>$this->loginStatus,
                'lastLogin'=>$this->lastLogin,
                'id'=>$this->id

            ];

            $stmt = $this->db->query('UPDATE users SET loginStatus = ?, lastLogin = ? WHERE id = ?',$data);


                if($stmt) {
                    return true;
                } else {
                    return false;
                }

        }

        public function getAllUsers($condt=[]) {
            $users = $this->db->findAll('users',['username','loginStatus','lastLogin','image_path','type'],$condt);

            return $users;
        }

    }