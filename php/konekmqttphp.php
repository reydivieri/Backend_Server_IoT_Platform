<?php


require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/EchoLogger.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use Reydi\EchoLogger;

$username = "reydivieri";
$password = "aio_OnTW529QgXcaxzpyeWz3G26tOWBp";
$server = "io.adafruit.com";
$port = 8883;
$clientId = "dpG2Tq77SkGPbI2OF43YFA"; // Use a unique client ID for your application

$echo_logger = new EchoLogger();
$mqtt = new MqttClient($server, $port, $clientId, MqttClient::MQTT_3_1, null, $echo_logger);
$connectionSettings = (new ConnectionSettings)
    ->setUsername($username)
    ->setPassword($password)
    ->setUseTls(true)
    ->setConnectTimeout(90);
try {
    $mqtt->connect($connectionSettings, true);
    $topic = 'reydivieri/feeds/transaksi-mesin1'; // Replace with your Adafruit IO feed topic
    $message = 'Hello, Adafruit MQTT!'; // Message to publish

    $mqtt->publish($topic, $message);
    $mqtt->disconnect();
    echo 'Message published successfully!';
} catch (ConnectingToBrokerFailedException $e) {
    echo $e->getMessage();
}
