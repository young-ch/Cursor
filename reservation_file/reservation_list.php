<?php
// 데이터베이스 연결 설정
$host = '175.196.104.244';
$dbname = 'reservation_db';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 삭제 처리
if (isset($_POST['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: reservation_list.php?message=deleted");
        exit;
    } catch(PDOException $e) {
        $error = "삭제 실패: " . $e->getMessage();
    }
}

// 예약 목록 조회
try {
    $stmt = $pdo->query("SELECT * FROM reservations ORDER BY created_at DESC");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "데이터 조회 실패: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예약 관리</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header fade-in">
            <h1>예약 관리</h1>
            <p>예약 현황을 확인하고 관리하세요</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="reservation.php" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                새 예약하기
            </a>
        </div>
        
        <?php if (isset($_GET['message']) && $_GET['message'] == 'deleted'): ?>
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                예약이 삭제되었습니다.
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="reservation-table fade-in">
            <div class="table-responsive">
                <table class="table simple-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>회사명</th>
                            <th>담당자</th>
                            <th>전화번호</th>
                            <th>이메일</th>
                            <th>과정명</th>
                            <th>예약 시작일</th>
                            <th>예약 종료일</th>
                            <th>총 인원</th>
                            <th>강의실</th>
                            <th>숙박</th>
                            <th>식사</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['company']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['total_people']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['room_type']) . ' x' . htmlspecialchars($reservation['room_count']) . ' (' . htmlspecialchars($reservation['start_time']) . '~' . htmlspecialchars($reservation['end_time']) . ')'; ?></td>
                            <td><?php echo htmlspecialchars($reservation['accommodation_type']) . ' x' . htmlspecialchars($reservation['accommodation_room_count']) . ' (' . htmlspecialchars($reservation['accommodation_start']) . '~' . htmlspecialchars($reservation['accommodation_end']) . ')'; ?></td>
                            <td><?php echo htmlspecialchars($reservation['meal_type']) . ' x' . htmlspecialchars($reservation['meal_count']) . ' (' . htmlspecialchars($reservation['meal_start']) . '~' . htmlspecialchars($reservation['meal_end']) . ')'; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-id="<?php echo $reservation['id']; ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="fas fa-edit"></i>
                                        수정
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" 
                                            data-id="<?php echo $reservation['id']; ?>"
                                            data-company="<?php echo htmlspecialchars($reservation['company']); ?>"
                                            data-name="<?php echo htmlspecialchars($reservation['name']); ?>">
                                        <i class="fas fa-trash"></i>
                                        삭제
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 수정 모달 -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">예약 수정</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="edit_reservation.php" method="POST">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">회사명 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="company" id="edit_company" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">담당자 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="edit_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">전화번호 <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" id="edit_phone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">이메일 <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <input type="text" name="email_id" id="edit_email_id" class="form-control" required>
                                        <span class="align-self-center">@</span>
                                        <input type="text" name="email_domain" id="edit_email_domain" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">과정명</label>
                                    <input type="text" class="form-control" name="course_name" id="edit_course_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">예약 시작일</label>
                                    <input type="date" class="form-control" name="start_date" id="edit_start_date">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">총 인원</label>
                                    <input type="number" class="form-control" name="total_people" id="edit_total_people" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">교육생 인원</label>
                                    <input type="number" class="form-control" name="student_people" id="edit_student_people" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">교육진행 인원</label>
                                    <input type="number" class="form-control" name="staff_people" id="edit_staff_people" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">기타 문의사항</label>
                            <textarea class="form-control" name="inquiry" id="edit_inquiry" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="submit" form="editForm" class="btn btn-primary">저장</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 수정 버튼 클릭 시
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                // AJAX로 예약 데이터 가져오기
                fetch('get_reservation.php?id=' + id)
                    .then(response => response.json())
                    .then(response => {
                        if (response.status === 'success' && response.data) {
                            const data = response.data;
                            // 필수 필드 설정
                            document.getElementById('edit_id').value = data.id || '';
                            document.getElementById('edit_company').value = data.company || '';
                            document.getElementById('edit_name').value = data.name || '';
                            document.getElementById('edit_phone').value = data.phone || '';
                            
                            // 이메일 처리
                            if (data.email) {
                                const [emailId, emailDomain] = data.email.split('@');
                                document.getElementById('edit_email_id').value = emailId || '';
                                document.getElementById('edit_email_domain').value = emailDomain || '';
                            }
                            
                            // 선택 필드 설정
                            document.getElementById('edit_course_name').value = data.course_name || '';
                            document.getElementById('edit_start_date').value = data.start_date || '';
                            document.getElementById('edit_end_date').value = data.end_date || '';
                            document.getElementById('edit_total_people').value = data.total_people || '';
                            document.getElementById('edit_student_people').value = data.student_people || '';
                            document.getElementById('edit_staff_people').value = data.staff_people || '';
                            document.getElementById('edit_inquiry').value = data.inquiry || '';
                        } else {
                            console.error('예약 데이터 형식 오류:', response);
                        }
                    })
                    .catch(error => {
                        console.error('데이터 가져오기 실패:', error);
                    });
            });
        });

        // 삭제 버튼 클릭 시
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const company = this.dataset.company;
                const name = this.dataset.name;
                
                if (confirm(`정말로 ${company}의 ${name}님의 예약을 삭제하시겠습니까?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `<input type="hidden" name="delete_id" value="${id}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // 전화번호 입력 제한
        document.getElementById('edit_phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // 인원 수 자동 계산
        function updateTotalPeople() {
            const studentPeople = parseInt(document.getElementById('edit_student_people').value) || 0;
            const staffPeople = parseInt(document.getElementById('edit_staff_people').value) || 0;
            document.getElementById('edit_total_people').value = studentPeople + staffPeople;
        }

        document.getElementById('edit_student_people').addEventListener('input', updateTotalPeople);
        document.getElementById('edit_staff_people').addEventListener('input', updateTotalPeople);
    });
    </script>
</body>
</html> 