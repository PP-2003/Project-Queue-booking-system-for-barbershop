<?php
$accessToken = "KrVQ86k1lMimcrVRLL8WRrCjpqU6pM855tPD9Fs/h4yvSXDt2KW++lvXfiRXzjI2v/LeC7a60qOr+/fAtZ9gVBuPlWM3OV1Ej60JThwkMRj03PIXSPVIDZ58GBnyN91JFo5tuM7zkPeZVClMgRt3JwdB04t89/1O/w1cDnyilFU="; // ใส่ Channel Access Token ของคุณ

// ตรวจสอบว่าเป็นคำขอแบบ POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}

// รับข้อมูล JSON
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error decoding JSON: " . json_last_error_msg());
    http_response_code(400);
    exit("Invalid JSON");
}

// ตรวจสอบข้อมูลที่ได้รับ
if (!empty($data["events"])) {
    $source = $data["events"][0]["source"];
    if (isset($source["groupId"])) {
        $groupId = $source["groupId"];

        // บันทึก Group ID ลงในไฟล์
        if (file_put_contents("group_id.txt", $groupId) === false) {
            error_log("Failed to write group ID to file");
            http_response_code(500);
            exit("Internal Server Error");
        }

        // ตอบกลับข้อความไปยังกลุ่ม
        $replyToken = $data["events"][0]["replyToken"];
        $responseMessage = "Group ID ของคุณคือ: $groupId ถูกบันทึกแล้ว!";

        $response = [
            "replyToken" => $replyToken,
            "messages" => [[
                "type" => "text",
                "text" => $responseMessage
            ]]
        ];

        $ch = curl_init("https://api.line.me/v2/bot/message/reply");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));

        $result = curl_exec($ch);

        if ($result === false) {
            error_log("Curl Error: " . curl_error($ch));
            http_response_code(500);
            exit("Curl Error");
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            error_log("LINE API responded with HTTP Code: $httpCode");
        }

        curl_close($ch);
    } else {
        error_log("Group ID not found in the received data");
    }
} else {
    error_log("No events found in the received data");
}

http_response_code(200);
echo "OK";
?>
