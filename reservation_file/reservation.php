<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 예약</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="reservation-header fade-in">
            <h1>온라인 예약</h1>
            <p>편리한 온라인 예약 시스템으로 원하는 일정을 선택하세요</p>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <a href="reservation_list.php" class="btn btn-secondary">
                <i class="fas fa-list"></i>
                예약 목록 보기
            </a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <form action="save_reservation.php" method="POST" class="reservation-form fade-in" enctype="multipart/form-data">
            <table class="form-table">
                <tr><th>이름</th><td><input type="text" name="name" required></td></tr>
                <tr><th>소속</th><td><input type="text" name="company" required></td></tr>
                <tr><th>전화번호</th><td><input type="tel" name="phone" required pattern="[0-9\-]+" placeholder="숫자와 -만 입력"></td></tr>
                <tr>
                    <th>이메일</th>
                    <td>
                        <div class="input-group">
                            <input type="text" name="email_id" required style="width:120px;"> <span>@</span>
                            <input type="text" name="email_domain" required style="width:120px;">
                            <select name="email_select" onchange="if(this.value){this.form.email_domain.value=this.value;}" style="width:120px;">
                                <option value="">직접입력</option>
                                <option value="naver.com">naver.com</option>
                                <option value="gmail.com">gmail.com</option>
                                <option value="daum.net">daum.net</option>
                                <option value="hotmail.com">hotmail.com</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr><th>과정명</th><td><input type="text" name="course_name"></td></tr>
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
                    <td><input type="number" name="total_people" id="total_people" readonly style="background:#f5f5f5;"></td>
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
                        <div class="input-group">
                            <select name="room_type" style="width:120px;">
                                <option value="대강의실">대강의실</option>
                                <option value="중강의실">중강의실</option>
                                <option value="소강의실">소강의실</option>
                            </select>
                            <input type="number" name="room_count" min="1" value="1" style="width:80px;" placeholder="개수">
                            <input type="time" name="start_time" style="width:120px;">
                            <span>~</span>
                            <input type="time" name="end_time" style="width:120px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>숙박</th>
                    <td>
                        <div class="input-group">
                            <select name="accommodation_type" style="width:120px;">
                                <option value="">선택</option>
                                <option value="1인실">1인실</option>
                                <option value="2인실">2인실</option>
                                <option value="3인실">3인실</option>
                            </select>
                            <input type="number" name="accommodation_room_count" min="0" style="width:80px;" placeholder="객실수">
                            <input type="date" name="accommodation_start" style="width:140px;">
                            <span>~</span>
                            <input type="date" name="accommodation_end" style="width:140px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>식사</th>
                    <td>
                        <div class="input-group">
                            <select name="meal_type" style="width:120px;">
                                <option value="">선택</option>
                                <option value="조식">조식</option>
                                <option value="중식">중식</option>
                                <option value="석식">석식</option>
                            </select>
                            <input type="number" name="meal_count" min="0" style="width:80px;" placeholder="식수">
                            <input type="date" name="meal_start" style="width:140px;">
                            <span>~</span>
                            <input type="date" name="meal_end" style="width:140px;">
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
            <div style="margin-bottom:1rem;">
                <label for="terms" style="display:block;font-weight:600;margin-bottom:0.5rem;">이용약관</label>
                <textarea id="terms" class="terms-box" readonly>여기에 이용약관 내용을 입력하세요.</textarea>
                <div class="checkbox-group"><input type="checkbox" id="agree" name="agree" required> <label for="agree">동의함</label></div>
            </div>
            <div style="text-align:right;">
                <button type="submit" class="btn-simple">등록</button>
            </div>
        </form>
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
    </script>
</body>
</html> 