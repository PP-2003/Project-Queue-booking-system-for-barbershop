<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div style='text-align: center; margin-top: 50px;'>
            <h1>คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h1>
            <a href='login.php' class='btn btn-primary'>กลับไปยังหน้าเข้าสู่ระบบ</a>
          </div>";
    exit();
}

// ตรวจสอบว่ามี queue_number ใน URL หรือไม่
if (!isset($_GET['queue_number'])) {
    echo "<script>
            alert('ไม่พบข้อมูลที่ต้องการแก้ไข!');
            window.location.href = 'view.php';
          </script>";
    exit();
}

$queue_number = trim($_GET['queue_number']);

include 'db_connect.php';

// ดึงข้อมูลการจองจากตาราง bookings ตาม queue_number
$stmt = $conn->prepare("SELECT name, phone, booking_date, booking_time, services FROM bookings WHERE queue_number = ? LIMIT 1");
$stmt->bind_param("s", $queue_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>
            alert('ไม่พบข้อมูลที่ต้องการแก้ไข!');
            window.location.href = 'view.php';
          </script>";
    exit();
}

$booking = $result->fetch_assoc();
$stmt->close();

// หากมีการส่งข้อมูล POST เพื่อแก้ไข
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = $_POST['name']          ?? "";
    $phone         = $_POST['phone']         ?? "";
    $booking_date  = $_POST['booking_date']  ?? "";
    $booking_time  = $_POST['booking_time']  ?? "";
    $services      = $_POST['services']      ?? "";

    // เตรียมคำสั่ง UPDATE
    $update_stmt = $conn->prepare("
        UPDATE bookings
        SET name = ?,
            phone = ?,
            booking_date = ?,
            booking_time = ?,
            services = ?
        WHERE queue_number = ?
    ");
    $update_stmt->bind_param("ssssss", 
        $name, 
        $phone, 
        $booking_date, 
        $booking_time, 
        $services, 
        $queue_number
    );

    if ($update_stmt->execute()) {
        // แสดง SweetAlert2 เมื่อแก้ไขสำเร็จ และ redirect ไปหน้า view.php
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'ข้อมูลได้รับการแก้ไขแล้ว',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = 'view.php';
                    });
                });
              </script>";
    } else {
        // แสดง SweetAlert2 เมื่อแก้ไขล้มเหลว
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'ข้อผิดพลาด!',
                        text: 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                });
              </script>";
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลการจอง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #dfe9f3, #ffffff);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #6c63ff;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #4b47b7;
        }
        .btn-secondary {
            background-color: #bbbbbb;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #999999;
        }
        .header-title {
            font-size: 2rem;
            font-weight: bold;
            color: #6c63ff;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header-title">แก้ไขข้อมูลการจอง (Queue: <?php echo htmlspecialchars($queue_number); ?>)</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ:</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($booking['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทร:</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                       value="<?php echo htmlspecialchars($booking['phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="booking_date" class="form-label">วันที่จอง:</label>
                <input type="date" class="form-control" id="booking_date" name="booking_date"
                       value="<?php echo htmlspecialchars($booking['booking_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="booking_time" class="form-label">เวลาที่จอง:</label>
                <input type="time" class="form-control" id="booking_time" name="booking_time"
                       value="<?php echo htmlspecialchars($booking['booking_time']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="services" class="form-label">บริการ:</label>
                <input type="text" class="form-control" id="services" name="services"
                       value="<?php echo htmlspecialchars($booking['services']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">บันทึกการแก้ไข</button>
            <a href="view.php" class="btn btn-secondary w-100 mt-3">ยกเลิก</a>
        </form>
    </div>
</body>
</html>
