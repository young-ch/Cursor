<?php
require_once 'include/common/db_connect.php';
require_once 'include/dao/ReservationDAO.php';

// GET 파라미터로 전달된 데이터 가져오기
$reservationData = $_GET;

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 데이터베이스 연결
        $database = new Database();
        $db = $database->getConnection();
        
        // ReservationDAO 인스턴스 생성
        $reservationDAO = new ReservationDAO($db);

        // 예약 데이터 준비
        $data = [
            'company_name' => $_POST['company_name'],
            'representative' => $_POST['representative'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'business_number' => $_POST['business_number'],
            
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
            'tax_manager_email' => $_POST['email_id'] . '@' . $_POST['email_domain'],
            
            'purpose' => $_POST['purpose'] ?? '',
            'total_people' => $_POST['total_people'] ?? 0,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            
            // 사용계획 금액
            'total_amount' => str_replace(',', '', $_POST["total_amount"]),
         //   'contract_amount' => str_replace(',', '', $_POST["contract_amount"]),
         //   'balance_amount' => str_replace(',', '', $_POST["balance_amount"]),
            
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s')
        ];

        // ReservationDAO를 통해 예약 데이터 저장
        $result = $reservationDAO->create($data);

        if ($result) {
            // 성공 시 예약 완료 페이지로 리다이렉션
            header('Location: reservation_success.php?id=' . $result);
            exit;
        } else {
            throw new Exception("예약 저장에 실패했습니다.");
        }

    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예약 확인</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header">
            <h1>예약 확인</h1>
            <p>입력하신 예약 정보를 확인해주세요.</p>
        </div>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form action="reservation_confirm.php" method="POST">
                <!-- 기존 입력 데이터를 hidden 필드로 전달 -->
                <?php foreach ($reservationData as $key => $value): ?>
                    <?php if (!is_array($value)): ?>
                        <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" 
                               value="<?php echo htmlspecialchars($value); ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="reservation-summary">
                    <h3>예약 정보 확인</h3>
                    <table class="table">
                        <tr>
                            <th>기관(업체)명</th>
                            <td><?php echo htmlspecialchars($reservationData['company_name'] ?? ''); ?></td>
                            <th>대표자</th>
                            <td><?php echo htmlspecialchars($reservationData['representative'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>전화번호</th>
                            <td><?php echo htmlspecialchars($reservationData['phone'] ?? ''); ?></td>
                            <th>주소</th>
                            <td><?php echo htmlspecialchars($reservationData['address'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>사업자등록번호</th>
                            <td><?php echo htmlspecialchars($reservationData['business_number'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>전자세금계산서 담당자</th>
                            <td colspan="3">
                                <?php echo htmlspecialchars($reservationData['tax_manager_name'] ?? ''); ?> / 
                                <?php echo htmlspecialchars($reservationData['tax_manager_phone'] ?? ''); ?> / 
                                <?php echo htmlspecialchars($reservationData['email_id'] ?? ''); ?>@<?php echo htmlspecialchars($reservationData['email_domain'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>사용목적</th>
                            <td colspan="3"><?php echo htmlspecialchars($reservationData['purpose'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th>사용인원</th>
                            <td colspan="3"><?php echo htmlspecialchars($reservationData['total_people'] ?? 0); ?>명</td>
                        </tr>
                        <tr>
                            <th>예약 기간</th>
                            <td colspan="3">
                                <?php echo htmlspecialchars($reservationData['start_date'] ?? ''); ?> ~ 
                                <?php echo htmlspecialchars($reservationData['end_date'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>총사용대금</th>
                            <td colspan="3">
                                <?php 
                                $totalAmount = isset($reservationData['total_amount']) ? 
                                             intval($reservationData['total_amount']) : 0;
                                echo number_format($totalAmount) . "원"; 
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="d-flex gap-2 justify-content-center mt-4">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left"></i>
                        이전으로
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-check"></i>
                        예약 확정
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        // 예약 확정 버튼 클릭 이벤트
        document.getElementById('submitBtn').addEventListener('click', function() {
            // 폼 제출
            document.querySelector('form').submit();
        });
    </script>
</body>
</html> 