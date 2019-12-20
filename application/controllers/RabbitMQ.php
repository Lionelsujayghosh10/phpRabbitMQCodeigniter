<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require_once '/var/www/html/ReportCard/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ extends CI_Controller{


  public function receive_function(){


    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);
    echo " [*] Waiting for messages. To exit press CTRL+C\n";


    $callback = function ($msg) {
      echo ' [x] Received ', $msg->body, "\n";
    };

    $channel->basic_consume('hello', '', false, true, false, false, $callback);

    while ($channel->is_consuming()) {
        $channel->wait();
    }


    $channel->close();
    $connection->close();
  }


  public function send_function(){
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();



    $channel->queue_declare('hello', false, false, false, false);
    $msg = new AMQPMessage('Hello World!');
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Hello World!'\n";


    $channel->close();
    $connection->close();
  }
}
