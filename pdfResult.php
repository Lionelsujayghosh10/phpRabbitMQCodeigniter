<?php


    require_once __DIR__ . '/vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;

    

    function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "password";
        $db = "reportCard_system";
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
        if(!$conn) {
        die("Connect failed: %s\n". $conn -> error);
        } else {
        echo "connected succesfull.";
        }
        return $conn;
    }


    function CloseCon($conn) {
        $conn->close();
    }



    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel_connection = $connection->channel();




    $channel_connection->queue_declare('pdfResult', false, true, false, false);


    echo " [*] Waiting for messages. To exit press CTRL+C\n";


    $callback = function ($msg) {
        $data_array = json_decode($msg->body, true);
        echo "<pre>"; print_r($data_array); 
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    };



    $channel_connection->basic_qos(null, 1, null);
    $channel_connection->basic_consume('pdfResult', '', false, false, false, false, $callback);



    while ($channel_connection->is_consuming()) {
        $channel_connection->wait();
    }
    
    $channel_connection->close();
    $connection->close();


  
  
  
  
  
  


    