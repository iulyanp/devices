<?php
declare(strict_types=1);

namespace App\Services;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

class Sender
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(new Version2X('localhost:3000'));
    }

    public function sendMessage(string $event, array $device)
    {
        $this->client->initialize();

        $this->client->emit($event, $device);

        $this->client->close();
    }
}
