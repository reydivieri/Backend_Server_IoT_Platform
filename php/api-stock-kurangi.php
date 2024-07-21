<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405); // Method Not Allowed
    die('Method not allowed');
}

require_once __DIR__ . '/konek.php';

// Step 1: Read the raw POST data
$json = file_get_contents('php://input');

// Step 2: Decode the JSON data
$data = json_decode($json, true);

// Step 3: Check for errors
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Error parsing JSON: " . json_last_error_msg()]);
    exit;
}

// Step 4: Check for required fields
if (empty($data) || !is_array($data)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

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


if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$sql = generateMassUpdateSQL('tb_produk', $data, 'id');
if (empty($sql)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Generated SQL is empty"]);
    exit;
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Update successful"]);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => "SQL execution failed: " . $conn->error]);
}

$conn->close();
