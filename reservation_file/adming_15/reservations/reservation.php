<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>온라인 예약 신청</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="/reservations/styles.css" rel="stylesheet"> 
    
	<style>
	#use_etc_txt {display:none;}
	</style>
</head>
<body>
    <div class="container">
        <div class="reservation-header">
            <h1>온라인 예약 신청</h1>
           <!-- <div class="reservation-header-image">
                <img src="/reservations/images/saemaul.png" alt="예약 이미지" >
            </div> -->
        </div>

<style>
#doc_hall_txt {display:none;line-height:150%;color:red;margin:0;padding:0;}
</style>

        <div class="reservation-form">
		<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
			<script>
				function zipsearch() {
					new daum.Postcode({
						oncomplete: function(data) {
							// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			
							// 각 주소의 노출 규칙에 따라 주소를 조합한다.
							// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
							var fullAddr = ''; // 최종 주소 변수
							var extraAddr = ''; // 조합형 주소 변수
			
							// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
							if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
								fullAddr = data.roadAddress;
			
							} else { // 사용자가 지번 주소를 선택했을 경우(J)
								fullAddr = data.jibunAddress;
							}
			
							// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
							if(data.userSelectedType === 'R'){
								//법정동명이 있을 경우 추가한다.
								if(data.bname !== ''){
									extraAddr += data.bname;
								}
								// 건물명이 있을 경우 추가한다.
								if(data.buildingName !== ''){
									extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
								}
								// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
								fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
							}
			
							// 우편번호와 주소 정보를 해당 필드에 넣는다.
							//document.getElementById('zipcode').value = data.zonecode; //5자리 새우편번호 사용
							document.getElementById('address').value = fullAddr;
			
							// 커서를 상세주소 필드로 이동한다.
							document.getElementById('address').focus();
						}
					}).open();
				}
			</script>
            <form name="reservation_form" action="reservation_complete.php" method="POST" enctype="multipart/form-data">
               
                <table class="form-table">
                    <tr>
                        <th>기관(업체)명 <span class="required">*</span></th>
                        <td><input type="text" name="company_name" required></td>
                        <th>대표자 <span class="required">*</span></th>
                        <td><input type="text" name="representative" required></td>
                    </tr>
                    <tr>
                        <th>전화 <span class="required">*</span></th>
                        <td><input type="tel" name="phone" id="phone" required placeholder="000-0000-0000" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" onkeyup="formatPNumber(this)"></td>
                        <th>주소 <span class="required">*</span></th>
                        <td><input type="text" id="address" name="address" required onclick="javascript:zipsearch();"></td>
                    </tr>
                    <tr>    
                        <th>증빙종류</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_accommodation" name="doc_tax_invoice" value="1" onclick="document.getElementById('doc_hall_txt').style.display='none';">
                                    <label for="doc_accommodation">세금계산서</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_hall" name="doc_tax_invoice" value="2" onclick="document.getElementById('doc_hall_txt').style.display='block';">
                                    <label for="doc_hall">카드</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_facility" name="doc_tax_invoice" value="3" onclick="document.getElementById('doc_hall_txt').style.display='none';">
                                    <label for="doc_facility">현금영수증</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_etc" name="doc_tax_invoice" value="4" onclick="document.getElementById('doc_hall_txt').style.display='none';">
                                    <label for="doc_etc">해당없음</label>
                                </div>
								<p id="doc_hall_txt">*세금계산서 발행불가</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사업자등록번호 <span class="required">*</span></th>
                        <td colspan="2"><input type="text" id="business_number" name="business_number" maxlength="12" required placeholder="000-00-00000" onkeyup="formatPhoneNumber()"></td>
                        <td><input type="file" name="attachment"></td>
                    </tr>
                    <tr>    
                        <th>사용범위</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="usage_accommodation" name="usage_accommodation" value="1">
                                    <label for="use_accommodation">숙소</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_hall" name="usage_hall" value="1">
                                    <label for="use_hall">강당/강의실</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_facility" name="usage_facility" value="1">
                                    <label for="use_facility">부대시설</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_etc" name="usage_etc" onclick="is_checked()"  value="1">
                                    <label for="use_etc">협의사항</label>
									<input type="text" id="use_etc_txt" name="use_etc_txt">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>연락할 담당자 <span class="required">*</span></th>
                        <td colspan="3">
                            <div class="tax-manager-group">
                                <div class="name-phone-row">
                                    <input type="text" name="tax_manager_name" placeholder="성명" required>
                                    <input type="tel" name="tax_manager_phone" placeholder="000-0000-0000" required pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" onkeyup="formatPNumber(this)">
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
                                <input type="number" name="total_people" id="total_people">
                                <span>명</span>
                            </div>
                        </td>
                        <th>예약 기간</th>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date" max="2030-12-31" min="">
                                <span>~</span>
                                <input type="date" name="end_date" id="end_date" max="2030-12-31" min="">
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
                                        <button class="nav-link" id="classroom2-tab" data-bs-toggle="tab" data-bs-target="#classroom2" type="button" role="tab">강의실2</button>
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
                                                    <th>실</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2인실(69호실)</td>
                                                    <td><input type="text" class="usage-amount" value="60,000" name="room_no_1" readonly></td>
                                                    <td><input type="number" class="usage-people" name="room_no_1_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="69"></td>
                                                    <td><input type="text" class="total-amount" name="room_no_1_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="room_no_1t" placeholder="비고"></td>
                                                </tr>
												<tr>
                                                    <td>3인실(6호실)</td>
                                                    <td><input type="text" class="usage-amount" value="70,000" name="room_no_2" readonly></td>
                                                    <td><input type="number" class="usage-people" name="room_no_2_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" name="room_no_2_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="room_no_2t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>4인실(59호실)</td>
                                                    <td><input type="text" class="usage-amount" value="90,000" name="room_no_3" readonly></td>
                                                    <td><input type="number" class="usage-people" name="room_no_3_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" name="room_no_3_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="room_no_3t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>5인실(1호실)</td>
                                                    <td><input type="text" class="usage-amount" value="100,000" name="room_no_4" readonly></td>
                                                    <td><input type="number" class="usage-people single-room" name="room_no_4_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" name="room_no_4_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="room_no_4t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>8인실(8호실)</td>
                                                    <td><input type="text" class="usage-amount" value="140,000" name="room_no_5" readonly></td>
                                                    <td><input type="number" class="usage-people" name="room_no_5_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" name="room_no_5_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="room_no_5t" placeholder="비고"></td>
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
                                                    <th>실</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>강당(470석)</td>
                                                    <td><input type="text" class="usage-amount" value="4,500,000" readonly name="lec_no_1"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_1_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_1_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_1t" placeholder="비고"></td>
                                                </tr>
												<tr>
                                                    <td>제1강의실<br>(84석)</td>
                                                    <td><input type="text" class="usage-amount" value="770,000" readonly name="lec_no_2"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_2_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_2_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_2t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제2강의실<br>(120석)</td>
                                                    <td><input type="text" class="usage-amount" value="1,100,000" readonly name="lec_no_3"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_3_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1" oninput="validateClassroomCount(this)"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_3_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_3t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제3강의실<br>(208석)</td>
                                                    <td><input type="text" class="usage-amount" value="1,706,000" readonly name="lec_no_4"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_4_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1" oninput="validateClassroomCount(this)"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_4_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_4t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>토의실(30석)</td>
                                                    <td><input type="text" class="usage-amount" value="165,000" readonly name="lec_no_5"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_5_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_5_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_5t" placeholder="비고"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="classroom2" role="tabpanel">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>시설명</th>
                                                    <th>단가</th>
                                                    <th>실</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>제4강의실<br>(176석)</td>
                                                    <td><input type="text" class="usage-amount" value="1,540,000" readonly name="lec_no_6"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_6_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_6_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_6t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제5강의실(24석)</td>
                                                    <td><input type="text" class="usage-amount" value="275,000" readonly name="lec_no_7"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_7_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_7_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_7t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>제6강의실(20석)</td>
                                                    <td><input type="text" class="usage-amount" value="220,000" readonly name="lec_no_8"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_8_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_8_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_8t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>역사관 강의실<br>(54석)</td>
                                                    <td><input type="text" class="usage-amount" value="880,000" readonly name="lec_no_9"></td>
                                                    <td><input type="number" class="usage-people single-room" name="lec_no_9_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="lec_no_9_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_9t" placeholder="비고"></td>
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
                                                    <th>실</th>
                                                    <th>금액</th>
                                                    <th>비고</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<tr>	
													<td>
                                                        <select name="hansik_type" class="form-select" onchange="updateClassroomAmount(this)">
                                                            <option value="">한식타입</option>
                                                            <option value="9,000">한식1</option>
                                                            <option value="12,000">한식2</option>
                                                            <option value="15,000">한식3</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="hansik_no" class="usage-amount" value="0" readonly></td>
                                                    <td><input type="number" name="hansik_count" class="usage-people" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1000" oninput="numberMaxLength(this);">
													<span class="fee-currency">명</span></td>
                                                    <td><input type="text" class="total-amount" name="hansik_count_1" value="0" readonly></td>
                                                    <td><input type="text" name="hansik_not" class="usage-note" placeholder="비고"></td>
												</tr>
												<!--tr>
                                                    <td>한식</td>
                                                    <td><input type="number" class="usage-amount" value="15000" readonly></td>
                                                    <td><input type="number" class="usage-people" name="eat_no_1" value="" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" name="eat_no_1_1" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="eat_no_1t" placeholder="비고"></td>
                                                </tr-->
                                                <tr>
                                                    <td>시설사용료<br>(출장뷔페 사용시)</td>
                                                    <td><input type="text" class="usage-amount" value="300,000" readonly name="eat_no_1"></td>
                                                    <td><input type="number" class="usage-people single-room" name="eat_no_1_1" value="" onchange="calculateTotalAmount(this.closest('tr'))" max="1"></td>
                                                    <td><input type="text" class="total-amount" name="eat_no_1_2" value="0" readonly></td>
                                                    <td><input type="text" class="usage-note" name="eat_no_1t" placeholder="비고"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="classroom_type" class="form-select" onchange="updateClassroomplusAmount(this)">
                                                            <option value="">운동장</option>
                                                            <option value="2,750,000">199명 이하</option>
                                                            <option value="3,800,000">299명 이하</option>
                                                            <option value="13,000">599명 이하</option>
                                                            <option value="11,000">1000명이하</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="ground_no" class="usage-amount" value="0" readonly></td>
                                                    <td><input type="number" name="classroom_type_count" class="usage-people" value="" onchange="calculateTotalAmount(this.closest('tr'))" oninput="validateClassroomCount(this)" max="1000"></td>
                                                    <td><input type="text" class="total-amount" name="classroom_type_count_1" value="0" readonly></td>
                                                    <td><input type="text" name="ground_not" class="usage-note" placeholder="비고"></td>
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
                                        <input type="text" id="grand-total" class="fee-amount" readonly name="total_amount">
                                        <input type="hidden" id="hidden-grand-total" name="hidden_total_amount">
                                        <span class="fee-currency">원 (VAT 별도)</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">계약금 (10%)</span>
                                        <input type="text" id="contract-amount" class="fee-amount" readonly name="contract_amount">
                                        <input type="hidden" id="hidden-contract-amount" name="hidden_contract_amount">
                                        <span class="fee-currency">원</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">잔금</span>
                                        <input type="text" id="balance-amount" class="fee-amount" readonly name="balance_amount">
                                        <input type="hidden" id="hidden-balance-amount" name="hidden_balance_amount">
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
                    <textarea id="terms" class="terms-box" style="line-height:150%;" readonly>
1. 개인정보의 수집방법
	새마을중앙운동연수원 홈페이지에서는 기본적인 회원 서비스 제공을 위한 필수정보와 정보주체 각각의 기호와 필요에 맞는 서비스를 제공하기 위한 
	선택정보로 구분하여 다음의 정보를 수집하고 있습니다.
	선택정보를 입력하지 않은 경우에는 일부 서비스 이용에는 제한이 있습니다.

	홈페이지를 이용할 경우 이름,연락처 등 개인정보는 자동적으로 수집·저장됩니다.

2. 개인정보의 수집 이용목적 및 보유 이용기간
	예약신청 등록 홈페이지에서는 정보주체(신청자)의 등록 가입일로부터 신청자 등록 서비스를 이용하기 위한
	최소한의 개인정보를 보유 및 이용 하게 됩니다.
					</textarea>
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
                    <button type="button" id="submitBtn" class="btn btn-primary">요청하기</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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

            // 한식 금액 업데이트 함수
            window.updatehansikAmount = function(select) {
                const row = select.closest('tr');
                const amountInput = row.querySelector('.usage-amount');
                const selectedValue = select.value;
				var selectedValueresult;

				if(select.value == "13000")
				{
					if(Number(document.reservation_form.classroom_type_count.value) > 299 && Number(document.reservation_form.classroom_type_count.value) < 600)
					{
						selectedValueresult = select.value * Number(document.reservation_form.classroom_type_count.value); 
					}else{
						document.reservation_form.classroom_type_count.value = "0";
						alert("가능한 인원수는 300 ~ 599명까지 입니다.");
						select.selectedIndex = 0;
						return false;
					}
				}else if(select.value == "11000")
				{
					if(Number(document.reservation_form.classroom_type_count.value) > 599 && Number(document.reservation_form.classroom_type_count.value) <= 1000)
					{
						selectedValueresult = select.value * Number(document.reservation_form.classroom_type_count.value); 
					}else{
						document.reservation_form.classroom_type_count.value = "0";
						alert("가능한 인원수는 600 ~ 1000명까지 입니다.");
						select.selectedIndex = 0;
						return false;
					}
				}else selectedValueresult = selectedValue;
                
                if (amountInput) {
                    amountInput.value = selectedValue || 0;
                    calculateTotalAmount(row);
                }
            };

			// 강의실과 시설사용료 입력 필드 설정
            const restrictedInputs = [
                'eat_no_2',      // 시설사용료
                'lec_no_1',      // 제1강의실
                'lec_no_2',      // 제2강의실
                'lec_no_3',      // 제3강의실
                'lec_no_4',      // 제4강의실
                'lec_no_5',      // 제5강의실
                'lec_no_6',      // 제6강의실
                'lec_no_7',      // 제7강의실
                'lec_no_8'       // 역사관 강의실
            ];

            restrictedInputs.forEach(inputName => {
                const input = document.querySelector(`input[name="${inputName}"]`);
                if(input) {
                    input.max = 1;
                    input.addEventListener('input', function() {
                        if(Number(this.value) > 1) {
                            this.value = 1;
                            alert("강의실과 시설사용료는 실/인원을 1로 설정해주세요.");
                        }
                        calculateTotalAmount(this.closest('tr'));
                    });
                }
            });

			// 강의실 인원수 유효성 검사 함수
            function validateClassroomCount(input) {
                const select = input.closest('tr').querySelector('select[name="classroom_type"]');
                const selectedValue = select.value;
                
                // 입력값이 숫자가 아니면 0으로 설정
                if(isNaN(input.value) || input.value === '') {
                    input.value = 0;
                }
                
                // 강의실과 시설사용료 체크
                const restrictedInputs = [
                    'eat_no_2',      // 시설사용료
                    'lec_no_1',      // 제1강의실
                    'lec_no_2',      // 제2강의실
                    'lec_no_3',      // 제3강의실
                    'lec_no_4',      // 제4강의실
                    'lec_no_5',      // 제5강의실
                    'lec_no_6',      // 제6강의실
                    'lec_no_7',      // 제7강의실
                    'lec_no_8'       // 역사관 강의실
                ];
                
                if(restrictedInputs.includes(input.name)) {
                    if(Number(input.value) > 1) {
                        input.value = 1;
                        alert("강의실과 시설사용료는 실/인원을 1로 설정해주세요.");
                    }
                } else if(selectedValue === "2,750,000" || selectedValue === "3,800,000") {
                    if(Number(input.value) > 1) {
                        input.value = 1;
                        alert("199명/299명 이하는 실/인원을 1로 설정해주세요.");
                    }
                } else if(selectedValue === "13,000") {
                    if(Number(input.value) > 599) {
                        input.value = 599;
                        alert("가능한 인원수는 599명까지 입니다.");
                    }
                } else if(selectedValue === "11,000") {
                    if(Number(input.value) > 1000) {
                        input.value = 1000;
                        alert("가능한 인원수는 1000명까지 입니다.");
                    }
                }
                
                // 음수 입력 방지
                if(Number(input.value) < 0) {
                    input.value = 0;
                }
                
                calculateTotalAmount(input.closest('tr'));
            }

			// 강의실 금액 업데이트 함수
            window.updateClassroomplusAmount = function(select) {
                const row = select.closest('tr');
                const amountInput = row.querySelector('.usage-amount');
                const peopleInput = row.querySelector('.usage-people');
                const selectedValue = select.value;
                var selectedValueresult;

                if(select.value == "2,750,000") { // 199명 이하
                    if(peopleInput) {
                        peopleInput.max = 1;
                        if(Number(peopleInput.value) > 1) {
                            peopleInput.value = 1;
                            alert("199명 이하는 실/인원을 1로 설정해주세요.");
                        }
                    }
                    selectedValueresult = selectedValue;
                } else if(select.value == "3,800,000") { // 299명 이하
                    if(peopleInput) {
                        peopleInput.max = 1;
                        if(Number(peopleInput.value) > 1) {
                            peopleInput.value = 1;
                            alert("299명 이하는 실/인원을 1로 설정해주세요.");
                        }
                    }
                    selectedValueresult = selectedValue;
                } else if(select.value == "13,000") { // 599명 이하
                    if(peopleInput) {
                        peopleInput.max = 1000;
                        if(Number(peopleInput.value) > 599) {
                            peopleInput.value = 599;
                            alert("가능한 인원수는 599명까지 입니다.");
                        }
                    }
                    selectedValueresult = selectedValue;
                } else if(select.value == "11,000") { // 1000명 이하
                    if(peopleInput) {
                        peopleInput.max = 1000;
                        if(Number(peopleInput.value) > 1000) {
                            peopleInput.value = 1000;
                            alert("가능한 인원수는 1000명까지 입니다.");
                        }
                    }
                    selectedValueresult = selectedValue;
                } else {
                    if(peopleInput) {
                        peopleInput.max = 1000;
                    }
                    selectedValueresult = selectedValue;
                }
                
                if (amountInput) {
                    amountInput.value = selectedValue || 0;
                    calculateTotalAmount(row);
                }
            };

			// 강의실 금액 업데이트 함수
            window.updateClassroomAmount = function(select) {
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
                let totalAmount = 0;
                const amountInput = row.querySelector('.usage-amount');
                const peopleInput = row.querySelector('.usage-people');
                const totalInput = row.querySelector('.total-amount');
                
                if (amountInput && peopleInput && totalInput) {
                    const amount = parseFloat(amountInput.value.replace(/,/g, '')) || 0;
                    const people = parseInt(peopleInput.value) || 0;
                    const total = amount * people;
                    totalAmount += people * amount;
                    totalInput.value = total.toLocaleString('ko-KR');
                    updateGrandTotal();
                }
            };

            // 전체 합계 계산 함수
            window.updateGrandTotal = function() {
                const totalInputs = document.querySelectorAll('.total-amount');
                let grandTotal = 0;
                
                totalInputs.forEach(input => {
                    const value = parseFloat(input.value.replace(/,/g, '')) || 0;
                    grandTotal += value;
                });
                
                const grandTotalInput = document.getElementById('grand-total');
                const contractAmountInput = document.getElementById('contract-amount');
                const balanceAmountInput = document.getElementById('balance-amount');
                
                if (grandTotalInput) {
                    grandTotalInput.value = grandTotal.toLocaleString('ko-KR');
                    // hidden input에 콤마 없는 값 저장
                    const hiddenGrandTotal = document.getElementById('hidden-grand-total');
                    if (hiddenGrandTotal) {
                        hiddenGrandTotal.value = grandTotal;
                    }
                }
                
                if (contractAmountInput) {
                    // 계약금 계산 (총액의 10%)
                    let contractAmount = Math.round(grandTotal * 0.1);
                    
                   
                    
                   
             
                    contractAmountInput.value = contractAmount.toLocaleString('ko-KR');
                    // hidden input에 콤마 없는 값 저장
                    const hiddenContractAmount = document.getElementById('hidden-contract-amount');
                    if (hiddenContractAmount) {
                        hiddenContractAmount.value = contractAmount;
                    }
                    
                    // 잔금 자동 계산
                    if (balanceAmountInput) {
                        const balance = grandTotal - contractAmount;
                        balanceAmountInput.value = balance.toLocaleString('ko-KR');
                        // hidden input에 콤마 없는 값 저장
                        const hiddenBalanceAmount = document.getElementById('hidden-balance-amount');
                        if (hiddenBalanceAmount) {
                            hiddenBalanceAmount.value = balance;
                        }
                    }
                }
            };

            // 잔금 계산 함수
            window.calculateBalance = function() {
                const grandTotalInput = document.getElementById('grand-total');
                const contractAmountInput = document.getElementById('contract-amount');
                const balanceAmountInput = document.getElementById('balance-amount');
                
                if (grandTotalInput && contractAmountInput && balanceAmountInput) {
                    const grandTotal = parseFloat(grandTotalInput.value.replace(/,/g, '')) || 0;
                    const contractAmount = parseFloat(contractAmountInput.value.replace(/,/g, '')) || 0;
                    
                    // 계약금 제한 적용
                    let validContractAmount = Math.round(grandTotal * 0.1);
                    validContractAmount = Math.max(1000000, Math.min(5000000, validContractAmount));
                    validContractAmount = Math.round(validContractAmount / 100000) * 100000;
                    
                    const balance = grandTotal - validContractAmount;
                    
                    balanceAmountInput.value = balance.toLocaleString('ko-KR');
                    contractAmountInput.value = validContractAmount.toLocaleString('ko-KR');
                    
                    // hidden input에 콤마 없는 값 저장
                    const hiddenContractAmount = document.getElementById('hidden-contract-amount');
                    const hiddenBalanceAmount = document.getElementById('hidden-balance-amount');
                    if (hiddenContractAmount) {
                        hiddenContractAmount.value = validContractAmount;
                    }
                    if (hiddenBalanceAmount) {
                        hiddenBalanceAmount.value = balance;
                    }
                }
            };

            // 모든 행에 이벤트 리스너 추가
            const planTableRows = document.querySelectorAll('.plan-table tbody tr');
            planTableRows.forEach(row => {
                const peopleInput = row.querySelector('.usage-people');
                const amountInput = row.querySelector('.usage-amount');
                const classroomSelect = row.querySelector('.classroom-select');

                if (peopleInput) {
                    peopleInput.addEventListener('input', function() {
                        calculateTotalAmount(row);
                    });
                }

                if (classroomSelect) {
                    classroomSelect.addEventListener('change', function() {
                        updateClassroomAmount(this);
                    });
                }

                // 초기 금액 설정
                if (amountInput && !amountInput.value) {
                    amountInput.value = '66000';
                }
            });

            // 계약금 입력 시 숫자만 입력되도록 설정
            const contractAmountInput = document.getElementById('contract-amount');
            if (contractAmountInput) {
                contractAmountInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    const grandTotal = parseFloat(document.getElementById('grand-total').value.replace(/,/g, '')) || 0;
                    const contractAmount = parseFloat(this.value) || 0;
                    
                    if (contractAmount > grandTotal) {
                        this.value = grandTotal;
                    }
                    
                    calculateBalance();
                });
            }

            // 초기 총액 계산
            updateGrandTotal();

            // 예약하기 버튼 클릭 이벤트
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // 모든 에러 스타일 초기화
                    document.querySelectorAll('.input-error').forEach(field => {
                        field.classList.remove('input-error');
                    });

                    // 필수 입력 필드 검증
                    const requiredFields = [
                        { name: 'company_name', label: '기관(업체)명' },
                        { name: 'representative', label: '대표자' },
                        { name: 'phone', label: '전화번호' },
                        { name: 'address', label: '주소' },
                        { name: 'business_number', label: '사업자등록번호' },
                        { name: 'tax_manager_name', label: '담당자 성명' },
                        { name: 'tax_manager_phone', label: '담당자 휴대폰' },
                        { name: 'email_id', label: '이메일' },
                        { name: 'email_domain', label: '이메일 도메인' }
                    ];

                    let firstEmptyField = null;
                    let emptyFields = [];

                    // 각 필수 필드 검증
                    for (const field of requiredFields) {
                        const input = document.querySelector(`input[name="${field.name}"], select[name="${field.name}"], textarea[name="${field.name}"]`);
                        if (input && !input.value.trim()) {
                            input.classList.add('input-error');
                            emptyFields.push({ input: input, label: field.label });
                            if (!firstEmptyField) {
                                firstEmptyField = input;
                            }
                        }
                    }

                    // 필수 필드가 비어있으면 포커스하고 알림
                    if (emptyFields.length > 0) {
                        const emptyFieldLabels = emptyFields.map(field => field.label).join(', ');
                        
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
					
					var msg = "신청하시겠습니까?";
                    if (confirm(msg)){
						//document.reservation_form.submit();
						window.open("reservation_pop.php?" + params.toString(),"confirm_window");
					}
                    //window.location.href = 'reservation_confirm.php?' + params.toString();
                });
            }

            // 약관 동의 체크 이벤트
            const agreeCheckbox = document.getElementById('agree');
            if (agreeCheckbox) {
                agreeCheckbox.addEventListener('change', function() {
                    const submitBtn = document.getElementById('submitBtn');

                    // 체크박스 상태에 따라 에러 스타일 토글
                    this.classList.toggle('input-error', !this.checked);
                });
            }

            // 입력 필드 포커스 시 에러 스타일 제거
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('focus', function() {
                    this.classList.remove('input-error');
                });
            });

            // 이메일 도메인 선택 시 자동 입력
            const emailSelect = document.querySelector('select[name="email_select"]');
            if (emailSelect) {
                emailSelect.addEventListener('change', function() {
                    const domainInput = document.querySelector('input[name="email_domain"]');
                    if (domainInput && this.value) {
                        domainInput.value = this.value;
                        domainInput.classList.remove('input-error');
                    }
                });
            }

            // 운동장 인원수 입력 필드에 이벤트 리스너 추가
            const classroomCountInput = document.querySelector('input[name="classroom_type_count"]');
            if(classroomCountInput) {
                classroomCountInput.addEventListener('input', function() {
                    validateClassroomCount(this);
                });
            }

            // single-room 클래스를 가진 모든 입력 필드에 이벤트 리스너 추가
            const singleRoomInputs = document.querySelectorAll('.single-room');
            singleRoomInputs.forEach(input => {
                input.addEventListener('input', function() {
                    if(Number(this.value) > 1) {
                        this.value = 1;
                        alert("강의실과 시설사용료는 실/인원을 1로 설정해주세요.");
                    }
                    calculateTotalAmount(this.closest('tr'));
                });
            });
        });

		 function numberMaxLength(e){

			if(e.value > 1000){
				e.value = e.value.slice(0, e.maxLength);
			}
		}

		function is_checked() {    
			const checkbox = document.getElementById('use_etc');  
			const is_checked = checkbox.checked;
			if (is_checked) document.getElementById('use_etc_txt').style.display='block';
			else document.getElementById('use_etc_txt').style.display='none';
		}

		function formatPhoneNumber() {
		  const business_number_phoneInput = document.getElementById("business_number");
		  let phoneNumber = business_number_phoneInput.value.replace(/[^0-9]/g, ""); // 입력된 값에서 숫자만 추출합니다.
		  
		  if (phoneNumber.length > 5) {
			phoneNumber = phoneNumber.replace(/(\d{3})(\d{2})(\d{5})/, "$1-$2-$3"); // 전화번호를 형식에 맞게 변환합니다.
		  }
		  
		  business_number_phoneInput.value = phoneNumber; // 변환된 전화번호를 입력란에 설정합니다.
		}
		document.getElementById("business_number").addEventListener("focus", formatPhoneNumber);

		function formatPNumber(target) {
			// 숫자만 추출
			let number = target.value.replace(/[^0-9]/g, '');
			
			// 전화번호 형식에 맞게 하이픈 추가
			if (number.length > 0) {
				if (number.length <= 3) {
					number = number;
				} else if (number.length <= 7) {
					number = number.slice(0, 3) + '-' + number.slice(3);
				} else {
					number = number.slice(0, 3) + '-' + number.slice(3, 7) + '-' + number.slice(7, 11);
				}
			}
			
			target.value = number;
		}

		// 전화번호 입력 필드에 이벤트 리스너 추가
		document.addEventListener('DOMContentLoaded', function() {
			const phoneInputs = document.querySelectorAll('input[type="tel"]');
			phoneInputs.forEach(input => {
				input.addEventListener('input', function() {
					formatPNumber(this);
				});
				
				// 포커스가 벗어날 때도 형식 확인
				input.addEventListener('blur', function() {
					formatPNumber(this);
				});
			});
		});

		function complete_sender() {    
			document.reservation_form.submit();
		}

        // CSS 스타일 추가
        const style = document.createElement('style');
        style.textContent = `
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
`;
document.head.appendChild(style);
    </script>
</body>
</html>