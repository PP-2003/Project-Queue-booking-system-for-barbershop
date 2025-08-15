<?php
session_start();
include 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

// กำหนดให้ไฟล์นี้ส่งผลลัพธ์เป็น JSON
header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบว่ามีการอัพโหลดไฟล์และไม่มีข้อผิดพลาด
if (isset($_FILES['slip']) && $_FILES['slip']['error'] == 0) {
    // กำหนดโฟลเดอร์สำหรับเก็บไฟล์สลิป
    $uploadDir = "uploads/";
    // สร้างชื่อไฟล์ใหม่ (ตัวอย่างนี้ใช้ basename)
    $fileName = basename($_FILES["slip"]["name"]);
    $targetFile = $uploadDir . $fileName;
    
    // ย้ายไฟล์ที่อัพโหลดไปยังโฟลเดอร์ที่กำหนด
    if (move_uploaded_file($_FILES["slip"]["tmp_name"], $targetFile)) {
        // รับค่า queue_number ที่ส่งมาจากฟอร์ม
        $queue_number = isset($_POST['queue_number']) ? $_POST['queue_number'] : "";
        
        // ตรวจสอบว่าค่า queue_number ไม่ว่าง
        if ($queue_number == "") {
            echo json_encode([
                "error" => true,
                "error" => "ไม่พบค่าเลขคิวที่ส่งมา"
            ]);
            exit();
        }
        
        // แทนที่จะ INSERT เราใช้ UPDATE เพื่ออัปเดต slip_path ใน record เดิม
        $sql = "UPDATE bookings
                SET slip_path = ?
                WHERE queue_number = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode([
                "error" => true,
                "error" => "Prepare statement ล้มเหลว: " . $conn->error
            ]);
            exit();
        }
        $stmt->bind_param("ss", $targetFile, $queue_number);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "error" => false,
                "message" => "อัพโหลดสลิปและบันทึกข้อมูลสำเร็จ!"
            ]);
        } else {
            echo json_encode([
                "error" => true,
                "error" => "ไม่พบ queue_number หรือไม่สามารถอัปเดต slip_path ได้"
            ]);
        }
        
        $stmt->close();
    } else {
        echo json_encode([
            "error" => true,
            "error" => "ไม่สามารถย้ายไฟล์ไปยังโฟลเดอร์ uploads ได้"
        ]);
    }
} else {
    echo json_encode([
        "error" => true,
        "error" => "ไม่พบไฟล์สลิปที่อัพโหลด หรือมีข้อผิดพลาดในการอัพโหลด"
    ]);
}

$conn->close();
?>
