<?php
include 'db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ฟังก์ชันส่งข้อความแจ้งเตือนผ่าน LINE Messaging API
function sendLineMessage($recipients, $message) {
    $accessToken = "KrVQ86k1lMimcrVRLL8WRrCjpqU6pM855tPD9Fs/h4yvSXDt2KW++lvXfiRXzjI2v/LeC7a60qOr+/fAtZ9gVBuPlWM3OV1Ej60JThwkMRj03PIXSPVIDZ58GBnyN91JFo5tuM7zkPeZVClMgRt3JwdB04t89/1O/w1cDnyilFU=";
    $url = "https://api.line.me/v2/bot/message/push";

    foreach ($recipients as $to) {
        $data = [
            "to" => $to,
            "messages" => [[
                "type" => "text",
                "text" => $message
            ]]
        ];

        $postData = json_encode($data);
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $accessToken
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($result === false) {
            error_log("Error sending message to $to: " . curl_error($ch));
        } else if ($httpCode !== 200) {
            error_log("LINE API responded with HTTP code: $httpCode, response: $result");
        }
        curl_close($ch);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name         = $_POST['name'] ?? "";
    $phone        = $_POST['phone'] ?? "";
    $booking_date = $_POST['date'] ?? "";
    $booking_time = $_POST['time'] ?? "";
    $services     = isset($_POST['service']) ? implode(', ', $_POST['service']) : "ไม่ได้เลือกบริการ";
    
    // รับค่า queue_number จากฟอร์ม (hidden input)
    $queue_number = $_POST['queue_number'] ?? "";
    // หากไม่มีค่า queue_number ให้สุ่มค่าใหม่
    if (empty($queue_number)) {
        $randomNum = rand(1, 999);
        $queue_number = "A" . str_pad($randomNum, 3, '0', STR_PAD_LEFT);
    }

    // Debug: ตรวจสอบค่าที่ได้รับ
    error_log("DEBUG name: " . $name);
    error_log("DEBUG phone: " . $phone);
    error_log("DEBUG booking_date: " . $booking_date);
    error_log("DEBUG booking_time: " . $booking_time);
    error_log("DEBUG queue_number: " . $queue_number);

    // ตรวจสอบว่าข้อมูลครบถ้วนหรือไม่
    if (!$name || !$phone || !$booking_date || !$booking_time || !$queue_number) {
        die(json_encode(["error" => "กรุณากรอกข้อมูลให้ครบถ้วน"]));
    }

    // INSERT ข้อมูลลงในตาราง bookings (รวม queue_number)
    $sql = "INSERT INTO bookings (name, phone, booking_date, booking_time, services, queue_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(["error" => "Prepare statement ล้มเหลว: " . $conn->error]));
    }
    $stmt->bind_param("ssssss", $name, $phone, $booking_date, $booking_time, $services, $queue_number);

    if ($stmt->execute()) {
        // ดึงค่า booking_id ที่เพิ่ง insert (Primary Key แบบ AUTO_INCREMENT)
        $booking_id = $stmt->insert_id;

        // ส่งข้อความแจ้งเตือนผ่าน LINE
        $recipients = [
            "U698f383ea47433b801cdc3fe05e91325", // User ID
            "Cadff91012e1af5f97cf8a26d0b778135"  // Group ID
        ];
        $message = "📢 แจ้งเตือนการจองคิวตัดผม\n"
                 . "👤 ลูกค้า: $name\n"
                 . "🔢 หมายเลขคิว: $queue_number\n"
                 . "📞 เบอร์โทร: $phone\n"
                 . "📅 วันที่: $booking_date\n"
                 . "⏰ เวลา: $booking_time\n"
                 . "💈 บริการ: $services\n";
        sendLineMessage($recipients, $message);

        // Redirect ไปยัง booking_card.html พร้อม query parameters (ไม่ส่ง booking_id หากไม่จำเป็น)
        header("Location: booking_card.html?"
             . "queue_number=" . urlencode($queue_number)
             . "&name=" . urlencode($name)
             . "&phone=" . urlencode($phone)
             . "&date=" . urlencode($booking_date)
             . "&time=" . urlencode($booking_time)
             . "&services=" . urlencode($services)
        );
        exit();
    } else {
        die(json_encode(["error" => "การจองล้มเหลว: " . $stmt->error]));
    }

    $stmt->close();
    $conn->close();
}
?>
