<?php
include '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$trade_id = intval($data['trade_id']);

$stmt = $conn->prepare("UPDATE trades SET status='closed' WHERE trade_id=?");
$stmt->bind_param("i", $trade_id);
$stmt->execute();

echo json_encode(['status' => 'closed']);
?>
