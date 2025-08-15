<?php
session_start();
include 'db_connect.php';

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

// รับค่า queue_number จาก query string
$queue_number = isset($_GET['queue_number']) ? $_GET['queue_number'] : "";
if (empty($queue_number)) {
    echo "ไม่พบเลขคิวที่ต้องการยกเลิก";
    exit();
}

// ตัวอย่าง: ยกเลิกการจองโดยการ update สถานะในฐานข้อมูล
$sql = "UPDATE bookings SET status = 'cancelled' WHERE queue_number = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit();
}
$stmt->bind_param("s", $queue_number);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // ส่งข้อความแจ้งเตือนการยกเลิกผ่าน LINE
    $recipients = [
        "U698f383ea47433b801cdc3fe05e91325", // User ID
        "Cadff91012e1af5f97cf8a26d0b778135"   // Group ID
    ];
    $message = "🚫 การจองคิวตัดผมถูกยกเลิกแล้ว\nหมายเลขคิว: $queue_number";
    sendLineMessage($recipients, $message);

    // Redirect ไปหน้า homepage.php พร้อมพารามิเตอร์แจ้งเตือน (หากต้องการ)
    header("Location: homepage.php?status=cancel_success");
    exit();
} else {
    echo "ไม่สามารถยกเลิกการจองได้";
}

$stmt->close();
$conn->close();
?>
