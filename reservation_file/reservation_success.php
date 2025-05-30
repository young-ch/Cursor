<?php
require_once 'include/common/db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: reservation.php');
    exit;
}

try {
    // 데이터베이스 연결
    $database = new Database();
    $db = $database->getConnection();

    // 예약 정보 조회
    $query = "SELECT * FROM reservations WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        throw new Exception("예약 정보를 찾을 수 없습니다.");
    }
} catch (Exception $e) {
    header('Location: reservation.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예약 완료</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header fade-in">
            <h1>예약이 완료되었습니다</h1>
            <p>예약해 주셔서 감사합니다.</p>
        </div>

        <div class="reservation-form fade-in">
            <div class="reservation-details">
                <table class="table">
                    <tr>
                        <th>예약번호</th>
                        <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                    </tr>
                    <tr>
                        <th>기관(업체)명</th>
                        <td><?php echo htmlspecialchars($reservation['company_name']); ?></td>
                    </tr>
                    <tr>
                        <th>대표자</th>
                        <td><?php echo htmlspecialchars($reservation['representative']); ?></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>이메일</th>
                        <td><?php echo htmlspecialchars($reservation['tax_manager_email']); ?></td>
                    </tr>
                    <tr>
                        <th>예약기간</th>
                        <td>
                            <?php 
                            echo date('Y-m-d', strtotime($reservation['start_date']));
                            if ($reservation['end_date']) {
                                echo " ~ " . date('Y-m-d', strtotime($reservation['end_date']));
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>사용인원</th>
                        <td><?php echo htmlspecialchars($reservation['total_people']); ?>명</td>
                    </tr>
                    <tr>
                        <th>총사용대금</th>
                        <td><?php echo number_format($reservation['total_amount']); ?>원</td>
                    </tr>
                </table>
            </div>

            <div class="d-flex gap-2 justify-content-center mt-4">
                <a href="reservation.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    새 예약하기
                </a>
                <a href="reservation_list.php" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    예약 목록 보기
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>
</html> 