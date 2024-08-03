<?php
// header('Content-Type: application/json');

require_once(__DIR__ . '/../php/konek.php');
// date_default_timezone_set('Asia/Bangkok');
date_default_timezone_set('Asia/Jakarta');
// date_default_timezone_set('Atlantic/Azores');


/*
$sql = "SELECT nama_sensor, jumlah, waktu_produk_keluar FROM tb_sensor";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sensor = $row['nama_sensor'];
        if (!isset($data[$sensor])) {
            $data[$sensor] = array(
                'label' => $sensor,
                'data' => array()
            );
        }
        // Mengambil hanya bagian tanggal dari timestamp
        // $date = date('Y-m-d', strtotime($row['waktu_produk_keluar']));
        // $data[$sensor]['data'][] = array(
        //     strtotime($date) * 1000, // Mengonversi ke milidetik
        //     $row['jumlah']
        // );
        // $timestamp = strtotime($row['waktu_produk_keluar']) * 1000; // Convert to milliseconds
        // $data[$sensor]['data'][] = array($timestamp, $row['jumlah']);

        // hitung jumlah pada setiap sensor
        $timestamp = strtotime($row['waktu_produk_keluar']) * 1000; // Convert to milliseconds
        $data[$sensor]['data'][] = array($timestamp, (int)$row['jumlah']);
    }
}

// $conn->close();
echo json_encode($data, JSON_PRETTY_PRINT);
*/
$sql = "SELECT nama_sensor, jumlah, waktu_produk_keluar FROM tb_sensor";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sensor = $row['nama_sensor'];
        $timestamp = strtotime($row['waktu_produk_keluar']);
        $hour = date('Y-m-d H:00:00', $timestamp); // Mengelompokkan berdasarkan jam
        $minute = date('Y-m-d H:i:00', $timestamp); // Mengelompokkan berdasarkan menit
        $date = date('Y-m-d', $timestamp); // Mengelompokkan berdasarkan tanggal

        if (!isset($data[$sensor])) {
            $data[$sensor] = array();
        }

        if (!isset($data[$sensor][$date])) {
            $data[$sensor][$date] = 0;
        }

        $data[$sensor][$date] += $row['jumlah'];
    }
}

$finalData = array();
foreach ($data as $sensor => $dates) {
    $sensorData = array(
        'label' => $sensor,
        'data' => array()
    );

    foreach ($dates as $date => $jumlah) {
        $timestamp = strtotime($date) * 1000; // Convert to milliseconds
        $sensorData['data'][] = array($timestamp, $jumlah);
    }

    $finalData[] = $sensorData;
}
echo json_encode($finalData);
