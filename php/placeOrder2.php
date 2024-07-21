<?php
// Check if the required POST parameters are set
if (!isset($_POST['total']) || !isset($_POST['items']) || !isset($_POST['name'])) {
    echo($_POST['total']);
    echo($_POST['items']);
    echo($_POST['name']);
    http_response_code(400); // Bad Request
    echo "Missing required parameters.";
    exit;
}

// Include the Midtrans Snap library if not already included
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php'; // Adjust the path as per your file structure

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-Q9w7-tGlscM8lyQPum_KrenN';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

// Generate transaction details
$order_id = rand(); // Generate a random order ID
$total_amount = $_POST['total']; // Get total amount from POST data
$items = json_decode($_POST['items'], true); // Decode the JSON string of items

// Validate total amount
if (!is_numeric($total_amount)) {
    http_response_code(400); // Bad Request
    echo "Invalid total amount.";
    exit;
}

// Validate items
if (!is_array($items)) {
    http_response_code(400); // Bad Request
    echo "Invalid items data.";
    exit;
}

echo "Received POST data:\n";
print_r($_POST);
echo($total_amount);
echo($items);
echo($_POST['name']);

// Build the request parameters
$params = array(
    'transaction_details' => array(
        'order_id' => $order_id,
        'gross_amount' => $total_amount,
    ),
    'item_details' => $items,
    'customer_details' => array(
        'first_name' => $_POST['name'],
    ),
);

try {
    // Get Snap token from Midtrans API
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo $snapToken;
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo "Error: " . $e->getMessage();
    exit;
}
?>
