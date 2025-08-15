<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึงสำหรับ admin หรือ staff
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
    exit();
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (!isset($_GET['id'])) {
    echo "ไม่มีข้อมูล ID สำหรับลบ";
    exit();
}

include 'db_connect.php';

$id = intval($_GET['id']); // กำหนดให้เป็นตัวเลขเพื่อความปลอดภัย

// คำสั่ง SQL สำหรับลบข้อมูลจากตาราง bookings
$sql = "DELETE FROM bookings WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // เมื่อการลบสำเร็จ ให้เปลี่ยนกลับไปยังหน้า view.php พร้อมส่งสถานะ
    header("Location: view.php?status=deleted");
    exit();
} else {
    echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . $conn->error;
}

$conn->close();
?>
