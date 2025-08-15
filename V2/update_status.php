<?php
include 'db_connect.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);
$queue_number = $data['queue_number'] ?? "";
$status = $data['status'] ?? "";

if ($queue_number == "" || $status == "") {
    echo json_encode(["error" => "Missing parameters"]);
    exit();
}

$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE queue_number = ?");
$stmt->bind_param("ss", $status, $queue_number);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["error" => false, "message" => "สถานะได้รับการอัปเดตแล้ว"]);
} else {
    echo json_encode(["error" => true, "error" => "ไม่สามารถอัปเดตสถานะได้"]);
}

$stmt->close();
$conn->close();
?>
