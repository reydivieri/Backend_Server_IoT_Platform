<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");


require_once __DIR__ . '/konek.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array("message" => "Connection failed: " . $conn->connect_error));
    exit();
}

$sql = "SELECT id, nama, harga, stok, img FROM tb_produk";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(array("message" => "Query error: " . $conn->error));
    exit();
}

$products = array();

if ($result->num_rows > 0) {
    // Mengambil setiap baris data dan memasukkan ke array products
    while ($row = $result->fetch_assoc()) {
        $product = array(
            "id" => $row["id"],
            "name" => $row["nama"],
            "price" => $row["harga"],
            "stock" => $row["stok"],
            "img" => $row["img"]
        );
        array_push($products, $product);
    }
    // Mengirim respon dalam bentuk JSON
    echo json_encode($products);
} else {
    // Mengirim respon jika tidak ada data
    echo json_encode(array("message" => "Tidak ada produk ditemukan."));
}
