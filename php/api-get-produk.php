<?php

if (!isset($_GET['midtrans_order_id'])) {
    echo 'Transaksi tidak ditemukan';
    die;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/konek.php';


$tr_id = $_GET['midtrans_order_id'];

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-Q9w7-tGlscM8lyQPum_KrenN';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

$status = \Midtrans\Transaction::status($tr_id);

if ($status->transaction_status == 'settlement') { // cari tau status success lainnya

    $transaksi_result = mysqli_query($conn, "SELECT * FROM tb_transaksi WHERE midtrans_order_id = '$tr_id'");
    $transaksi = mysqli_fetch_assoc($transaksi_result);
    $id_transaksi = $transaksi['id'];

    $tr_produk_result = mysqli_query($conn, "SELECT id_produk, jumlah 
    FROM tb_transaksi_produk WHERE id_transaksi = '$id_transaksi'");
    $tr_produk = mysqli_fetch_all($tr_produk_result, MYSQLI_ASSOC);
    $tr_produk_json = json_encode($tr_produk, JSON_PRETTY_PRINT);
    http_response_code(200);
    echo ($tr_produk_json);
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Transaction is not settled']);
}
