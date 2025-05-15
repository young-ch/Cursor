<?php
// 데이터베이스 연결 설정
$host = '175.196.104.244';
$dbname = 'reservation_db';
$username = 'root';
$password = 'root';

// 디버그 로그 설정
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/reservation_debug.log';
error_log("예약 저장 시작: " . date('Y-m-d H:i:s') . "\n", 3, $logFile);

try {
    // POST 데이터 로깅
    error_log("POST 데이터: " . print_r($_POST, true) . "\n", 3, $logFile);
    
    // 데이터베이스 연결
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    error_log("데이터베이스 연결 성공\n", 3, $logFile);

    // 필수 필드 검증
    $requiredFields = ['company', 'name', 'phone', 'email_id', 'email_domain'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("필수 항목이 누락되었습니다: " . $field);
        }
    }

    // 전화번호 형식 검증
    if (!preg_match('/^[0-9]+$/', $_POST['phone'])) {
        throw new Exception("전화번호는 숫자만 입력 가능합니다.");
    }

    // 이메일 주소 조합 및 검증
    $email = $_POST['email_id'] . '@' . $_POST['email_domain'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("올바른 이메일 형식이 아닙니다.");
    }

    // SQL 쿼리 준비
    $sql = "INSERT INTO reservations (
        company, name, phone, email, 
        course_name, start_date, end_date, 
        total_people, student_people, staff_people,
        room_type, start_time, end_time, room_count,
        accommodation_start, accommodation_end, accommodation_type, accommodation_room_count,
        meal_start, meal_end, meal_type, meal_count,
        inquiry, created_at, status
    ) VALUES (
        :company, :name, :phone, :email,
        :course_name, :start_date, :end_date,
        :total_people, :student_people, :staff_people,
        :room_type, :start_time, :end_time, :room_count,
        :accommodation_start, :accommodation_end, :accommodation_type, :accommodation_room_count,
        :meal_start, :meal_end, :meal_type, :meal_count,
        :inquiry, NOW(), 'pending'
    )";

    $stmt = $pdo->prepare($sql);
    
    // 바인딩할 데이터 준비
    $params = [
        ':company' => $_POST['company'],
        ':name' => $_POST['name'],
        ':phone' => $_POST['phone'],
        ':email' => $email,
        ':course_name' => $_POST['course_name'] ?? null,
        ':start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
        ':end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
        ':total_people' => !empty($_POST['total_people']) ? (int)$_POST['total_people'] : null,
        ':student_people' => !empty($_POST['student_people']) ? (int)$_POST['student_people'] : 0,
        ':staff_people' => !empty($_POST['staff_people']) ? (int)$_POST['staff_people'] : 0,
        ':room_type' => $_POST['room_type'] ?? 'hall',
        ':start_time' => $_POST['start_time'] ?? null,
        ':end_time' => $_POST['end_time'] ?? null,
        ':room_count' => !empty($_POST['room_count']) ? (int)$_POST['room_count'] : 1,
        ':accommodation_start' => !empty($_POST['accommodation_start']) ? $_POST['accommodation_start'] : null,
        ':accommodation_end' => !empty($_POST['accommodation_end']) ? $_POST['accommodation_end'] : null,
        ':accommodation_type' => $_POST['accommodation_type'] ?? null,
        ':accommodation_room_count' => !empty($_POST['room_count']) ? (int)$_POST['room_count'] : 0,
        ':meal_start' => !empty($_POST['meal_start']) ? $_POST['meal_start'] : null,
        ':meal_end' => !empty($_POST['meal_end']) ? $_POST['meal_end'] : null,
        ':meal_type' => $_POST['meal_type'] ?? null,
        ':meal_count' => !empty($_POST['meal_count']) ? (int)$_POST['meal_count'] : 0,
        ':inquiry' => $_POST['inquiry'] ?? null
    ];

    // 쿼리 실행
    $stmt->execute($params);
    $reservationId = $pdo->lastInsertId();
    error_log("예약 저장 성공 (ID: {$reservationId})\n", 3, $logFile);

    // 성공 메시지와 함께 리다이렉트
    header("Location: reservation_complete.php?id=" . $reservationId);
    exit;

} catch (Exception $e) {
    error_log("에러 발생: " . $e->getMessage() . "\n", 3, $logFile);
    header("Location: reservation.php?status=error&message=" . urlencode($e->getMessage()));
    exit;
}
?> 