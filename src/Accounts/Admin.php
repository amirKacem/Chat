<?php
namespace MyChat\Accounts;

use MyChat\Users;

class  Admin extends Users{

    public function login() {
        $data = [   'pseudo'=>$this->name,
            'password'=>$this->password,
            'type'=>'A'
        ];
        $stmt = $this->db->query('SELECT * FROM users WHERE username =? AND password=? and type=?',$data);

        if($stmt->RowCount()>0){
            return $stmt->result();
        }else{
            return false;
        }

    }

    public function getUser(){
        $user = $this->db->query("SELECT `id`,`username`, `email`,`loginStatus`, `lastLogin`,`image_path`,`type` from users where id=? AND type='A' LIMIT 1",
            ['id'=>$this->id])->result();
        return $user;
    }

}