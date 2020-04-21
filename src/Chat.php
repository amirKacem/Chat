<?php
namespace MyChat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class Chat implements MessageComponentInterface {

    private $clients;
    protected $usersImpl;
    private $users;

    public function __construct($usersImpl)
    {
        // initialize clients storage
        $this->clients = new \SplObjectStorage;
        $this->usersImpl = $usersImpl;
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // store new connection in clients
        $this->clients->attach($conn);

    }



    public function onClose(ConnectionInterface $conn)
    {

        if(isset($this->users[$conn->resourceId])){
            $user  = $this->users[$conn->resourceId] ;


            // check if the user is alerday connected
            $userConnected = array_filter(
                $this->users,
                function ($e) use ($user) {

                    return $e->id == $user->id;

                }
            );

            if(count($userConnected)<=1){
                $this->updateUserStatus($user,0);

            }
            unset($this->users[$conn->resourceId]);
            //var_dump($user->username);


        }
        // remove connection from clients
        $this->clients->detach($conn);

        $voyants = $this->usersImpl->getAllUsers(['type'=>'V']);

        foreach ($this->clients as $client) {
            $client->send(json_encode(array('type' => 'disconnect','voyants' => $voyants)));
        }
    }


    public function onMessage(ConnectionInterface $conn, $message)
    {
        // send message out to all connected clients
        $data = json_decode($message);
        printf("Connection opened: %s\n",$data->type);

        switch($data->type){
            case 'subscribe':

                $this->usersImpl->setId($data->userId);
                $user = $this->usersImpl->getUser();
                if($user){
                    $this->updateUserStatus($user,1);
                    $this->users[$conn->resourceId] = $user ;
                    // send a welcome message to the client that just connected

                }
                $voyants = $this->usersImpl->getAllUsers(['type'=>'V']);
                $messages = $this->usersImpl->getAllMsg();

                foreach ($this->clients as $client) {
                    $client->send(json_encode(array('type' => 'subscribe', 'voyants' => $voyants,'messages'=>$messages)));
                }

                break;

            default:

                foreach ($this->clients as $client) {
                    $client->send($message);
                }
        }

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


    public function updateUserStatus($user,$status){


        $this->usersImpl->setId($user->id);
        $this->usersImpl->setLoginStatus($status);
        $this->usersImpl->updateLoginStatus();

    }



}
