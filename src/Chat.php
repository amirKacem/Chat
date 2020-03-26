<?php
namespace MyChat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use MyChat\Users;

class Chat implements MessageComponentInterface {

    private $clients;
    
    public function __construct()
    {
        // initialize clients storage
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // store new connection in clients
        $this->clients->attach($conn);
        printf("New connection: %s\n", $conn->resourceId);

        // send a welcome message to the client that just connected
        $conn->send(json_encode(array('type'=>'connect', 'text' => 'Welcome to the test chat app!')));
    }

    public function onClose(ConnectionInterface $conn)
    {
        // remove connection from clients
        $this->clients->detach($conn);
        printf("Connection closed: %s\n", $conn->resourceId);
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
