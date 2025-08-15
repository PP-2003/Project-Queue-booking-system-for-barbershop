<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null;
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baanpom Barber Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="description" content="Baanpom Barber Shop - ร้านตัดผมคุณภาพสูง">
    <meta name="keywords" content="ตัดผม, ร้านตัดผม, Barber Shop, Baanpom">
    <meta name="author" content="Baanpom Barber Shop">
    <meta property="og:title" content="Baanpom Barber Shop">
    <meta property="og:description" content="บริการตัดผมคุณภาพสูง ใส่ใจในทุกรายละเอียด">
    <meta property="og:image" content="Pictures/Baanpom.jpg">
    <meta property="og:url" content="https://yourwebsite.com">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }
    
        /* Navbar */
        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
        }
    
        .navbar-nav .nav-link {
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            transition: color 0.3s;
        }
    
        .navbar-nav .nav-link:hover {
            color: #ffccff;
        }
    
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
    
        .navbar-brand:hover {
            color: #ffccff;
        }
    
        .btn-login {
            background-color: #33FF66;
            color: #000000;
            font-weight: bold;
            border: 1px solid black;
            transition: background-color 0.3s, color 0.3s;
            padding: 10px 20px;
            border-radius: 5px;
        }
    
        .btn-login:hover {
            background-color: #2E8B57;
            color: #000000;
        }
    
        .btn-logout {
            background-color: #FF6666;
            color: white;
            font-weight: bold;
            border: 1px solid black;
            transition: background-color 0.3s;
            padding: 10px 20px;
            border-radius: 5px;
        }
    
        .btn-logout:hover {
            background-color: #D9534F;
        }
    
        .btn-reserve {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
            border: 1px solid black;
            transition: background-color 0.3s;
            padding: 10px 20px;
            border-radius: 5px;
        }
    
        .btn-reserve:hover {
            background-color: #0056b3;
        }
    
        .hero {
            height: 80vh;
            min-height: 400px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('Pictures/Baanpom.jpg') center/cover no-repeat;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            background-attachment: fixed;
            padding: 0 20px;
            box-shadow: inset 0 0 100px rgba(0, 0, 0, 0.5);
        }
    
        @media (max-width: 768px) {
            .hero {
                height: auto;
                padding: 50px 20px;
                background-size: cover;
                background-attachment: scroll;
            }
        }
    
        .hero h1 {
            font-size: 4rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }
    
        .hero p {
            font-size: 1.5rem;
            margin-top: 15px;
            max-width: 600px;
        }
    
        /* About Section */
        .about {
            padding: 80px 20px;
            text-align: center;
        }
    
        .about img {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
        }
    
        /* Services Section */
        .services {
            background: #f9f9f9;
            padding: 80px 20px;
        }
    
        .service-item {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.3s;
        }
    
        .service-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        /* Gallery Section */
        .gallery {
            padding: 80px 20px;
        }
    
        .gallery img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
    
        .gallery img:hover {
            transform: scale(1.05);
        }
    
        /* Contact Section */
        .contact {
            padding: 80px 20px;
            background: #8f7bbf;
            color: white;
            text-align: center;
        }
    
        .contact a {
            color: #ffccff;
            font-weight: bold;
            text-decoration: none;
        }
    
        .contact a:hover {
            text-decoration: underline;
        }
    
        /* Footer */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
    
        .footer a {
            color: #ffccff;
            text-decoration: none;
        }
    
        .footer a:hover {
            text-decoration: underline;
        }
    
        /* Scroll-To-Top Button */
        #scrollTop {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
        }
    </style>    
</head>

<body>
    <!-- ✅ Navbar  -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Baanpom</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">เกี่ยวกับเรา</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">บริการ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gallery">แกลเลอรี่</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">ติดต่อ</a></li>

                <!-- 🔹 ตรวจสอบสถานะการล็อกอิน -->
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link btn-reserve" href="reserve.html">จองคิว</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-logout" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn-login" href="login.php">เข้าสู่ระบบ</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- ✅ Hero Section -->
<section class="hero" id="hero">
    <div class="text-center">
        <h1>Welcome to Baanpom Barber Shop</h1>
        <p>บริการตัดผมคุณภาพสูง ใส่ใจในทุกรายละเอียด</p>
    </div>
</section>

    <!-- About Section -->
    <section class="about" id="about">
        <h2>เกี่ยวกับเรา</h2>
        <p>ร้านตัดผม Baanpom Barber Shop มุ่งเน้นบริการที่มีคุณภาพ และการดูแลลูกค้าด้วยใจ</p>
        <div class="d-flex justify-content-center gap-4">
            <div class="about-image">
                <img src="Pictures/show1.jpg" class="img-fluid rounded" alt="Image 1">
            </div>
            <div class="about-image">
                <img src="Pictures/show5.jpg" class="img-fluid rounded" alt="Image 2">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <h2 class="text-center">บริการของเรา</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="service-item">
                        <i class="fas fa-cut fa-3x"></i>
                        <h5 class="mt-3">ตัดผม</h5>
                        <p>บริการตัดผมทุกทรงสำหรับเพศชายทุกวัย</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <i class="fas fa-spa fa-3x"></i>
                        <h5 class="mt-3">นวดหน้า</h5>
                        <p>บริการเสริมเพื่อสุขภาพผิวหน้าที่ดี</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-item">
                        <i class="fas fa-tint fa-3x"></i>
                        <h5 class="mt-3">ย้อมสีผม</h5>
                        <p>เปลี่ยนสีผมให้ดูทันสมัยและมีสไตล์</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery" id="gallery">
        <div class="container">
            <h2 class="text-center">แกลเลอรี่</h2>
            <div class="row">
                <div class="col-md-4"><img src="Pictures/show4.jpg" alt="Haircut 1"></div>
                <div class="col-md-4"><img src="Pictures/show2.jpg" alt="Haircut 2"></div>
                <div class="col-md-4"><img src="Pictures/show3.jpg" alt="Haircut 3"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2>ติดต่อเรา</h2>
        <p>โทร: 061-2456936 | อีเมล: tachakorn.bon@gmail.com</p>
        <p>เวลาทำการ: ทุกวัน 09:00 - 20:00</p>

        <!-- Google Map -->
        <div class="mt-4">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3876.047843036269!2d99.96539827429048!3d13.08433881356702!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30fd275afce94029%3A0x46716b6f769c4f36!2z4Lij4LiH4LmI4Lia4LiZ4LiZ4Liq4Lii!5e0!3m2!1sth!2sth!4v1673570186402!5m2!1sth!2sth" 
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </section>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; Baanpom Barber Shop. All Rights Reserved.</p>
        </footer>
    
        <!-- Scroll-To-Top Button -->
        <button id="scrollTop" class="btn btn-primary">⬆</button>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const scrollTopButton = document.getElementById("scrollTop");
            window.onscroll = () => {
                if (document.documentElement.scrollTop > 100) {
                    scrollTopButton.style.display = "block";
                } else {
                    scrollTopButton.style.display = "none";
                }
            };
            scrollTopButton.onclick = () => {
                window.scrollTo({ top: 0, behavior: "smooth" });
            };
    
            // ✅ ตรวจสอบว่ามี status=success ใน URL หรือไม่
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
    
            if (status === "success") {
                Swal.fire({
                    title: "เข้าสู่ระบบสำเร็จ!",
                    text: "ยินดีต้อนรับสู่ระบบ",
                    icon: "success",
                    confirmButtonText: "ตกลง"
                }).then(() => {
                    // ✅ ล้างค่าพารามิเตอร์จาก URL เพื่อไม่ให้แสดงซ้ำ
                    history.replaceState(null, null, window.location.pathname);
                });
            }
        </script>
    </body>
    </html>
    
