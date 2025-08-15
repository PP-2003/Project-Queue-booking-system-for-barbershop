<?php session_start(); ?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-image: url('pictures/bg2.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.938);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h3 {
            color: #a26fd0;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: #e1b0f7;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background: #d18aee;
        }
        .login-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .login-footer a {
            color: #a26fd0;
            text-decoration: none;
            font-weight: bold;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .tagline {
            color: #a26fd0;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="pictures/Baanpom.jpg" alt="โลโก้ร้าน" class="logo">
        <h3>เข้าสู่ระบบ</h3>
        <p class="tagline">ลงทะเบียนจองคิวตัดผมออนไลน์ง่ายๆ</p>
        <form action="login_process.php" method="POST">
            <div class="mb-3 text-start">
                <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
        </form>
        <div class="login-footer">
            <p>ยังไม่มีบัญชี? <a href="Apply.php">สมัครสมาชิก</a></p>
        </div>
    </div>

    <script>
    // ✅ ตรวจสอบว่ามี error จาก login_process.php หรือไม่
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
        let errorMessage = "เกิดข้อผิดพลาด";
        if (error === "invalid_password") errorMessage = "รหัสผ่านไม่ถูกต้อง!";
        if (error === "invalid_username") errorMessage = "ชื่อผู้ใช้ไม่ถูกต้อง!";

        Swal.fire({
            title: "ข้อผิดพลาด",
            text: errorMessage,
            icon: "error",
            confirmButtonText: "ตกลง"
        });
    }
</script>

</body>
</html>
