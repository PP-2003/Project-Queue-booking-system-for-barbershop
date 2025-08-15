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

include 'db_connect.php';

// ดึงข้อมูลการจองจากฐานข้อมูล โดยใช้ queue_number เป็นหลัก
$sql = "
    SELECT 
        queue_number,
        name,
        phone,
        booking_date,
        booking_time,
        services,
        slip_path,
        status
    FROM bookings
    ORDER BY queue_number ASC
";

$result = $conn->query($sql);
if (!$result) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูล: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แสดงข้อมูลการจองคิว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            font-family: 'Arial', sans-serif;
        }
        .navbar-custom {
            background-color: #6a1b9a;
            padding: 15px;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .nav-link {
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .btn-logout {
            background-color: #d9534f;
            color: white !important;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #c9302c;
        }
        /* จัดข้อความให้อยู่กลางทั้งหัวตารางและข้อมูล */
        table th, table td {
            text-align: center;
            vertical-align: middle;
        }
        .badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">Edit information</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link bg-primary text-white" href="dashboard.php"> ย้อนกลับ</a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <h1 class="header-title text-center">ข้อมูลการจองคิว</h1>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>หมายเลขคิว</th>
                        <th>ชื่อ</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>วันที่จอง</th>
                        <th>เวลาจอง</th>
                        <th>บริการ</th>
                        <th>Slip</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php
                        $counter = 1;
                        while ($row = $result->fetch_assoc()):
                            $status = $row['status'];
                            // กำหนด badge class ตามสถานะ
                            $badgeClass = 'badge bg-secondary'; // default
                            if ($status === 'active') {
                                $badgeClass = 'badge bg-success';
                            } else if ($status === 'cancelled') {
                                $badgeClass = 'badge bg-danger';
                            }
                        ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($row['queue_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['services']); ?></td>
                                <td>
                                    <?php
                                    if (!empty($row['slip_path'])) {
                                        echo '<a href="'.htmlspecialchars($row['slip_path']).'" target="_blank">ดูสลิป</a>';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- เมื่อคลิกที่ badge จะเรียก toggleStatus() -->
                                    <span class="<?php echo $badgeClass; ?>" 
                                          onclick="toggleStatus('<?php echo $row['queue_number']; ?>', '<?php echo $status; ?>')">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_booking.php?queue_number=<?php echo urlencode($row['queue_number']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> แก้ไข
                                    </a>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row['queue_number']; ?>')">
                                        <i class="fas fa-trash"></i> ลบ
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">ไม่มีข้อมูลการจอง</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

   

    <!-- ฟังก์ชันสำหรับยืนยันการลบ -->
    <script>
        function confirmDelete(queue_number) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบการจองนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_booking.php?queue_number=' + encodeURIComponent(queue_number);
                }
            });
        }
    </script>

    <!-- ฟังก์ชันสำหรับเปลี่ยนสถานะ (toggle) -->
    <script>
        function toggleStatus(queue_number, currentStatus) {
            let newStatus = currentStatus === 'active' ? 'cancelled' : 'active';
            let confirmText = currentStatus === 'active' ? 
                "คุณต้องการเปลี่ยนสถานะจาก active เป็น cancelled หรือไม่?" : 
                "คุณต้องการเปลี่ยนสถานะจาก cancelled เป็น active หรือไม่?";
            
            Swal.fire({
                title: 'เปลี่ยนสถานะ?',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เปลี่ยนสถานะ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งคำขอผ่าน AJAX เพื่ออัปเดตสถานะ
                    fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            queue_number: queue_number,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: data.error,
                                confirmButtonText: 'ตกลง'
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: data.message,
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: error.toString(),
                            confirmButtonText: 'ตกลง'
                        });
                    });
                }
            });
        }
    </script>

    <!-- FontAwesome และ Bootstrap JS -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
