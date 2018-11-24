<?php

namespace BeyondCode\LaravelWebSockets\Server\Logger;

use Ratchet\ConnectionInterface;

class ConnectionLogger extends Logger implements ConnectionInterface
{
    /** @var \Ratchet\ConnectionInterface */
    protected $connection;

    public static function decorate(ConnectionInterface $app): ConnectionLogger
    {
        $logger = app(ConnectionLogger::class);

        return $logger->setConnection($app);
    }

    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    protected function getConnection() {
        return $this->connection;
    }

    public function send($data)
    {
        $this->info("{$this->connection->appId}: connection id {$this->connection->socketId} sending message {$data}");

        $this->connection->send($data);
    }

    public function close()
    {
        $this->warn("{$this->connection->appId}: connection id {$this->connection->socketId} closing.");

        $this->connection->close();
    }

    public function __set($name, $value)
    {
        return $this->connection->$name = $value;
    }

    public function __get($name)
    {
        return $this->connection->$name;
    }

    public function __isset($name) {
        return isset($this->connection->$name);
    }

    public function __unset($name) {
        unset($this->connection->$name);
    }
}