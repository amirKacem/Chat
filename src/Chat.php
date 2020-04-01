<?php
namespace MyChat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class Chat implements MessageComponentInterface {

    private $clients;
    protected $users;
    
    public function __construct($users)
    {
        // initialize clients storage
        $this->clients = new \SplObjectStorage;
        $this->users = $users;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // store new connection in clients
        $this->clients->attach($conn);

        $voyants = $this->users->getAllUsers(['type'=>'V']);
        // send a welcome message to the client that just connected
        foreach ($this->clients as $client) {
            $client->send(json_encode(array('type' => 'connect', 'text' => 'Welcome to the test chat app!', 'voyants' => $voyants)));
        }

    }

    public function onClose(ConnectionInterface $conn)
    {
        // remove connection from clients
        $this->clients->detach($conn);
        printf("Connection closed: %s\n", $conn->resourceId);
        $voyants = $this->users->getAllUsers(['type'=>'V']);
        foreach ($this->clients as $client) {
            $client->send(json_encode(array('type' => 'disconnect','voyants' => $voyants)));
        }
    }


    public function onMessage(ConnectionInterface $conn, $message)
    {
        // send message out to all connected clients
        foreach ($this->clients as $client) {
            $client->send($message);
        }

    }

     public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
