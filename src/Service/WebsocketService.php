<?php

namespace App\Service;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class WebsocketService implements MessageComponentInterface
{

    protected $clients;

    public function __construct() 
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        //  Stocker la nouvelle connexion pour envoyer des messages ultérieurement.
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        $conn->send(json_encode([
            'type' => 'id',
            'data' => $conn->resourceId
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {
        foreach ($this->clients as $client) {
            //if ($from !== $client) {
                // L'expéditeur n'est pas le destinataire, envoyez à chaque client connecté.
                $client->send(json_encode([
                    'type' => 'message',
                    'data' => [
                        'clientId' => $from->resourceId,
                        'message' => (string)$msg,
                        'created' =>  (new \DateTime())->format("Y-m-d H:i:s")
                    ]
                ]));
            //}
        }
    }

    public function onClose(ConnectionInterface $conn) 
    {
        //  La connexion est fermée, retirez-la, car nous ne pouvons plus lui envoyer de messages.
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {
        echo "Une erreur est survenue : {$e->getMessage()}\n";

        $conn->close();
    }
}
