<?php
session_start();
include 'connect.php'; // ใช้ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password, username, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role']; 

            // ✅ กำหนดเส้นทางไปหน้าที่ถูกต้อง พร้อมส่ง `status=success`
            if ($row['role'] === 'admin') {
                header("Location: dashboard.php?status=success");
                exit();
            } elseif ($row['role'] === 'staff') {//ในส่วนของ staff ยังไม่ได้ทำหน้ารองรับไว้
                header("Location: staff_dashboard.php?status=success");
                exit();
            } else {
                header("Location: homepage.php?status=success");
                exit();
            }
        } else {
            // ❌ รหัสผ่านผิด ส่ง error ไป login.php
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        // ❌ ไม่พบชื่อผู้ใช้
        header("Location: login.php?error=invalid_username");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
