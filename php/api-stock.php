<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('method not allowed');
}

require_once __DIR__ . '/konek.php';

// Step 1: Read the raw POST data
$json = file_get_contents('php://input');

// Step 2: Decode the JSON data
$data = json_decode($json, true);

// Step 3: Check for errors
if (json_last_error() !== JSON_ERROR_NONE) {
    // Handle JSON parsing error
    echo "Error parsing JSON: " . json_last_error_msg();
    exit;
}

/**
 * Generate a SQL mass update statement from an associative array.
 *
 * @param string $tableName The name of the table to update.
 * @param array $data An associative array of updates. Format:
 *                    [
 *                      ['id' => 1, 'column1' => 'value1', 'column2' => 'value2'],
 *                      ['id' => 2, 'column1' => 'value3', 'column2' => 'value4'],
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
    $columns = array_keys($data[0]);
    $columns = array_diff($columns, [$primaryKey]);

    foreach ($columns as $column) {
        $caseStatements = "$column = CASE";
        foreach ($data as $row) {
            $caseStatements .= " WHEN $primaryKey = " . intval($row[$primaryKey]) . " THEN '" . addslashes($row[$column]) . "'";
        }
        $caseStatements .= " ELSE $column END";
        $updates[] = $caseStatements;
    }

    $idsList = implode(', ', array_map('intval', $ids));
    $updateString = implode(', ', $updates);

    $sql = "UPDATE $tableName SET $updateString WHERE $primaryKey IN ($idsList);";
    return $sql;
}

mysqli_query($conn, generateMassUpdateSQL('tb_produk', $data, 'id'));

// UPDATE FROM table SET f1=v1, f2=v2
