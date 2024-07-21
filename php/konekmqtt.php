<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

$mqtt_username = "reydivieri";
$mqtt_password = "aio_OnTW529QgXcaxzpyeWz3G26tOWBp";
$mqtt_server = "io.adafruit.com";
$mqtt_port = 8883;
$mqtt_clientId = "dpG2Tq77SkGPbI2OF43YFA"; // Use a unique client ID for your application

$mqtt = new MqttClient($mqtt_server, $mqtt_port, $mqtt_clientId);
$mqtt_connectionSettings = (new ConnectionSettings)
    ->setUsername($mqtt_username)
    ->setPassword($mqtt_password)
    ->setUseTls(true)
    ->setConnectTimeout(90);

$mqtt->connect($mqtt_connectionSettings, true);
