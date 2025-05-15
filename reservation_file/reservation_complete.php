<?php
// 데이터베이스 연결 설정
$host = '175.196.104.244';
$dbname = 'reservation_db';
$username = 'root';
$password = 'root';

try {
    if (!isset($_GET['id'])) {
        throw new Exception("예약 정보를 찾을 수 없습니다.");
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
} catch (Exception $e) {
    header("Location: reservation.php?status=error&message=" . urlencode($e->getMessage()));
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
                        <th>회사명</th>
                        <td><?php echo htmlspecialchars($reservation['company']); ?></td>
                    </tr>
                    <tr>
                        <th>담당자</th>
                        <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>이메일</th>
                        <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                    </tr>
                    <?php if ($reservation['course_name']): ?>
                    <tr>
                        <th>과정명</th>
                        <td><?php echo htmlspecialchars($reservation['course_name']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($reservation['start_date']): ?>
                    <tr>
                        <th>교육일정</th>
                        <td>
                            <?php 
                            echo htmlspecialchars($reservation['start_date']);
                            if ($reservation['end_date']) {
                                echo " ~ " . htmlspecialchars($reservation['end_date']);
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($reservation['total_people']): ?>
                    <tr>
                        <th>예약인원</th>
                        <td>
                            총 <?php echo htmlspecialchars($reservation['total_people']); ?>명
                            <?php if ($reservation['student_people'] || $reservation['staff_people']): ?>
                            (교육생: <?php echo htmlspecialchars($reservation['student_people']); ?>명,
                            교육진행: <?php echo htmlspecialchars($reservation['staff_people']); ?>명)
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
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