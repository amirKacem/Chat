<?php


namespace MyChat;


use MyChat\Database\Db;
use MyChat\Helpers\Format;

class Messages
{
    private $id;
    private $content;
    private $date_send;
    private $user_id;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getDateSend()
    {
        return $this->date_send;
    }

    public function setDateSend($date_send)
    {
        $this->date_send = $date_send;
    }

    public function getUserId()
    {
        return $this->user_id;
    }


    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __construct() {

        $this->db = Db::getInstance();
        $this->date_send = date('Y-m-d h:i:s');

    }


    public function save() {
        $sql = "INSERT INTO `messages`(`id`,`content`,`user_id`,`date_send`) VALUES (null, ?, ?,?)";

        $data = [ 'content'=> $this->content,
                    'user_id'=>$this->user_id,
            'date_send'=>$this->date_send
        ];
        $format = new Format();
        $format->allvalidation($data);
        $stmt = $this->db->query($sql,$data);
        if($stmt){
            $msg_id = $stmt->getLastInsertId();
            $sql = "SELECT  m.content,m.date_send,u.username,u.image_path,u.type,u.loginStatus,u.lastLogin from messages m join users u on m.user_id=u.id where m.id=?";
            $user_msg = $this->db->query($sql,[$msg_id])->result();
            return $user_msg;
        }else{
            return false;
        }
    }

    public function getAllUserMsg(){
        $sql = "SELECT  m.content,m.date_send,u.username,u.image_path,u.type,u.loginStatus,u.lastLogin from messages m join users u on m.user_id=u.id order by m.date_send";

        return $this->db->query($sql)->results();

    }



}