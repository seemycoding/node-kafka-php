<?php
$conf = new RdKafka\Conf();
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('debug', 'all');
$rk = new RdKafka\Producer($conf);
$rk->addBrokers("localhost:9092");
$topic = $rk->newTopic("test");
if (!$rk->getMetadata(false, $topic, 2000)) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}
$messages = array("Payload Data");
$i = 0;
$interval = 5000;
$timerId = null;
function sendMessage() {
    global $i, $messages, $topic;
    $i = $i >= count($messages) - 1 ? 0 : $i + 1;
    
    $payloads = array(
        'topic' => $topic,
        'messages' => "Payload Data"
    );
    $topic->produce(RD_KAFKA_PARTITION_UA, 0,$payloads);
    echo 'payloads=' . json_encode($payloads) . "\n";
}

$timerId = setInterval(function () {
    sendMessage();
}, $interval);

echo "Message published\n";
?>