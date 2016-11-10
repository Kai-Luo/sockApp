<?php 
namespace SockApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Manager implements MessageComponentInterface {

    protected $clients;
    protected $games;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->games = new \SplObjectStorage; 
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $game = new Game($conn->resourceId); 
        $conn->send($game->firstLoad());
        $this->games->attach($game); //  Se añade un nuevo juego a la array de objetos games.
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $jsonMsg = json_decode($msg);
        echo sprintf('Connection %d sending message to %d other connection%s' . "\n", $from->resourceId, $numRecv, $numRecv == 1 ? '' : 's');
        foreach ($this->games as $el) {
            if($el->id == $from->resourceId){
                $from->send($el->validate($jsonMsg->{"answer"}));
            }
        }

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

