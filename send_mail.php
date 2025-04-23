<?php
header('Content-Type: application/json');

// 폼 데이터 받기
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// 입력 데이터 검증
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'success' => false,
        'message' => '모든 필드를 입력해주세요.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => '유효한 이메일 주소를 입력해주세요.'
    ]);
    exit;
}

// 메일 헤더 설정
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// 메일 본문 작성
$mailBody = "
<html>
<head>
    <title>새로운 문의가 도착했습니다</title>
</head>
<body>
    <h2>새로운 문의가 도착했습니다.</h2>
    <p><strong>이름:</strong> {$name}</p>
    <p><strong>이메일:</strong> {$email}</p>
    <p><strong>제목:</strong> {$subject}</p>
    <p><strong>메시지:</strong></p>
    <p>" . nl2br(htmlspecialchars($message)) . "</p>
</body>
</html>
";

// 메일 발송
$mailSubject = "[온라인 명함] " . $subject;
$success = mail('godsky1990@naver.com', $mailSubject, $mailBody, $headers);

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => '메시지가 성공적으로 전송되었습니다.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => '메일 전송에 실패했습니다. 나중에 다시 시도해주세요.'
    ]);
}
?> 