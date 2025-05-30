<?php
require_once 'include/controllers/ReservationController.php';
require_once 'include/common/functions.php';

session_start();

$controller = new ReservationController();
$errors = [];
$formData = [];

// 이전 입력 데이터 복원
if (isset($_SESSION['form_data'])) {
    $formData = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->create();
    
    if (!$result['success']) {
        if (isset($result['errors'])) {
            $errors = $result['errors'];
            // 현재 입력 데이터 저장
            $_SESSION['form_data'] = $_POST;
        }
        $errorMessage = $result['message'];
    } else {
        // 성공 시 세션 데이터 삭제
        unset($_SESSION['form_data']);
        header('Location: reservation_complete.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 예약</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="/reservation_file/styles.css" rel="stylesheet">
    <style>
        .usage-plan {
            margin-top: 20px;
        }
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #495057;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            border: none;
            color: #007bff;
        }
        .nav-tabs .nav-link.active {
            border: none;
            color: #007bff;
            border-bottom: 3px solid #007bff;
            background: none;
        }
        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }
        .table {
            margin-bottom: 0;
            width: 100%;
            table-layout: fixed;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 12px;
            text-align: center;
        }
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        .usage-amount, .usage-people, .total-amount {
            width: 120px;
            text-align: right;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .usage-note {
            width: 200px;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .form-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .table th:nth-child(1) { width: 20%; }
        .table th:nth-child(2) { width: 20%; }
        .table th:nth-child(3) { width: 20%; }
        .table th:nth-child(4) { width: 20%; }
        .table th:nth-child(5) { width: 20%; }
        .input-error {
            border: 2px solid #dc3545 !important;
            background-color: #fff8f8 !important;
        }
        .input-error:focus {
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }
        .checkbox-group {
            position: relative;
            display: inline-block;
        }
        .agree-message {
            display: none;
            color: #dc3545;
            font-size: 14px;
            margin-left: 10px;
        }
        .checkbox-group input[type="checkbox"]:not(:checked) ~ .agree-message {
            display: inline-block;
        }
        /* 부대시설 탭 특별 스타일 */
        #facility .table td:first-child {
            width: 20%;
        }
        #facility .form-select {
            width: 100%;
            max-width: 200px;
        }
        #facility .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
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

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo h($errorMessage); ?>
            </div>
        <?php endif; ?>

        <div class="reservation-form">
            <form action="reservation_complete.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <table class="form-table">
                    <tr>
                        <th>기관(업체)명 <span class="required">*</span></th>
                        <td><input type="text" name="company_name" required></td>
                        <th>대표자 <span class="required">*</span></th>
                        <td><input type="text" name="representative" required></td>
                    </tr>
                    <tr>
                        <th>전화 <span class="required">*</span></th>
                        <td><input type="tel" name="phone" required></td>
                        <th>주소 <span class="required">*</span></th>
                        <td><input type="text" name="address" required></td>
                    </tr>
                    <tr>    
                        <th>증빙종류</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_accommodation" name="doc_accommodation">
                                    <label for="doc_accommodation">세금계산서</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_hall" name="doc_hall">
                                    <label for="doc_hall">카드</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_facility" name="doc_facility">
                                    <label for="doc_facility">현금영수증</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_etc" name="doc_etc">
                                    <label for="doc_etc">해당없음</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사업자등록번호 <span class="required">*</span></th>
                        <td colspan="2"><input type="text" name="business_number" required></td>
                        <td><input type="file" name="attachment"></td>
                    </tr>
                    <tr>    
                        <th>사용범위</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_accommodation" name="doc_accommodation">
                                    <label for="doc_accommodation">숙소</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_hall" name="doc_hall">
                                    <label for="doc_hall">강당/강의실</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_facility" name="doc_facility">
                                    <label for="doc_facility">부대시설</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="doc_etc" name="doc_etc">
                                    <label for="doc_etc">기타</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>전자세금계산서 <br>담당자 <span class="required">*</span></th>
                        <td colspan="3">
                            <div class="tax-manager-group">
                                <div class="name-phone-row">
                                    <input type="text" name="tax_manager_name" placeholder="성명" required>
                                    <input type="text" name="tax_manager_phone" placeholder="휴대폰" required>
                                </div>
                                <div class="email-row">
                                    <div class="email-group">
                                        <input type="text" name="email_id" placeholder="이메일" required>
                                        <span>@</span>
                                        <input type="text" name="email_domain" required>
                                        <select name="email_select" onchange="if(this.value){this.form.email_domain.value=this.value;}">
                                            <option value="">직접입력</option>
                                            <option value="naver.com">naver.com</option>
                                            <option value="gmail.com">gmail.com</option>
                                            <option value="daum.net">daum.net</option>
                                            <option value="hotmail.com">hotmail.com</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사용목적</th>
                        <td colspan="3"><input type="text" name="purpose"></td>
                    </tr>
                    <tr>
                        <th>사용인원</th>
                        <td>
                            <div class="input-group">
                                <input type="number" name="total_people" id="total_people" >
                                <span>명</span>
                            </div>
                        </td>
                        <th>예약 기간</th>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date">
                                <span>~</span>
                                <input type="date" name="end_date" id="end_date">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사용계획</th>
                        <td colspan="3">
                            <div class="usage-plan">
                                <ul class="nav nav-tabs" id="facilityTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="accommodation-tab" data-bs-toggle="tab" data-bs-target="#accommodation" type="button" role="tab">숙소</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="classroom-tab" data-bs-toggle="tab" data-bs-target="#classroom" type="button" role="tab">강의실</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="facility-tab" data-bs-toggle="tab" data-bs-target="#facility" type="button" role="tab">부대시설</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="facilityTabContent">
                                    <!-- 숙소 탭 -->
                                    <div class="tab-pane fade show active" id="accommodation" role="tabpanel">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>시설명</th>
                                                    <th>단가</th>
                                                    <th>인원</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2인실</td>
                                                    <td><input type="number" class="usage-amount" value="50000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>4인실</td>
                                                    <td><input type="number" class="usage-amount" value="80000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>6인실</td>
                                                    <td><input type="number" class="usage-amount" value="100000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>8인실</td>
                                                    <td><input type="number" class="usage-amount" value="120000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- 강의실 탭 -->
                                    <div class="tab-pane fade" id="classroom" role="tabpanel">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>시설명</th>
                                                    <th>단가</th>
                                                    <th>인원</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>제1강의실</td>
                                                    <td><input type="number" class="usage-amount" value="100000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제2강의실</td>
                                                    <td><input type="number" class="usage-amount" value="80000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제3강의실</td>
                                                    <td><input type="number" class="usage-amount" value="60000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>토의실</td>
                                                    <td><input type="number" class="usage-amount" value="40000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- 부대시설 탭 -->
                                    <div class="tab-pane fade" id="facility" role="tabpanel">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>시설명</th>
                                                    <th>단가</th>
                                                    <th>인원</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>한식</td>
                                                    <td><input type="number" class="usage-amount" value="15000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>시설사용료</td>
                                                    <td><input type="number" class="usage-amount" value="50000" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="grass_field" class="form-select" onchange="updateGrassFieldAmount(this)">
                                                            <option value="">잔디운동장</option>
                                                            <option value="2750000">199명 이하</option>
                                                            <option value="3800000">299명 이하</option>
                                                            <option value="13000">599명 이하</option>
                                                            <option value="11000">1000명이하</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" class="usage-amount" value="0" readonly></td>
                                                    <td><input type="number" class="usage-people" value="0" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" placeholder="비고"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사용료</th>
                        <td colspan="3">
                            <div class="fee-info">
                                <div class="fee-calculator">
                                    <div class="fee-row">
                                        <span class="fee-label">총사용대금</span>
                                        <input type="text" id="grand-total" class="fee-amount" readonly>
                                        <span class="fee-currency">원</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">계약금 (10%)</span>
                                        <input type="number" id="contract-amount" class="fee-amount" min="0" onchange="calculateBalance()">
                                        <span class="fee-currency">원</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">잔금</span>
                                        <input type="text" id="balance-amount" class="fee-amount" readonly>
                                        <span class="fee-currency">원</span>
                                    </div>
                                </div>
                                <p class="fee-note">이용대금 송금 구좌 : 우리은행 1005-303-298161 새마을연수원(카드결재 가능)</p>
                            </div>
                        </td>
                        <td>
                         
                        </td>
                    </tr>
                </table>

                <div class="terms-section">
                    <h3>이용약관</h3>
                    <textarea id="terms" class="terms-box" readonly>위와 같은 조건으로 새마을운동중앙연수원 시설을 사용하고자 신청(계약)합니다.</textarea>
                    <div class="checkbox-group">
                        <input type="checkbox" id="agree" name="agree" required>
                        <label for="agree">이용약관에 동의합니다.</label>
                        <span class="agree-message">※ 이용약관에 동의해주세요.</span>
                    </div>
                </div>

                <div class="contract-terms">
                    <h4>계약 특례 사항</h4>
                    <ol>
                        <li>본 계약은 계약금(사용대금의 10%)을 입금하여야 효력이 발생합니다.</li>
                        <li>시설사용 대금 잔금은 입교일 2일전까지 입금이 되어야 하며, 그렇지 않을 경우에 계약은 자동 해지 됩니다.</li>
                        <li>시설 사용 내역과 식사 인원은 최소 입교 7일전에 조정하여야 유효합니다.</li>
                        <li>시설사용 약정 사항 및 시설이용 준수 사항을 반드시 이행합니다.</li>
                        <li>약정 인원 및 시설사용 시간 초과 시 추가 사용료를 부담합니다.</li>
                        <li>시설사용 중 발생된 상해의 민․형사상 관련 일체의 책임은 시설사용자에게 있습니다.</li>
                        <li>응급 상비약은 자체 준비하며, 숙박시 칫솔, 수건을 준비합니다.</li>
                        <li>타 교육진행을 위해 식사 시간을 준수합니다(조식 07:00-08:00/ 중식 12:00-13:00/ 석식 18:00-19:00)</li>
                        <li>계약금 입금 후 귀 기관의 사정으로 계약을 파기시에는 계약금은 환불되지 않습니다. (단, 뷔폐 중도 취소시 
                        위약금은 뷔폐업체와의 약정기준에 의거합니다.)</li>
                        <li>숙소 사용시 시설 훼손예치금 1인당 2,500원 선부과(훼손 없을시 반환)</li>
                        <li>출장뷔페 사용시 시설사용료 300,000원 부과 및 환경예치금(음식물 청소비용) 1인당 2,000원 선부과(깨끗이 사용시 반환)</li>
                        <li>시설대여 불가(종교집회, 정치집회, 특정집단‧특정인 혐오집회, 미풍양속에 반하는 집회, 사회통념상 부도덕한 집회, 기타 
                        시설물 사용시 불성실업체)</li>
                    </ol>
                    <p class="contact-info">
                        ※ 문의(시설운영팀) : Tel 031)780-7832, 010-5314-7801 / E-Mail sucti@hanmail.net / FAX 031)780-7835
                    </p>
                </div>

                <div class="form-actions">
                    <button type="button" id="submitBtn" class="btn btn-primary">예약하기</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        // DOM이 완전히 로드된 후 실행
        document.addEventListener('DOMContentLoaded', function() {
            // 전화번호 입력 제한
            const phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // 날짜 유효성 검사
            const endDateInput = document.getElementById('end_date');
            if (endDateInput) {
                endDateInput.addEventListener('change', function() {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = this.value;
                    
                    if (startDate && endDate && startDate > endDate) {
                        alert('종료일은 시작일보다 이후여야 합니다.');
                        this.value = '';
                    }
                });
            }

            // 잔디운동장 금액 업데이트 함수
            window.updateGrassFieldAmount = function(select) {
                const row = select.closest('tr');
                const amountInput = row.querySelector('.usage-amount');
                const selectedValue = select.value;
                
                if (amountInput) {
                    amountInput.value = selectedValue || 0;
                    calculateTotalAmount(row);
                }
            };
            
            // 사용대금 자동 계산 함수
            window.calculateTotalAmount = function(row) {
                const amountInput = row.querySelector('.usage-amount');
                const peopleInput = row.querySelector('.usage-people');
                const totalInput = row.querySelector('.total-amount');
                
                if (amountInput && peopleInput && totalInput) {
                    const amount = parseFloat(amountInput.value) || 0;
                    const people = parseInt(peopleInput.value) || 0;
                    const total = amount * people;
                    totalInput.value = total;
                    updateGrandTotal();
                }
            };

            // 총 사용대금 계산 함수
            window.updateGrandTotal = function() {
                const totalInputs = document.querySelectorAll('.total-amount');
                let grandTotal = 0;
                
                totalInputs.forEach(input => {
                    grandTotal += parseFloat(input.value) || 0;
                });
                
                const grandTotalInput = document.getElementById('grand-total');
                if (grandTotalInput) {
                    grandTotalInput.value = grandTotal.toLocaleString();
                }
            };

            // 모든 시설 행에 이벤트 리스너 추가
            const facilityRows = document.querySelectorAll('.tab-content .table tbody tr');
            facilityRows.forEach(row => {
                const peopleInput = row.querySelector('.usage-people');
                const amountInput = row.querySelector('.usage-amount');
                const grassFieldSelect = row.querySelector('select[name="grass_field"]');

                if (peopleInput) {
                    peopleInput.addEventListener('input', function() {
                        calculateTotalAmount(row);
                    });
                }

                if (grassFieldSelect) {
                    grassFieldSelect.addEventListener('change', function() {
                        updateGrassFieldAmount(this);
                    });
                }
            });

            // 예약하기 버튼 클릭 이벤트
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    // 필수 필드 검사
                    const requiredFields = document.querySelectorAll('[required]');
                    let emptyFields = [];
                    let firstEmptyField = null;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            emptyFields.push({
                                field: field,
                                label: field.closest('tr')?.querySelector('th')?.textContent || field.name
                            });
                            if (!firstEmptyField) {
                                firstEmptyField = field;
                            }
                        }
                    });

                    // 필수 필드가 비어있으면 포커스하고 알림
                    if (emptyFields.length > 0) {
                        const emptyFieldLabels = emptyFields.map(field => field.label).join(', ');
                        alert('다음 필드를 입력해주세요: ' + emptyFieldLabels);
                        
                        setTimeout(() => {
                            firstEmptyField.focus();
                            firstEmptyField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);

                        return;
                    }

                    const agreeCheckbox = document.getElementById('agree');
                    if (!agreeCheckbox.checked) {
                        agreeCheckbox.classList.add('input-error');
                        agreeCheckbox.focus();
                        return;
                    }

                    // 총 사용대금 계산
                    let totalAmount = 0;
                    totalAmount = parseFloat(document.getElementById('grand-total').value.replace(/,/g, '')) || 0;

                    // FormData 객체 생성
                    const formData = new FormData(document.querySelector('form'));
                    formData.append('total_amount', totalAmount);

                    // URL 파라미터 생성
                    const params = new URLSearchParams();
                    for (const [key, value] of formData.entries()) {
                        params.append(key, value);
                    }

                    // 확인 페이지로 이동
                    window.location.href = 'reservation_confirm.php?' + params.toString();
                });
            }

            // 약관 동의 체크 이벤트
            const agreeCheckbox = document.getElementById('agree');
            if (agreeCheckbox) {
                agreeCheckbox.addEventListener('change', function() {
                    this.classList.remove('input-error');
                });
            }
        });
    </script>
</body>
</html> 