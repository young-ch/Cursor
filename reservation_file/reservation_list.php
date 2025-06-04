<?php
require_once 'include/common/db_connect.php';

// 데이터베이스 연결
$database = new Database();
$db = $database->getConnection();

// 예약 목록 조회
$query = "SELECT * FROM reservations ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
   
<!DOCTYPE html>
<html lang="ko">
<head>
<style>
        /* 기본 화면 스타일 */
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        /* 프린트 스타일 */
        @media print {
            /* 폼 크기 조정 */
            form {
                width: 90%;
                padding: 0;
                margin: 0;
            }

            /* 테이블 크기 조정 */
            table {
                width: 100%;
                border: 1px solid black;
            }

            /* 페이지 여백 조정 */
            body {
                margin: 0;
                padding: 0;
                font-size: 6px;
            }

            /* 페이지 나누기 */
            .page-break {
                page-break-before: always;
            }

            /* 헤더/푸터 숨기기 */
            .no-print {
                display: none;
            }
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예약 목록</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="/reservation_file/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header">
            <h1>예약 목록</h1>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="reservation.php" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                새 예약하기
            </a>
        </div>
        <div class="no-print">
            <button onclick="window.print()">프린트</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>예약번호</th>
                        <th>기관명</th>
                        <th>대표자</th>
                        <th>연락처</th>
                        <th>예약기간</th>
                        <th>사용인원</th>
                        <th>총사용대금</th>
                        <th>상태</th>
                        <th>등록일</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) > 0): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['representative']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                                <td>
                                    <?php 
                                    echo date('Y-m-d', strtotime($reservation['start_date'])) . ' ~ ' . 
                                         date('Y-m-d', strtotime($reservation['end_date'])); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($reservation['total_people']); ?>명</td>
                                <td><?php echo number_format($reservation['total_amount']); ?>원</td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($reservation['status']) {
                                        case 'pending':
                                            $statusClass = 'badge bg-warning';
                                            $statusText = '대기';
                                            break;
                                        case 'confirmed':
                                            $statusClass = 'badge bg-success';
                                            $statusText = '확정';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'badge bg-danger';
                                            $statusText = '취소';
                                            break;
                                        case 'completed':
                                            $statusClass = 'badge bg-info';
                                            $statusText = '완료';
                                            break;
                                    }
                                    ?>
                                    <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($reservation['created_at'])); ?></td>
                                <td>
                                    <a href="reservation_view.php?id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-info">상세</a>
                                    <a href="reservation_edit.php?id=<?php echo $reservation['id']; ?>" class="btn btn-sm btn-warning">수정</a>
                                    <button onclick="deleteReservation(<?php echo $reservation['id']; ?>)" class="btn btn-sm btn-danger">삭제</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">등록된 예약이 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteReservation(id) {
            if (confirm('정말로 이 예약을 삭제하시겠습니까?')) {
                window.location.href = 'reservation_delete.php?id=' + id;
            }
        }
    </script>
</body>
</html> 