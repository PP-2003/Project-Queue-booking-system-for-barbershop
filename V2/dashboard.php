<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึงสำหรับ admin หรือ staff
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แดชบอร์ดผู้ดูแลระบบ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pap8+gV7mbG4QrW8UQmGKyhKgkaN+xIYQ6b8W6MFnOQgOVYFW6+YSr67Oe3hJb90XtbSd+6PZ0YZdD9hGbIFug==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      background: #f1f5f9;
      font-family: 'Arial', sans-serif;
    }
    /* Modern Navbar */
    .navbar {
      background: linear-gradient(90deg, #6a1b9a, #8e24aa);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.8rem;
    }
    .navbar-nav .nav-link {
      font-size: 1.1rem;
      margin-right: 10px;
    }
    .navbar-nav .nav-link i {
      margin-right: 5px;
    }
    /* Card Styling */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .card-title {
      font-size: 1.4rem;
      font-weight: bold;
    }
    /* Footer */
    footer {
      background-color: #6a1b9a;
      color: #fff;
      padding: 15px 0;
      text-align: center;
      margin-top: 30px;
      border-radius: 0 0 12px 12px;
    }
    /* List Group (Recent Activity) */
    .list-group-item {
      font-size: 1rem;
      transition: background 0.3s ease;
    }
    .list-group-item:hover {
      background: #f8f9fa;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, #6a1b9a, #8e24aa);">
  <div class="container">
    <a class="navbar-brand text-white" href="#">
      <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="ms-auto">
        <a class="btn btn-primary me-2" href="view.php">
          <i class="fa-solid fa-table"></i> ดูการจอง
        </a>     
        <a class="btn btn-danger" href="logout.php">
          <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
        </a>
      </div>
    </div>
  </div>
</nav>


  <!-- Dashboard Content -->
  <div class="container my-5">
    <h2 class="mb-4">สรุปข้อมูลการจองคิว</h2>
    <div class="row">
      <!-- การจองทั้งหมด -->
      <div class="col-md-4">
        <div class="card text-white bg-success">
          <div class="card-body text-center">
            <h5 class="card-title"><i class="fa-solid fa-list"></i> การจองทั้งหมด</h5>
            <p class="card-text display-4" id="totalBookings">0</p>
          </div>
        </div>
      </div>
      <!-- การจองที่ใช้งาน -->
      <div class="col-md-4">
        <div class="card text-white bg-primary">
          <div class="card-body text-center">
            <h5 class="card-title"><i class="fa-solid fa-check"></i> การจองที่ใช้งาน</h5>
            <p class="card-text display-4" id="activeBookings">0</p>
          </div>
        </div>
      </div>
      <!-- การจองที่ถูกยกเลิก -->
      <div class="col-md-4">
        <div class="card text-white bg-danger">
          <div class="card-body text-center">
            <h5 class="card-title"><i class="fa-solid fa-xmark"></i> การจองที่ถูกยกเลิก</h5>
            <p class="card-text display-4" id="cancelledBookings">0</p>
          </div>
        </div>
      </div>
    </div>
    
    <hr>
    
    <h2 class="mb-4">กิจกรรมล่าสุด</h2>
    <div class="list-group" id="recentActivity">
      <p class="text-muted">กำลังโหลดข้อมูล...</p>
    </div>
  </div>
   
  <footer>
    <div class="container">
    <p>&copy; Baanpom Barber Shop. All Rights Reserved.</p>
   </footer>

  <!-- SweetAlert2 แจ้งเตือนเข้าสู่ระบบ -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // ตรวจสอบ query parameter status เพื่อแสดง SweetAlert2 เมื่อเข้าสู่ระบบสำเร็จ
    const urlParams = new URLSearchParams(window.location.search);
    const loginStatus = urlParams.get('status');
    if (loginStatus === "success") {
      Swal.fire({
        title: "เข้าสู่ระบบสำเร็จ!",
        text: "ยินดีต้อนรับ, ผู้ดูแลระบบ",
        icon: "success",
        confirmButtonText: "ตกลง"
      }).then(() => {
        history.replaceState(null, null, window.location.pathname);
      });
    }
  </script>

  <!-- ฟังก์ชันดึงข้อมูลแดชบอร์ด -->
  <script>
    function fetchDashboardData() {
      fetch('get_dashboard_data.php')
        .then(response => response.json())
        .then(data => {
          document.getElementById('totalBookings').textContent = data.total;
          document.getElementById('activeBookings').textContent = data.active;
          document.getElementById('cancelledBookings').textContent = data.cancelled;
          
          let recentHTML = "";
          if (data.recent.length > 0) {
            data.recent.forEach(activity => {
              recentHTML += `<a href="#" class="list-group-item list-group-item-action">
                                <i class="fa-solid fa-user"></i> ${activity.name} (Queue: ${activity.queue_number}) <br>
                                <small>จองวันที่ ${activity.booking_date} เวลา ${activity.booking_time} [${activity.status}]</small>
                              </a>`;
            });
          } else {
            recentHTML = `<p class="text-muted">ไม่มีข้อมูลกิจกรรมล่าสุด</p>`;
          }
          document.getElementById('recentActivity').innerHTML = recentHTML;
        })
        .catch(error => {
          console.error('Error fetching dashboard data:', error);
        });
    }

    // เรียกข้อมูลเมื่อโหลดหน้าและรีเฟรชทุก ๆ 10 วินาที
    fetchDashboardData();
    setInterval(fetchDashboardData, 10000);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
