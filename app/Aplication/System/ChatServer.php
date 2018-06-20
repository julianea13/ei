<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require_once __DIR__ . '/../../../vendor/autoload.php';
$server = IoServer::factory(new HttpServer(new WsServer(new \ThisApp\Models\Chat)), 2000);
$server->run();
