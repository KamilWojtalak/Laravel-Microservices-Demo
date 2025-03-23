<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    public function publishOrderCreated(array $data)
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('order_created', false, false, false, false);

        $messageBody = json_encode($data);
        $msg = new AMQPMessage($messageBody);
        $channel->basic_publish($msg, '', 'order_created');

        $channel->close();
        $connection->close();
    }
}
