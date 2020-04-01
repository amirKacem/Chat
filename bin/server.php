<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyChat\Chat;
use MyChat\Users;

require dirname(__DIR__) . '/vendor/autoload.php';

$users = new Users();
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat($users)
        )
    ),
    8083);

$server->run();