<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once __DIR__ . '/konek.php';

    // Mengambil input JSON
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);


    if (isset($input['key_code'])) {
        $key_code = $input['key_code'];

        // Menyiapkan dan menjalankan query untuk mengecek key code
        $stmt = $conn->prepare("SELECT * FROM tb_transaksi WHERE midtrans_order_id = ?");
        $stmt->bind_param("s", $key_code);
        $stmt->execute();
        $result = $stmt->get_result();

        // Membuat respons
        $response = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['ejected'] == 0) {
                // Update status to 'used'
                $update_stmt = $conn->prepare("UPDATE tb_transaksi SET ejected = 1 WHERE midtrans_order_id = ?");
                $update_stmt->bind_param("s", $key_code);
                if ($update_stmt->execute()) {
                    $response['valid'] = true;
                    $response['message'] = 'Order ID is now marked as used';
                } else {
                    $response['valid'] = false;
                    $response['message'] = 'Failed to update the status';
                }
                $update_stmt->close();
            } else {
                $response['valid'] = false;
                $response['message'] = 'Order ID has already been used';
            }
        } else {
            $response['valid'] = false;
            $response['message'] = 'Order ID not found';
        }

        // Menutup statement
        $stmt->close();
    } else {
        // Jika key_code tidak ada di input, kembalikan respons error
        $response = array('error' => 'Invalid input');
    }

    // Menutup koneksi
    $conn->close();

    // Mengirimkan respons dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
