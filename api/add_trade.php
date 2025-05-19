<?php
include '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$symbol = strtoupper(trim($data['symbol']));
$entry_price = floatval($data['entry_price']);
$stoploss = floatval($data['stoploss']);
$target1 = floatval($data['target1']);
$target2 = floatval($data['target2']);
$date_entered = $data['date_entered'];
$holding_period = intval($data['holding_period']);

// Check if stock exists, else insert
$stmt = $conn->prepare("SELECT stock_id FROM stocks WHERE symbol=?");
$stmt->bind_param("s", $symbol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO stocks (symbol, name) VALUES (?, ?)");
    $name = $symbol; // can be updated later
    $stmt->bind_param("ss", $symbol, $name);
    $stmt->execute();
    $stock_id = $conn->insert_id;
} else {
    $row = $result->fetch_assoc();
    $stock_id = $row['stock_id'];
}

// Insert trade
$stmt = $conn->prepare("INSERT INTO trades (stock_id, entry_price, stoploss, target1, target2, date_entered, holding_period) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("idddssi", $stock_id, $entry_price, $stoploss, $target1, $target2, $date_entered, $holding_period);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
