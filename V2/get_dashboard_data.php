<?php
header('Content-Type: application/json; charset=utf-8');
include 'db_connect.php';

// Query สถิติการจอง
$totalQuery = "SELECT COUNT(*) AS total FROM bookings";
$activeQuery = "SELECT COUNT(*) AS active FROM bookings WHERE status = 'active'";
$cancelledQuery = "SELECT COUNT(*) AS cancelled FROM bookings WHERE status = 'cancelled'";

$totalResult = $conn->query($totalQuery);
$activeResult = $conn->query($activeQuery);
$cancelledResult = $conn->query($cancelledQuery);

$total = ($totalResult && $totalResult->num_rows > 0) ? $totalResult->fetch_assoc()['total'] : 0;
$active = ($activeResult && $activeResult->num_rows > 0) ? $activeResult->fetch_assoc()['active'] : 0;
$cancelled = ($cancelledResult && $cancelledResult->num_rows > 0) ? $cancelledResult->fetch_assoc()['cancelled'] : 0;

// Query กิจกรรมล่าสุด (แสดง 5 รายการล่าสุด)
$recentQuery = "SELECT queue_number, name, booking_date, booking_time, status 
                FROM bookings 
                ORDER BY booking_date DESC, booking_time DESC 
                LIMIT 5";
$recentResult = $conn->query($recentQuery);
$recent = [];
if ($recentResult && $recentResult->num_rows > 0) {
    while ($row = $recentResult->fetch_assoc()) {
        $recent[] = $row;
    }
}

$data = [
    "total" => $total,
    "active" => $active,
    "cancelled" => $cancelled,
    "recent" => $recent
];

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$conn->close();
?>
