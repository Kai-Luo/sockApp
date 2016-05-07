<?php 


namespace SockApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Manager implements MessageComponentInterface {
    protected $clients;
    protected $game;
    public function __construct() {

        $this->clients = new \SplObjectStorage;
        
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $conn->send($this->game->firstLoad());
        $this->game= new Game();
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $jsonMsg=json_decode($msg);
        echo sprintf('Connection %d sending message to %d other connection%s' . "\n"
            , $from->resourceId, $numRecv, $numRecv == 1 ? '' : 's');
        $from->send($this->game->validate($jsonMsg->{"answer"}));

       /* ++++++++  ENVIAR A TODOS  +++++++++ 
       foreach ($this->clients as $client) {
            if ($from !== $client) {
               // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }*/
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

