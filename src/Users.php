<?php
namespace MyChat;
use MyChat\Database\Db;
use MyChat\Helpers\Format;

class Users {
        protected $id;
        protected $name;
        protected $password;
        protected $email;
        protected $loginStatus;
        protected $lastLogin;
        protected $image_path;
        protected $type;
        protected $status;
        protected $firstname;
        protected $lastname;
        protected $tel;
        protected $db;


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
        public function getPassword(){return $this->password;}
        public function getImagePath(){return $this->image_path;}

        public function setImagePath($image_path,$custom_path=false)
        {
            if($custom_path){
                $this->image_path=$image_path;
            }else{
                $this->image_path = "https://www.voyanceenligne.chat/img/".$image_path.".png";

            }
        }
        public function getType(){return $this->type;}


        public function setType($type){$this->type = $type;}
        public function setPassword($password){$this->password = $password;}
        public function getStatus(){return $this->status;}
        public function setStatus($status){$this->status = $status;}

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }


        public function __construct() {

            $this->db = Db::getInstance();
            $this->type = "C";
            $this->image_path="";
            $this->loginStatus=0;


        }

        public function save() {
            $sql = "INSERT INTO `users`(`id`, `username`, `email`, `password`,`loginStatus`, `lastLogin`,`image_path`,`type`,`firstname`,`lastname`,`tel`) 
VALUES (null, ?, ?, ?, ?,?,?,?,?,?,?)";

            $data = [ 'name'=> $this->name,
                    'email'=> $this->email,
                'password'=>$this->password,
                'loginStatus'=>$this->loginStatus,
                'lastlogin'=>$this->lastLogin,
                'image_path'=>$this->image_path,
                'type'=>$this->type,
                'firstname'=>$this->firstname,
                'lastname'=>$this->lastname,
                'tel'=>$this->tel
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

        public function login() {
            $data = [   'pseudo'=>$this->name,
                        'password'=>$this->password,
                        'status'=>'ON'
                ];
            $format = new Format();
            $data = $format->allvalidation($data);

            $stmt = $this->db->query('SELECT * FROM users WHERE username =? AND password=? AND status=? ',$data);

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
            $users = $this->db->findAll('users',["id",'username','email','loginStatus','lastLogin','image_path','type','status','firstname','lastname','tel'],$condt);

            return $users;
        }

            public function getAllMsg(){
                $sql = "SELECT  m.content,m.date_send,u.username,u.image_path,u.type,u.loginStatus,u.lastLogin from messages m join users u on m.user_id=u.id where u.status='ON' order by m.date_send";

                return $this->db->query($sql)->results();

            }

        public function deleteUser(){
            return $this->db->delete('users','id=?',[$this->id]);
        }


        public function getUser(){
            $res = $this->db->query("SELECT `id`,`username`, `email`,`loginStatus`, `lastLogin`,`image_path`,`type`,`firstname`,`lastname`,`tel` from users where id=? AND (type='V' OR type='C')",
                ['id'=>$this->id]);

            if($res){
                $user =   $res->result();
                return $user;
            }else{
                return false;
            }


        }

        public function update($data){

            $format = new Format();
            $data = $format->allvalidation($data);
            return $this->db->update('users',$data,['id'=>$this->id]);
        }

    }