<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../include/common/conf.php");
require_once("../../include/common/dbconn.php");
require_once("../../include/common/function.php");
require_once("../../include/common/pageout.php");
require_once("../../include/dao/admin_menu.php");
require_once("../../include/dao/board_file.php");
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
$boardId = isset($_REQUEST['boardId']) ? $_REQUEST['boardId'] : '';
$listSize = isset($_REQUEST['listSize']) ? $_REQUEST['listSize'] : '';
$listSize = $listSize == '' ? '10' : $listSize;  

$startDate = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '';
$endDate = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '';
$startDate = $startDate == '' ? date("Y-m-d",strtotime("-10 year", time())) : $startDate;  // 기본 10년이전.
$endDate = $endDate == '' ? date("Y-m-d",time()) : $endDate;
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';

$page = sqlInject(rejectXss($page));
$searchValue = sqlInject(rejectXss($searchValue));
$boardId = sqlInject(rejectXss($boardId));
$startDate = sqlInject(rejectXss($startDate));
$endDate = sqlInject(rejectXss($endDate));
$category = sqlInject(rejectXss($category));
$listSize = sqlInject(rejectXss($listSize));

if (!$page) $page=1;

$gConn = new DBConn();
$obj = new ReserveSql("reservations");

$pWhere=" where 1=1 ";
if ($startDate) $pWhere .=" and created_at >= '$startDate.:00:00:00' ";
if ($endDate) $pWhere .=" and created_at <= '$endDate.:23:99:99' ";
if ($searchValue) $pWhere .=" and (company_name like '%".$searchValue."%' or tax_manager_name like '%".$searchValue."%') ";

$PageLs = $obj->PageLs ( $pWhere, $page, $listSize, $gConn->mConn);
$gConn->DisConnect();

$PageLink="?page=$page&listSize=$listSize&startDate=$startDate&endDate=$endDate";
$CommLink="?searchValue=$searchValue&listSize=$listSize&startDate=$startDate&endDate=$endDate";
?>

<? include "../common/topmenu.php" ?>

<link rel="stylesheet" href="../css/jquery-ui.css">
<script>
$(function() {
  $( "#startDate, #endDate" ).datepicker({
	 dateFormat: 'yy-mm-dd',
	 prevText: '이전 달',
	 nextText: '다음 달',
	 monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
	 monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
	 dayNames: ['일','월','화','수','목','금','토'],
	 dayNamesShort: ['일','월','화','수','목','금','토'],
	 dayNamesMin: ['일','월','화','수','목','금','토'],
	 showMonthAfterYear: true,
	 yearSuffix: '년'
  });
});
$(function() {
  $( "#startDate" ).datepicker({
	 dateFormat: 'yy-mm-dd'
  });
  $( "#endDate" ).datepicker({
	 dateFormat: 'yy-mm-dd'
  });
});
</script>


<script type="text/javascript">
<!--
function fn_submit() {
	$('#frmList').submit();
}	
//-->
</script>


<form name="frmList" id="frmList" action="<?=$PageLink?>" method="post">
	<input type="hidden" name="boardId" value="<?=$boardId?>">
	<?
	// CSRF 취약점 대응.
	$token = md5(uniqid(rand(),true));
	$_SESSION['TOKEN'] = $token;	
	?>
	<input type="hidden" name="token" value="<?=$token?>">

	<div class="admincontbox">
	  <div class="admincont">
			<h3>온라인예약 목록</h3>

			<div class="topsearcharealog">
				<div class="topsearchmember_text">	
					
					<div>
						<font color='#2262cc'>신청일</font> :&nbsp; <input type="text" class="textform" style="width:15%;" id="startDate" name="startDate" value="<?=$startDate?>">
						~
						<input type="text" class="textform" style="width:15%;" id="endDate" name="endDate" value="<?=$endDate?>">
						<font color='#2262cc'>업체명/담당자</font>:&nbsp;
						<input type="text" class="textform" name="searchValue" id="searchValue" value="<?=$searchValue?>" style="width:200px;"/>
					</div>
			  </div>
			  <span class="btn_search">
					<a href="javascript:fn_submit()" class="admbtn_search">검색</a>
			  </span>			  
			</div>

			<div class="total-count">
				<select name="listSize" id="listSize" class="selbox" onchange="fn_submit();">
					<option value="10" <?if ($listSize=='10'){ ?> selected="selected" <?}?>>10개</option>
					<option value="30" <?if ($listSize=='30'){ ?> selected="selected" <?}?>>30개</option>
					<option value="50" <?if ($listSize=='50'){ ?> selected="selected" <?}?>>50개</option>
					<option value="100" <?if ($listSize=='100'){ ?> selected="selected" <?}?>>100개</option>
				</select> 총 <?=$PageLs->mListAll?>건
			</div>

			<div class="admboard-rapper">
				<table width="100%" class="adm_boardlist">
					<colgroup>
						<col width="5%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<thead>
					<tr>
						<th scope="col">NO</th>
						<th scope="col">업체명</th>
						<th scope="col">전화</th>
						<th scope="col">사업자번호</th>
						<th scope="col">담당자</th>
						<th scope="col">휴대폰</th>
						<th scope="col">이메일</th>
						<th scope="col">사용인원</th>
						<th scope="col">현재상태</th>
						<th scope="col">신청일자</th>
					</tr>
					</thead>
					<tbody>

					<?
					if(count($PageLs->mData)) {
						$pTotal = $PageLs->mListAll;

						for($i=0; $i<count($PageLs->mData); $i++) {
							$RowNo = $pTotal - $PageLs->mSizePage * ($page - 1);
					?>
							<tr onclick="document.location='modify.php<?=$CommLink?>&id=<?=$PageLs->mData[$i]->id?>&page=<?=$page?>';" style="cursor:pointer;">
								<td><?=$RowNo?></td>
								<td><a href="modify.php<?=$CommLink?>&id=<?=$PageLs->mData[$i]->id?>&page=<?=$page?>"><?=$PageLs->mData[$i]->company_name?></a></td>
								<td><?=$PageLs->mData[$i]->phone?></td>
								<td><?=$PageLs->mData[$i]->business_number?></td>
								<td><?=$PageLs->mData[$i]->tax_manager_name?></td>
								<td><?=$PageLs->mData[$i]->tax_manager_phone?></td>
								<td><?=$PageLs->mData[$i]->tax_manager_email?></td>
								<td><?=$PageLs->mData[$i]->total_people?></td>
								<td><?=$PageLs->mData[$i]->status?></td>
								<td><?=substr($PageLs->mData[$i]->created_at,0,10)?></td>
							</tr>
					<?
							$pTotal = $pTotal - 1;			//번호 desc
						}
					}
					else{
					?>
						<tr>
							<td colspan="10" align="center">조회된 데이타가 없습니다.</td>
						</tr>
					<?
					}
					?>

					</tbody>
				</table>
			</div>

			<div class="paging">
			<?
			if(count($PageLs->mData)) {
				$pUrl=$_SERVER["PHP_SELF"].$CommLink;
				$PageLs->AdminPageList($pUrl);
			}     
			?>
			</div>

	  </div>
	</div>
</form>

<? include "../common/footer.php" ?>