<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 예약</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="/reservation_file/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header">
            <h1>온라인 예약</h1>
            <div class="reservation-header-image">
                <img src="/reservation_file/images/saemaul.png" alt="예약 이미지" >
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="reservation_list.php" class="btn btn-secondary">
                <i class="fas fa-list"></i>
                예약 목록 보기
            </a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form action="save_reservation.php" method="POST" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th>이름 <span class="required">*</span></th>
                        <td><input type="text" name="name" required></td>
                    </tr>
                    <tr>
                        <th>소속 <span class="required">*</span></th>
                        <td><input type="text" name="company" required></td>
                    </tr>
                    <tr>
                        <th>전화번호 <span class="required">*</span></th>
                        <td><input type="tel" name="phone" required pattern="[0-9\-]+" placeholder="숫자와 -만 입력"></td>
                    </tr>
                    <tr>
                        <th>이메일 <span class="required">*</span></th>
                        <td>
                            <div class="input-group">
                                <input type="text" name="email_id" required> <span>@</span>
                                <input type="text" name="email_domain" required>
                                <select name="email_select" onchange="if(this.value){this.form.email_domain.value=this.value;}">
                                    <option value="">직접입력</option>
                                    <option value="naver.com">naver.com</option>
                                    <option value="gmail.com">gmail.com</option>
                                    <option value="daum.net">daum.net</option>
                                    <option value="hotmail.com">hotmail.com</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>과정명</th>
                        <td><input type="text" name="course_name"></td>
                    </tr>
                    <tr>
                        <th>예약 시작일</th>
                        <td><input type="date" name="start_date" id="start_date"></td>
                    </tr>
                    <tr>
                        <th>예약 종료일</th>
                        <td><input type="date" name="end_date" id="end_date"></td>
                    </tr>
                    <tr>
                        <th>총 인원</th>
                        <td><input type="number" name="total_people" id="total_people" readonly></td>
                    </tr>
                    <tr>
                        <th>교육생 인원</th>
                        <td><input type="number" name="student_people" id="student_people" min="0"></td>
                    </tr>
                    <tr>
                        <th>교육진행 인원</th>
                        <td><input type="number" name="staff_people" id="staff_people" min="0"></td>
                    </tr>
                    <tr>
                        <th>강의실</th>
                        <td>
                            <div class="room-reservation-box">
                                <label for="room_type" class="form-label">강의실 종류</label>
                                <select name="room_type" id="room_type" class="form-select">
                                    <option value="대강의실">대강의실</option>
                                    <option value="중강의실">중강의실</option>
                                    <option value="소강의실">소강의실</option>
                                </select>
                                <label for="room_count" class="form-label">개수</label>
                                <input type="number" name="room_count" id="room_count" min="1" value="1" class="form-control" placeholder="개수">
                                <label for="start_time" class="form-label">시작 시간</label>
                                <input type="time" name="start_time" id="start_time" class="form-control">
                                <span>~</span>
                                <label for="end_time" class="form-label">종료 시간</label>
                                <input type="time" name="end_time" id="end_time" class="form-control">
                            </div>
                            <div class="form-error" id="room_error" style="display:none;color:#e74c3c;font-size:0.95rem;margin-top:0.5rem;"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>숙박</th>
                        <td>
                            <div class="input-group">
                                <select name="accommodation_type">
                                    <option value="">선택</option>
                                    <option value="1인실">1인실</option>
                                    <option value="2인실">2인실</option>
                                    <option value="3인실">3인실</option>
                                </select>
                                <input type="number" name="accommodation_room_count" min="0" placeholder="객실수">
                                <input type="date" name="accommodation_start">
                                <span>~</span>
                                <input type="date" name="accommodation_end">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>식사</th>
                        <td>
                            <div class="input-group">
                                <select name="meal_type">
                                    <option value="">선택</option>
                                    <option value="조식">조식</option>
                                    <option value="중식">중식</option>
                                    <option value="석식">석식</option>
                                </select>
                                <input type="number" name="meal_count" min="0" placeholder="식수">
                                <input type="date" name="meal_start">
                                <span>~</span>
                                <input type="date" name="meal_end">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>문의내용</th>
                        <td><textarea name="inquiry" rows="5"></textarea></td>
                    </tr>
                    <tr>
                        <th>첨부파일</th>
                        <td><input type="file" name="attachment"></td>
                    </tr>
                </table>

                <div class="terms-section">
                    <h3>이용약관</h3>
                    <textarea id="terms" class="terms-box" readonly>여기에 이용약관 내용을 입력하세요.</textarea>
                    <div class="checkbox-group">
                        <input type="checkbox" id="agree" name="agree" required>
                        <label for="agree">이용약관에 동의합니다.</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-simple">예약하기</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        // 전화번호 입력 제한
        document.getElementById('phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // 날짜 유효성 검사
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = this.value;
            
            if (startDate && endDate && startDate > endDate) {
                alert('종료일은 시작일보다 이후여야 합니다.');
                this.value = '';
            }
        });

        // 인원 수 자동 계산
        function updateTotalPeople() {
            const studentPeople = parseInt(document.getElementById('student_people').value) || 0;
            const staffPeople = parseInt(document.getElementById('staff_people').value) || 0;
            document.getElementById('total_people').value = studentPeople + staffPeople;
        }

        document.getElementById('student_people').addEventListener('input', updateTotalPeople);
        document.getElementById('staff_people').addEventListener('input', updateTotalPeople);

        // 폼 유효성 검사 (필수 입력값 미입력 시 에러 표시, 값은 유지)
        document.querySelector('.reservation-form form').addEventListener('submit', function(e) {
            let valid = true;
            // 예시: 강의실 필수값 체크
            const roomType = document.getElementById('room_type').value;
            const roomCount = document.getElementById('room_count').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const roomError = document.getElementById('room_error');
            roomError.style.display = 'none';
            roomError.textContent = '';
            if (!roomType || !roomCount || !startTime || !endTime) {
                roomError.textContent = '강의실 종류, 개수, 시작/종료 시간을 모두 입력해 주세요.';
                roomError.style.display = 'block';
                valid = false;
            }
            // 추가 필수 입력값 체크 (예시: 이름, 소속 등)
            this.querySelectorAll('[required]').forEach(function(input) {
                if (!input.value) {
                    input.classList.add('input-error');
                    valid = false;
                } else {
                    input.classList.remove('input-error');
                }
            });
            if (!valid) e.preventDefault();
        });
    </script>
</body>
</html> 