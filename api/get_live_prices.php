<?php
include '../db.php';

$sql = "SELECT t.trade_id, s.symbol, t.entry_price FROM trades t JOIN stocks s ON t.stock_id = s.stock_id WHERE t.status = 'active'";
$result = $conn->query($sql);

$prices = [];
while ($row = $result->fetch_assoc()) {
    $ltp = $row['entry_price'] * (0.95 + (rand(0, 100)/1000)); // random +/-5%
    $pnl = $ltp - $row['entry_price'];
    $prices[] = [
        'trade_id' => $row['trade_id'],
        'symbol' => $row['symbol'],
        'ltp' => round($ltp, 2),
        'pnl' => round($pnl, 2)
    ];
}

echo json_encode($prices);
?>
