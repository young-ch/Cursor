<?php
// XSS 방지를 위한 출력 이스케이프 함수
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 날짜 포맷 변환 함수
function formatDate($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
}

// 금액 포맷 변환 함수
function formatAmount($amount) {
    return number_format($amount) . '원';
}

// 상태 텍스트 변환 함수
function getStatusText($status) {
    $statusMap = [
        'pending' => '대기중',
        'confirmed' => '확정',
        'cancelled' => '취소',
        'completed' => '완료'
    ];
    return isset($statusMap[$status]) ? $statusMap[$status] : $status;
}

// JSON 데이터 디코딩 함수
function decodeJson($json) {
    return json_decode($json, true) ?? [];
}

// 파일 업로드 함수
function uploadFile($file, $targetDir = '../uploads/') {
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return false;
}

// 페이지네이션 HTML 생성 함수
function generatePagination($currentPage, $totalPages, $urlPattern) {
    $html = '<div class="pagination">';
    
    // 이전 페이지 링크
    if ($currentPage > 1) {
        $html .= '<a href="' . sprintf($urlPattern, $currentPage - 1) . '" class="prev">&laquo; 이전</a>';
    }
    
    // 페이지 번호
    for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) {
        $class = $i == $currentPage ? 'active' : '';
        $html .= '<a href="' . sprintf($urlPattern, $i) . '" class="' . $class . '">' . $i . '</a>';
    }
    
    // 다음 페이지 링크
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . sprintf($urlPattern, $currentPage + 1) . '" class="next">다음 &raquo;</a>';
    }
    
    $html .= '</div>';
    return $html;
}

// 입력값 검증 함수
function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        if (isset($rule['required']) && $rule['required'] && empty($data[$field])) {
            $errors[$field] = '필수 입력 항목입니다.';
            continue;
        }
        
        if (!empty($data[$field])) {
            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $data[$field])) {
                $errors[$field] = '올바른 형식이 아닙니다.';
            }
            
            if (isset($rule['min']) && strlen($data[$field]) < $rule['min']) {
                $errors[$field] = '최소 ' . $rule['min'] . '자 이상 입력해주세요.';
            }
            
            if (isset($rule['max']) && strlen($data[$field]) > $rule['max']) {
                $errors[$field] = '최대 ' . $rule['max'] . '자까지 입력 가능합니다.';
            }
        }
    }
    
    return $errors;
}

// CSRF 토큰 생성 함수
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF 토큰 검증 함수
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?> 