<?php
// ✅ ป้องกัน session_start() ซ้ำซ้อน
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; // ชื่อผู้ใช้ของ MySQL
$password = ""; // รหัสผ่านของ MySQL
$dbname = "barber1"; // ชื่อฐานข้อมูลที่ใช้

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
