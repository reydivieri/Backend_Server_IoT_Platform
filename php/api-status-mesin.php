<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    require_once __DIR__ . '/konekmqtt.php';

    $id = $_SESSION['id'];
    if (!isset($id)) {
        http_response_code(401);
        die("Not authorized");
    }

    if (!isset($_POST['status'])) {
        http_response_code(402);
        die('Missing parameter status');
    }

    $status = boolval($_POST['status']);

    $topic = 'reydivieri/feeds/status-mesin1'; // Replace with your Adafruit IO feed topic
    $message = json_encode(['status' => $status]); // Message to publish

    $mqtt->publish($topic, $message);
    $mqtt->disconnect();
}
