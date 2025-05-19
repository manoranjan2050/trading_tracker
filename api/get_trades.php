<?php
include '../db.php';

$sql = "SELECT t.trade_id, s.symbol, t.entry_price, t.stoploss, t.target1, t.target2, t.date_entered, t.holding_period, t.status
        FROM trades t
        JOIN stocks s ON t.stock_id = s.stock_id
        WHERE t.status = 'active'
        ORDER BY t.date_entered DESC";

$result = $conn->query($sql);
$trades = [];

while ($row = $result->fetch_assoc()) {
    $trades[] = $row;
}

echo json_encode($trades);
?>
