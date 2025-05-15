<?php
header('Content-Type: application/json; charset=utf-8');

// 데이터베이스 연결 설정
$host = '175.196.104.244';
$dbname = 'reservation_db';
$username = 'root';
$password = 'root';

try {
    if (!isset($_GET['id'])) {
        throw new Exception("예약 ID가 필요합니다.");
    }

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 예약 정보 조회
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        throw new Exception("예약 정보를 찾을 수 없습니다.");
    }

    // 이메일 분리
    if ($reservation['email']) {
        list($email_id, $email_domain) = explode('@', $reservation['email']);
        $reservation['email_id'] = $email_id;
        $reservation['email_domain'] = $email_domain;
    }

    // 날짜 형식 변환
    $dateFields = ['start_date', 'end_date', 'accommodation_start', 'accommodation_end', 'meal_start', 'meal_end'];
    foreach ($dateFields as $field) {
        if ($reservation[$field]) {
            $reservation[$field] = date('Y-m-d', strtotime($reservation[$field]));
        }
    }

    // 시간 형식 변환
    $timeFields = ['start_time', 'end_time'];
    foreach ($timeFields as $field) {
        if ($reservation[$field]) {
            $reservation[$field] = date('H:i', strtotime($reservation[$field]));
        }
    }

    echo json_encode([
        'status' => 'success',
        'data' => $reservation
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 