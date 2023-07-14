<?php
$conf = new RdKafka\Conf();
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('debug', 'all');
$rk = new RdKafka\Producer($conf);
$rk->addBrokers("172.17.0.1:9092");
$topic = $rk->newTopic("test");
if (!$rk->getMetadata(false, $topic, 2000)) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}
$messages = array("Payload Data");
$i = 0;
$interval = 5;
$timerId = null;
function sendMessage() {
    global $i, $messages, $topic;
    $i = $i >= count($messages) - 1 ? 0 : $i + 1;
    
    $payloads = array(
        'topic' => $topic,
        'messages' => array(
            array('key' => 'php-kafka', 'value' => json_encode($messages[$i]))
        )
    );
    $topic->produce(RD_KAFKA_PARTITION_UA, 0,json_encode($payloads));
    echo 'payloads=' . json_encode($payloads) . "\n";
}

while (true) {
    sendMessage();
    sleep($interval);
}


echo "Message published\n";
?>