<?php
header('Content-Type: application/json');

// Result 폴더가 없으면 생성
if (!file_exists('Result')) {
    mkdir('Result', 0777, true);
}

// POST 데이터 받기
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

// 파일명 생성 (날짜_시간.json)
$filename = 'Result/survey_' . date('Y-m-d_H-i-s') . '.json';

// JSON 파일로 저장
if (file_put_contents($filename, $json)) {
    echo json_encode(['success' => true, 'message' => 'Survey saved successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save survey']);
}
?> 