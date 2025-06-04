<?php
require_once 'include/common/db_connect.php';

// POST 데이터 검증
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reservation.php');
    exit;
}

try {
    // 데이터베이스 연결
    $database = new Database();
    $db = $database->getConnection();

    // 필수 필드 검증
    $requiredFields = [
        'company_name', 'representative', 'phone', 'address', 'business_number',
        'tax_manager_name', 'tax_manager_phone', 'email_id', 'email_domain'
    ];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("필수 항목이 누락되었습니다: " . $field);
        }
    }

    // 이메일 주소 조합
    $email = $_POST['email_id'] . '@' . $_POST['email_domain'];

    // 첨부파일 처리
    $attachment_path = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
            $attachment_path = $file_path;
        }
    }

    // 예약 데이터 준비
    $data = [
        'company_name' => $_POST['company_name'],
        'representative' => $_POST['representative'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'business_number' => $_POST['business_number'],
        'attachment_path' => $attachment_path,
        
        // 증빙종류
        'doc_tax_invoice' => isset($_POST['doc_tax_invoice']) ? 1 : 0,
        'doc_card' => isset($_POST['doc_card']) ? 1 : 0,
        'doc_cash_receipt' => isset($_POST['doc_cash_receipt']) ? 1 : 0,
        'doc_none' => isset($_POST['doc_none']) ? 1 : 0,
        
        // 사용범위
        'usage_accommodation' => isset($_POST['usage_accommodation']) ? 1 : 0,
        'usage_hall' => isset($_POST['usage_hall']) ? 1 : 0,
        'usage_facility' => isset($_POST['usage_facility']) ? 1 : 0,
        'usage_etc' => isset($_POST['usage_etc']) ? 1 : 0,
        
        // 전자세금계산서 담당자 정보
        'tax_manager_name' => $_POST['tax_manager_name'],
        'tax_manager_phone' => $_POST['tax_manager_phone'],
        'tax_manager_email' => $email,
        
        'purpose' => $_POST['purpose'] ?? '',
        'total_people' => $_POST['total_people'] ?? 0,
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        
        // 사용계획 금액
        'total_amount' => str_replace(',', '', $_POST['total_amount']),
        'contract_amount' => str_replace(',', '', $_POST['contract_amount']),
        'balance_amount' => str_replace(',', '', $_POST['balance_amount']),
        
        'status' => 'pending'
    ];

    // SQL 쿼리 준비
    $fields = implode(', ', array_keys($data));
    $values = ':' . implode(', :', array_keys($data));
    $query = "INSERT INTO reservations ($fields) VALUES ($values)";

    // 쿼리 실행
    $stmt = $db->prepare($query);
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->execute();

    // 성공 메시지와 함께 리다이렉션
    header('Location: reservation_success.php?id=' . $db->lastInsertId());
    exit;

} catch (Exception $e) {
    // 에러 발생 시 세션에 에러 메시지 저장
    session_start();
    $_SESSION['error'] = $e->getMessage();
    $_SESSION['form_data'] = $_POST;
    header('Location: reservation.php');
    exit;
} 