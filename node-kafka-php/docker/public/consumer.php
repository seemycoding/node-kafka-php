<?php
$conf = new RdKafka\Conf();
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('debug', 'all');
$rk = new RdKafka\Consumer($conf);
$rk->addBrokers("172.17.0.1:9092");
$topic = $rk->newTopic("test");
$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);
echo "consumer started" . PHP_EOL;

while (true) {
    $msg = $topic->consume(0, 1000);
    if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        continue;
    } elseif ($msg->err) {
        echo $msg->errstr(), "\n";
        break;
    } else {
        echo $msg->payload, "\n";
    }
}
?>