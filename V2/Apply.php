<?php
$message = ""; 
$alertType = ""; 
$alertScript = "";

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'barber1');
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    $usernameError = $emailError = $phoneError = "";

    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
        $alertType = "error";
    } else {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $usernameError = "ชื่อผู้ใช้ต้องมีเฉพาะตัวอักษร ตัวเลข และเครื่องหมายขีดล่างเท่านั้น";
            $alertType = "error";
        } elseif (strlen($username) > 50) {
            $usernameError = "ชื่อผู้ใช้ต้องมีความยาวไม่เกิน 50 ตัวอักษร";
            $alertType = "error";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "อีเมลไม่ถูกต้อง";
            $alertType = "error";
        } elseif (strlen($email) > 100) {
            $emailError = "อีเมลต้องมีความยาวไม่เกิน 100 ตัวอักษร";
            $alertType = "error";
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $phoneError = "เบอร์โทรศัพท์ต้องมี 10 หลัก";
            $alertType = "error";
        } elseif ($password !== $confirm_password) {
            $message = "รหัสผ่านไม่ตรงกัน";
            $alertType = "error";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $message = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร และมีตัวพิมพ์ใหญ่ ตัวเล็ก ตัวเลข และอักขระพิเศษ";
            $alertType = "error";
        } else {
            // ตรวจสอบข้อมูลในฐานข้อมูล
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?");
            $stmt->bind_param("sss", $username, $email, $phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $existingData = $result->fetch_assoc();
                if ($existingData['username'] === $username) {
                    $usernameError = "ชื่อผู้ใช้นี้มีอยู่แล้ว";
                }
                if ($existingData['email'] === $email) {
                    $emailError = "อีเมลนี้มีอยู่แล้ว";
                }
                if ($existingData['phone'] === $phone) {
                    $phoneError = "เบอร์โทรศัพท์นี้มีอยู่แล้ว";
                }
                $alertType = "error";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

                if ($stmt->execute()) {
                    $message = "สมัครสมาชิกสำเร็จ";
                    $alertType = "success";
                    $alertScript = "Swal.fire({title: 'สำเร็จ!', text: '$message', icon: 'success', confirmButtonText: 'ตกลง'}).then(() => {window.location.href = 'login.php';});";
                } else {
                    $message = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
                    $alertType = "error";
                }
            }
            $stmt->close();
        }
    }

    if (!empty($message) && empty($alertScript)) {
        $alertScript = "Swal.fire({title: '" . ($alertType === "success" ? "สำเร็จ!" : "ข้อผิดพลาด") . "', text: '$message', icon: '$alertType', confirmButtonText: 'ตกลง'});";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-image: url('Pictures/bg7.jpg'); 
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.932);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .register-container h3 {
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
        .register-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .register-footer a {
            color: #a26fd0;
            text-decoration: none;
            font-weight: bold;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-top: 20px;
        }
        .error {
            color: red;
        }
        .eye-icon {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="pictures/Baanpom.jpg" alt="โลโก้ร้าน" class="logo">
        <h3>สมัครสมาชิก</h3>
        <form action="Apply.php" method="POST" id="registerForm">
        <div class="mb-3 text-start">
    <label for="username" class="form-label">ชื่อผู้ใช้:</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้" required>
    <span id="usernameError" class="error" style="color: red;"><?php echo isset($usernameError) ? $usernameError : ''; ?></span>
    </div>
        <div class="mb-3 text-start">
    <label for="email" class="form-label">อีเมล:</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="อีเมล" required>
    <span id="emailError" class="error" style="color: red;"><?php echo isset($emailError) ? $emailError : ''; ?></span>
    </div>
        <div class="mb-3 text-start">
    <label for="phone" class="form-label">เบอร์โทรศัพท์:</label>
    <input type="tel" class="form-control" id="phone" name="phone" placeholder="เบอร์โทรศัพท์" required>
    <span id="phoneError" class="error" style="color: red;"><?php echo isset($phoneError) ? $phoneError : ''; ?></span>
    </div>
        <div class="mb-3 text-start">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required>
                    <span class="input-group-text eye-icon" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <span id="passwordError" class="error"></span>
            </div>
            <div class="mb-3 text-start">
                <label for="confirm-password" class="form-label">ยืนยันรหัสผ่าน:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="ยืนยันรหัสผ่าน" required>
                    <span class="input-group-text eye-icon" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <span id="confirmPasswordError" class="error"></span>
            </div>
            <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
        </form>
        <div class="register-footer">
            <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm-password');
            const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
            confirmPasswordField.type = type;
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('username').addEventListener('input', function () {
        const username = this.value;
        const usernameError = document.getElementById('usernameError');
        const usernamePattern = /^[a-zA-Z0-9_]+$/;

        if (!usernamePattern.test(username)) {
            usernameError.textContent = 'ชื่อผู้ใช้ต้องมีเฉพาะตัวอักษรภาษาอังกฤษ, ตัวเลข, และ "_"';
        } else if (username.length > 50) {
            usernameError.textContent = 'ชื่อผู้ใช้ต้องไม่เกิน 50 ตัวอักษร';
        } else {
            usernameError.textContent = '';
        }
    });

        document.getElementById('email').addEventListener('input', function() {
            var email = document.getElementById('email').value;
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                document.getElementById('emailError').textContent = 'อีเมลไม่ถูกต้อง';
            } else {
                document.getElementById('emailError').textContent = '';
            }
        });

        document.getElementById('phone').addEventListener('input', function() {
            var phone = document.getElementById('phone').value;
            var phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone)) {
                document.getElementById('phoneError').textContent = 'เบอร์โทรศัพท์ต้องมี 10 หลัก';
            } else {
                document.getElementById('phoneError').textContent = '';
            }
        });

        document.getElementById('password').addEventListener('input', function () {
        const password = this.value;
        const passwordError = document.getElementById('passwordError');
        const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!passwordPattern.test(password)) {
            passwordError.textContent =
                'รหัสผ่านต้องมีอย่างน้อย 8 ตัว ประกอบด้วยตัวพิมพ์ใหญ่ ตัวเล็ก ตัวเลข และสัญลักษณ์พิเศษ';
        } else {
            passwordError.textContent = '';
        }
    });

        document.getElementById('confirm-password').addEventListener('input', function() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
            } else {
                document.getElementById('confirmPasswordError').textContent = '';
            }
        });
    </script>

    <script>
        <?php echo $alertScript; ?>
    </script>
</body>
</html>
