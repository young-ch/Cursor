<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../include/common/conf.php");
require_once("../../include/common/dbconn.php");
require_once("../../include/common/function.php");
require_once("../../include/common/pageout.php");
require_once("../../include/dao/admin_menu.php");
require_once("../../include/dao/code_list.php");
require_once("../../include/dao/class_reserve.php");
?>
<?
// ##########################################################
// 회원권한조회
// ##########################################################
$thisPageAuthrityMethod = array("S");
$current_page_name = "reserve";
//require_once("../../include/common/page_authority.php");

if ($_SESSION["SS_ADM_ID"]==null||$_SESSION["SS_ADM_ID"]==''){
	//echo "페이지 인증정보 없음.";
	echo "<script language=\"javascript\">";
	echo "alert(\"로그인이 필요합니다.\");location.href=\"/admin/main/login.php\";";
	echo "</script>";
	exit;
}

?>
<?
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
$searchValue = isset($_REQUEST['searchValue']) ? $_REQUEST['searchValue'] : '';
$listSize = $listSize == '' ? '10' : $listSize;  
$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$page = sqlInject(rejectXss($page));
$searchValue = sqlInject(rejectXss($searchValue));
$startDate = sqlInject(rejectXss($startDate));
$endDate = sqlInject(rejectXss($endDate));
$listSize = sqlInject(rejectXss($listSize));
$id = sqlInject(rejectXss($id));

if(is_empty($id)) {
	echo "
	<Script>
		alert('필수값이 없습니다.');
		history.back();
	</Script>
	";
	exit;
}

$gConn = new DBConn();

$obj = new ReserveSql("reservations");
$Result = $obj->SelectWithSeq ( $id, $gConn->mConn );

//$content = reWebContent(strHtmlRe($Result[0]->content));
//$content = reWebContent($Result[0]->content);
$tax_manager_email = explode("@",$Result[0]->tax_manager_email);
$facility_array = explode("///",$Result[0]->facility);

$gConn->DisConnect();

$CommLink="?searchValue=$searchValue&page=$page&listSize=$listSize&startDate=$startDate&endDate=$endDate";
//echo $Result[0]->place;
?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" rel="stylesheet">
    <link href="/reservations/styles.css" rel="stylesheet">

	<style>
	#use_etc_txt {display:none;}
	</style>
<? include "../common/topmenu.php" ?>


	<script language="javascript">
	<!--
	
	function deleteConfirm(){
		if (confirm("삭제하시겠습니까?")){
			$('#frmWrite').attr("action","delete_exec.php");
			$('#frmWrite').submit();
		}
		return;
	}
	//-->
	</script>

	<form name="frmWrite" id="frmWrite" method="post">
	<input type="hidden" name="page" value="<?=$page?>">
	<input type="hidden" name="searchValue" value="<?=$searchValue?>">
	<input type="hidden" name="listSize" value="<?=$listSize?>">
	<input type="hidden" name="startDate" value="<?=$startDate?>">
	<input type="hidden" name="endDate" value="<?=$endDate?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<?
	// CSRF 취약점 대응.
	$token = md5(uniqid(rand(),true));
	$_SESSION['TOKEN'] = $token;	
	?>
	<input type="hidden" name="token" value="<?=$token?>">

	<div class="admincontbox">
	  <div class="admincont">
			<h3>온라인예약  수정</h3>

			<div class="admboard-rapper mt-20">
				
				<table class="form-table">
                    <tr>
                        <th>기관(업체)명 <span class="required">*</span></th>
                        <td><input type="text" name="company_name" value="<?=$Result[0]->company_name?>" required></td>
                        <th>대표자 <span class="required">*</span></th>
                        <td><input type="text" name="representative" value="<?=$Result[0]->representative?>" required></td>
                    </tr>
                    <tr>
                        <th>전화 <span class="required">*</span></th>
                        <td><input type="tel" name="phone" value="<?=$Result[0]->phone?>" required></td>
                        <th>주소 <span class="required">*</span></th>
                        <td><input type="text" name="address" value="<?=$Result[0]->address?>" required></td>
                    </tr>
                    <tr>    
                        <th>증빙종류</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_accommodation" name="doc_tax_invoice" value="1" <?=$Result[0]->doc_tax_invoice == "1" ? "checked" : ""; ?>>
                                    <label for="doc_accommodation">세금계산서</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_hall" name="doc_tax_invoice" value="2" <?=$Result[0]->doc_tax_invoice == "2" ? "checked" : ""; ?>>
                                    <label for="doc_hall">카드</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_facility" name="doc_tax_invoice" value="3" <?=$Result[0]->doc_tax_invoice == "3" ? "checked" : ""; ?>>
                                    <label for="doc_facility">현금영수증</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="radio" id="doc_etc" name="doc_tax_invoice" value="4" <?=$Result[0]->doc_tax_invoice == "4" ? "checked" : ""; ?>>
                                    <label for="doc_etc">해당없음</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>사업자등록번호 <span class="required">*</span></th>
                        <td colspan="2"><input type="text" name="business_number" value="<?=$Result[0]->business_number?>" required></td>
                        <td><a href="file_download.php?orgName=<?=$Result[0]->file_org_name?>&svcName=<?=$Result[0]->file_svc_name?>"><?=$Result[0]->file_org_name?></a></td>
                    </tr>
                    <tr>    
                        <th>사용범위</th>
                        <td colspan="3">
                            <div class="document-checkboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="usage_accommodation" name="usage_accommodation" value="1" <?=$Result[0]->usage_accommodation == "1" ? "checked" : ""; ?>>
                                    <label for="use_accommodation">숙소</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_hall" name="usage_hall" value="1" <?=$Result[0]->usage_hall == "1" ? "checked" : ""; ?>
                                    <label for="use_hall">강당/강의실</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_facility" name="usage_facility" value="1" <?=$Result[0]->usage_facility == "1" ? "checked" : ""; ?>
                                    <label for="use_facility">부대시설</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="use_etc" name="usage_etc" onclick="is_checked()"  value="1" <?=$Result[0]->usage_etc == "" ? "" : "checked"; ?>
                                    <label for="use_etc">협의사항</label>
									<input type="text" id="use_etc_txt" name="use_etc_txt" value="<?=$Result[0]->usage_etc?>">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>전자세금계산서 <br>담당자 <span class="required">*</span></th>
                        <td colspan="3">
                            <div class="tax-manager-group">
                                <div class="name-phone-row">
                                    <input type="text" name="tax_manager_name" placeholder="성명" value="<?=$Result[0]->tax_manager_name?>" required>
                                    <input type="text" name="tax_manager_phone" placeholder="휴대폰" value="<?=$Result[0]->tax_manager_phone?>" required>
                                </div>
                                <div class="email-row">
                                    <div class="email-group">
                                        <input type="text" name="email_id" placeholder="이메일" value="<?=$tax_manager_email[0]?>" required>
                                        <span>@</span>
                                        <input type="text" name="email_domain" value="<?=$tax_manager_email[1]?>" required>
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
                        <td colspan="3"><input type="text" name="purpose" value="<?=$Result[0]->purpose?>"></td>
                    </tr>
                    <tr>
                        <th>사용인원</th>
                        <td>
                            <div class="input-group">
                                <input type="number" name="total_people" id="total_people" value="<?=$Result[0]->total_people?>">
                                <span>명</span>
                            </div>
                        </td>
                        <th>예약 기간</th>
                        <td colspan="2">
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date" value="<?=$Result[0]->start_date?>">
                                <span>~</span>
                                <input type="date" name="end_date" id="end_date" value="<?=$Result[0]->end_date?>">
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
									<?
										$facility_first_array = explode("|||",$facility_array[0]);
										$facility_first_array1 = explode("==",$facility_first_array[0]);
										$facility_first_array2 = explode("==",$facility_first_array[1]);
										$facility_first_array3 = explode("==",$facility_first_array[2]);
										$facility_first_array4 = explode("==",$facility_first_array[3]);
										$facility_first_array5 = explode("==",$facility_first_array[4]);
									?>
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
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array1[0]?>" name="room_no_1" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="room_no_1_1" value="<?=$facility_first_array1[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array1[0]*$facility_first_array1[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="room_no_1t" placeholder="비고" value="<?=$facility_first_array1[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>3인실(6호실)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array2[0]?>" name="room_no_2" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="room_no_2_1" value="<?=$facility_first_array2[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array2[0]*$facility_first_array2[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="room_no_2t" placeholder="비고" value="<?=$facility_first_array2[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>4인실(59호실)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array3[0]?>" name="room_no_3" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="room_no_3_1" value="<?=$facility_first_array3[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array3[0]*$facility_first_array3[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="room_no_3t" placeholder="비고" value="<?=$facility_first_array3[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>5인실(1호실)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array4[0]?>" name="room_no_4" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="room_no_4_1" value="<?=$facility_first_array4[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array4[0]*$facility_first_array4[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="room_no_4t" placeholder="비고" value="<?=$facility_first_array4[2]?>"></td>
                                                </tr>
												 <tr>
                                                    <td>8인실(8호실)</td>
                                                    <td><input type="text" class="usage-amount" value="<?=$facility_first_array5[0]?>" name="room_no_5" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="room_no_5_1" value="<?=$facility_first_array5[1]?>" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="text" class="total-amount" value="<?=$facility_first_array5[0]*$facility_first_array5[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="room_no_5t" placeholder="비고" value="<?=$facility_first_array5[2]?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- 강의실 탭 -->
									<? 
										$facility_first_array = explode("|||",$facility_array[1]);
										$facility_first_array1 = explode("==",$facility_first_array[0]);
										$facility_first_array2 = explode("==",$facility_first_array[1]);
										$facility_first_array3 = explode("==",$facility_first_array[2]);
										$facility_first_array4 = explode("==",$facility_first_array[3]);
										$facility_first_array5 = explode("==",$facility_first_array[4]);
									?>
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
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array1[0]?>" name="lec_no_1" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_1_1" value="<?=$facility_first_array1[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array1[0]*$facility_first_array1[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_1t" placeholder="비고" value="<?=$facility_first_array1[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>제1강의실(84석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array2[0]?>" name="lec_no_2" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_2_1" value="<?=$facility_first_array2[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array2[0]*$facility_first_array2[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_2t" placeholder="비고" value="<?=$facility_first_array2[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>제2강의실(120석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array3[0]?>" name="lec_no_3" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_3_1" value="<?=$facility_first_array3[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array3[0]*$facility_first_array3[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_3t" placeholder="비고" value="<?=$facility_first_array3[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>제3강의실(208석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array4[0]?>" name="lec_no_4" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_4_1" value="<?=$facility_first_array4[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array4[0]*$facility_first_array4[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_4t" placeholder="비고" value="<?=$facility_first_array4[2]?>"></td>
                                                </tr>
												<tr>
                                                    <td>토의실(30석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array5[0]?>" name="lec_no_5" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_5_1" value="<?=$facility_first_array5[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array5[0]*$facility_first_array5[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_4t" placeholder="비고" value="<?=$facility_first_array5[2]?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
									<? 
										$facility_first_array = explode("|||",$facility_array[2]);
										$facility_first_array1 = explode("==",$facility_first_array[0]);
										$facility_first_array2 = explode("==",$facility_first_array[1]);
										$facility_first_array3 = explode("==",$facility_first_array[2]);
										$facility_first_array4 = explode("==",$facility_first_array[3]);
									?>
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
                                                    <td>제4강의실(176석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array1[0]?>" name="lec_no_6" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_6_1" value="<?=$facility_first_array1[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array1[0]*$facility_first_array1[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_6t" placeholder="비고" value="<?=$facility_first_array1[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>제5강의실(24석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array2[0]?>" name="lec_no_7" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_7_1" value="<?=$facility_first_array2[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array2[0]*$facility_first_array2[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_7t" placeholder="비고" value="<?=$facility_first_array2[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>제6강의실(20석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array3[0]?>" name="lec_no_8" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_8_1" value="<?=$facility_first_array3[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array3[0]*$facility_first_array3[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_8t" placeholder="비고" value="<?=$facility_first_array3[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>역사관 강의실(54석)</td>
                                                    <td><input type="number" class="usage-amount" value="<?=$facility_first_array4[0]?>" name="lec_no_9" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="lec_no_9_1" value="<?=$facility_first_array4[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array4[0]*$facility_first_array4[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="lec_no_9t" placeholder="비고" value="<?=$facility_first_array4[2]?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- 부대시설 탭 -->
									<?
										$facility_first_array = explode("|||",$facility_array[3]);
										$facility_first_array1 = explode("==",$facility_first_array[0]);
										$facility_first_array2 = explode("==",$facility_first_array[1]);
										$facility_first_array3 = explode("==",$facility_first_array[2]);
									?>
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
                                                <!--tr>
                                                    <td>한식</td>
                                                    <td><input type="number" class="usage-amount" value="15000" readonly></td>
                                                    <td><input type="number" class="usage-people" name="eat_no_1" value="<?=$facility_first_array1[0]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array1[0]*15000?>" readonly></td>
                                                    <td><input type="text" class="usage-note" name="eat_no_1t" placeholder="비고" value="<?=$facility_first_array1[1]?>"></td>
                                                </tr-->
												<tr>	
													<td>
                                                        <select name="hansik_type" onchange="updateClassroomAmount(this)" class="selbox">
                                                            <option value="">한식타입</option>
                                                            <option value="9,000" <?=$$facility_first_array1[0] == "9000" ? "selected" : ""; ?>>한식1</option>
                                                            <option value="12,000" <?=$$facility_first_array1[0] == "12000" ? "selected" : ""; ?>>한식2</option>
                                                            <option value="15,000" <?=$$facility_first_array1[0] == "15000" ? "selected" : ""; ?>>한식3</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="hansik_no" class="usage-amount" value="<?=$facility_first_array1[0]?>" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" name="hansik_count" class="usage-people" value="<?=$facility_first_array1[1]?>" onchange="calculateTotalAmount(this.closest('tr'))" max="1000" oninput="numberMaxLength(this);"></td>
                                                    <td><input type="text" class="total-amount" name="hansik_count_1" value="<?=$facility_first_array1[0]*$facility_first_array1[1]?>" ></td>
                                                    <td><input type="text" name="hansik_not" class="usage-note" placeholder="비고" value="<?=$facility_first_array1[2]?>"></td>
												</tr>
                                                <tr>
                                                    <td>시설사용료</td>
                                                    <td><input type="number" class="usage-amount" name="eat_no_1" value="<?=$facility_first_array2[0]?>" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="usage-people" name="eat_no_1_1" value="<?=$facility_first_array2[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array2[0]*$facility_first_array2[1]?>" ></td>
                                                    <td><input type="text" class="usage-note" name="eat_no_1t" placeholder="비고" value="<?=$facility_first_array2[2]?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="classroom_type" onchange="updateClassroomplusAmount(this)" class="selbox">
                                                            <option value="">잔디운동장</option>
                                                            <option value="2750000" <?=$facility_first_array3[0] == "2750000" ? "selected" : ""; ?>>199명 이하</option>
                                                            <option value="3800000" <?=$facility_first_array3[0] == "3800000" ? "selected" : ""; ?>>299명 이하</option>
                                                            <option value="13000" <?=$facility_first_array3[0] == "13000" ? "selected" : ""; ?>>599명 이하</option>
                                                            <option value="11000" <?=$facility_first_array3[0] == "11000" ? "selected" : ""; ?>>1000명이하</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="ground_no" class="usage-amount" value="<?=$facility_first_array3[0]?>" onchange="calculateTotalAmount(this.closest('tr'))"></td>
                                                    <td><input type="number" name="classroom_type_count" class="usage-people" value="<?=$facility_first_array3[1]?>" min="0" onchange="calculateTotalAmount(this.closest('tr'))" max="1000" oninput="numberMaxLength(this);"></td>
                                                    <td><input type="number" class="total-amount" value="<?=$facility_first_array3[0]*$facility_first_array3[1]?>" ></td>
                                                    <td><input type="text" name="ground_not" class="usage-note" placeholder="비고" value="<?=$facility_first_array3[2]?>"></td>
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
                                        <input type="text" id="grand-total" class="fee-amount" readonly name="total_amount" value="<?=$Result[0]->total_amount?>">
                                        <span class="fee-currency">원</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">계약금 (10%)</span>
                                        <input type="number" id="contract-amount" class="fee-amount" min="0" onchange="calculateBalance()" name="contract_amount" value="<?=$Result[0]->contract_amount?>">
                                        <span class="fee-currency">원</span>
                                    </div>
                                    <div class="fee-row">
                                        <span class="fee-label">잔금</span>
                                        <input type="text" id="balance-amount" class="fee-amount" readonly name="balance_amount" value="<?=$Result[0]->balance_amount?>">
                                        <span class="fee-currency">원</span>
                                    </div>
                                </div>
                                
								<span class="fee-label">현재상태</span>
								<select name="status" class="selbox">
									<option value="pending" <?=$Result[0]->status == "pending" ? "selected" : ""; ?>>pending</option>
									<option value="confirmed" <?=$Result[0]->status == "confirmed" ? "selected" : ""; ?>>confirmed</option>
									<option value="cancelled" <?=$Result[0]->status == "cancelled" ? "selected" : ""; ?>>cancelled</option>
									<option value="completed" <?=$Result[0]->status == "completed" ? "selected" : ""; ?>>completed</option>
								</select>
                            </div>
                        </td>
                        <td>
                         
                        </td>
                    </tr>
                </table>

			</div>

			<div class="adm_board_btn">
				<a href="#" class="admbtn_add" id="submitBtn">저장</a>
				<a href="javascript:deleteConfirm();" class="admbtn_type04">삭제</a>
				<a href="list.php<?=$CommLink?>" class="admbtn_type03">목록</a>
			</div>

	  </div>
	</div>

	</form>	


<? include "../common/footer.php" ?>

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

            // 강의실 금액 업데이트 함수
            window.updateClassroomplusAmount = function(select) {
                const row = select.closest('tr');
                const amountInput = row.querySelector('.usage-amount');
                const selectedValue = select.value;
				var selectedValueresult;

				if(select.value == "13000")
				{
					if(Number(document.frmWrite.classroom_type_count.value) > 299 && Number(document.frmWrite.classroom_type_count.value) < 600)
					{
						selectedValueresult = select.value * Number(document.frmWrite.classroom_type_count.value); 
					}else{
						document.frmWrite.classroom_type_count.value = "0";
						alert("가능한 인원수는 300 ~ 599명까지 입니다.");
						select.selectedIndex = 0;
						return false;
					}
				}else if(select.value == "11000")
				{
					if(Number(document.frmWrite.classroom_type_count.value) > 599 && Number(document.frmWrite.classroom_type_count.value) <= 1000)
					{
						selectedValueresult = select.value * Number(document.frmWrite.classroom_type_count.value); 
					}else{
						document.frmWrite.classroom_type_count.value = "0";
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
                let totalAmount = 0
                const amountInput = row.querySelector('.usage-amount');
                const peopleInput = row.querySelector('.usage-people');
                const totalInput = row.querySelector('.total-amount');
                
                if (amountInput && peopleInput && totalInput) {
                    const amount = parseFloat(amountInput.value) || 0;
                    const people = parseInt(peopleInput.value) || 0;
                    const total = amount * people;
                    totalAmount += people * amount;
                    totalInput.value = total;
                    updateGrandTotal();
                }
            };

            // 전체 합계 계산 함수
            window.updateGrandTotal = function() {
                const totalInputs = document.querySelectorAll('.total-amount');
                let grandTotal = 0;
                
                totalInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    grandTotal += value;
                });
                
                const grandTotalInput = document.getElementById('grand-total');
                const contractAmountInput = document.getElementById('contract-amount');
                
                if (grandTotalInput) {
                    grandTotalInput.value = grandTotal.toLocaleString('ko-KR');
                }
                
                if (contractAmountInput) {
                    const contractAmount = Math.round(grandTotal * 0.1);
                    contractAmountInput.value = contractAmount;
                    calculateBalance();
                }
            };

            // 잔금 계산 함수
            window.calculateBalance = function() {
                const grandTotalInput = document.getElementById('grand-total');
                const contractAmountInput = document.getElementById('contract-amount');
                const balanceAmountInput = document.getElementById('balance-amount');
                
                if (grandTotalInput && contractAmountInput && balanceAmountInput) {
                    const grandTotal = parseFloat(grandTotalInput.value.replace(/,/g, '')) || 0;
                    const contractAmount = parseFloat(contractAmountInput.value) || 0;
                    
                    const validContractAmount = Math.min(contractAmount, grandTotal);
                    const balance = grandTotal - validContractAmount;
                    
                    balanceAmountInput.value = balance.toLocaleString('ko-KR');
                    
                    if (contractAmount !== validContractAmount) {
                        contractAmountInput.value = validContractAmount;
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
					$('#frmWrite').attr("action","modify_exec.php");
					document.frmWrite.submit();
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

		function deleteConfirm(){
			if (confirm("삭제하시겠습니까?")){
				$('#frmWrite').attr("action","delete_exec.php");
				$('#frmWrite').submit();
			}
			return;
		}

		<?
			IF($Result[0]->usage_etc != "") echo "is_checked();";
		?>

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