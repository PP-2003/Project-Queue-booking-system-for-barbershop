<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barber1"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// ดึงเฉพาะการจองที่ยังไม่ถูกยกเลิก (status ไม่เท่ากับ 'cancelled')
$sql = "SELECT booking_date, booking_time FROM bookings WHERE status <> 'cancelled'";
$result = $conn->query($sql);

$bookings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // แปลงเวลาให้เป็นรูปแบบ H:i
        $formattedTime = date("H:i", strtotime($row['booking_time'])); 
        // จัดรูปแบบให้เป็น ISO 8601 (YYYY-MM-DDTHH:MM:SS)
        $formattedStartTime = $row['booking_date'] . 'T' . $formattedTime . ':00';
        // เพิ่มข้อมูลการจองลงใน JSON
        $bookings[] = [
            'title' => 'จองแล้ว',
            'start' => $formattedStartTime,
            'color' => 'red'
        ];
    }
}

echo json_encode($bookings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
?>
