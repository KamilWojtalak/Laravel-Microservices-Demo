<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class OrderCreatedListenerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to order_created queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            'rabbitmq', // host
            5672,       // port
            'guest',    // user
            'guest',    // pass
            '/',        // vhost
            false,      // insist
            'AMQPLAIN', // login method
            null,       // login response
            'en_US',    // locale
            3.0,        // connection_timeout
            3.0,        // read_write_timeout
            null,       // context
            true,       // keepalive
            10          // heartbeat
        );
        $channel = $connection->channel();

        $channel->queue_declare('order_created', false, false, false, false);

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            logger('📩 Order created event received:', $data);
        };

        $channel->basic_consume('order_created', '', false, true, false, false, $callback);

        $this->info("Listening for order_created events...");

        while (true) {
            try {
                $channel->wait();
            } catch (\Exception $e) {
                logger()->error('🐛 Błąd RabbitMQ: ' . $e->getMessage());
                break; // lub reconnect + retry
            }
        }

        $channel->close();
        $connection->close();
    }
}
