<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/konek.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-Q9w7-tGlscM8lyQPum_KrenN';
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

$midtrans_id = rand();
$nama_pembeli = $_POST['name'];
$nominal = $_POST['total'];
$items = json_decode($_POST['items'], true);

// Bikin row baru di database
mysqli_query($conn, "INSERT INTO `tb_transaksi`(midtrans_order_id, nama_pembeli, nominal) 
VALUES('$midtrans_id', '$nama_pembeli', '$nominal')");

$transaksi_id = $conn->insert_id;

$values = "";
foreach ($items as $item) {
    $value = [
        'id_produk' => $item['id'],
        'id_transaksi' => $transaksi_id,
        'jumlah' => $item['quantity']
    ];

    $values .= "(" . implode(',', $value) . "),";
}
$values = rtrim($values, ",");
$sql = "INSERT INTO tb_transaksi_produk(id_produk, id_transaksi, jumlah) VALUES{$values}";
mysqli_query($conn, $sql);

$params = array(
    'transaction_details' => array(
        'order_id' => $midtrans_id,
        'gross_amount' => $nominal,
    ),
    'item_details' => $items,

    'customer_details' => array(
        'first_name' => $nama_pembeli,
    ),
);

$snapToken = \Midtrans\Snap::getSnapToken($params);
echo $snapToken;
