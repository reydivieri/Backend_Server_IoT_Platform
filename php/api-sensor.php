<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405); // Method Not Allowed
    die('Method not allowed');
}

require_once __DIR__ . '/konek.php';

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id_produk']) && isset($data['jumlah']) && isset($data['nama_sensor']) && isset($data['status'])) {
    $idp = $data['id_produk'];
    $jumlah = $data['jumlah'];
    $nama_sensor = $data['nama_sensor'];
    $status = $data['status'];

    // Insert data into tb_sensor
    $sql = "INSERT INTO tb_sensor (id_produk, jumlah, nama_sensor, status) VALUES ('$idp', '$jumlah', '$nama_sensor', '$status')";

    if ($conn->query($sql) === TRUE) {
        $response = array("status" => "success", "message" => "Data inserted successfully");
    } else {
        $response = array("status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error);
    }

    // Update tb_transaksi_produk
    $sql_update = "UPDATE tb_transaksi_produk SET status='$status', waktu_keluar=NOW() WHERE id_produk='$idp'";
    if ($conn->query($sql_update) === TRUE) {
        $response['transaksi_update'] = "Transaksi updated successfully";
    } else {
        $response['transaksi_update'] = "Error: " . $sql_update . "<br>" . $conn->error;
    }

    // Reduce stock in tb_produk
    $data_stock = array(array("id" => $idp, "stok" => $jumlah));
    $sql_stock = generateMassUpdateSQL('tb_produk', $data_stock, 'id');

    if ($conn->query($sql_stock) === TRUE) {
        $response['stock_update'] = "Stock updated successfully";
    } else {
        $response['stock_update'] = "Error: " . $sql_stock . "<br>" . $conn->error;
    }
} else {
    $response = array("status" => "error", "message" => "Invalid input");
}

// Close connection
$conn->close();

// Return the response in JSON format
echo json_encode($response);

/**
 * Generate a SQL mass update statement from an associative array.
 *
 * @param string $tableName The name of the table to update.
 * @param array $data An associative array of updates. Format:
 *                    [
 *                      ['id' => 1, 'stok' => 'value1'],
 *                      ['id' => 2, 'stok' => 'value2'],
 *                      // ...
 *                    ]
 * @param string $primaryKey The primary key column name.
 * @return string The generated SQL mass update statement.
 */
function generateMassUpdateSQL($tableName, $data, $primaryKey)
{
    if (empty($data)) {
        return '';
    }

    $ids = array_column($data, $primaryKey);
    $updates = [];

    foreach ($data as $row) {
        $id = intval($row[$primaryKey]);
        $stok = intval($row['stok']);
        $updates[] = "WHEN $primaryKey = $id THEN $stok";
    }

    $idsList = implode(', ', array_map('intval', $ids));
    $sql = "UPDATE $tableName SET stok = stok - CASE " . implode(' ', $updates) . " ELSE stok END WHERE $primaryKey IN ($idsList);";
    return $sql;
}
