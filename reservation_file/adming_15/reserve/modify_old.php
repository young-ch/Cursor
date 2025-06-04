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
require_once("../../include/common/page_authority.php");
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
$content = $Result[0]->content;

$gConn->DisConnect();

$CommLink="?searchValue=$searchValue&page=$page&listSize=$listSize&startDate=$startDate&endDate=$endDate";
//echo $Result[0]->place;
?>

<? include "../common/topmenu.php" ?>

<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/datepicker-ko-KR.js" type="text/javascript"></script>

	<script language="javascript">
	<!--
	function formCheck(obj)
	{
		if ($('#subject').val()==""){
			alert("제목을 입력하십시요.");
			$('#subject').focus();
			return;
		}    
		if ($('#joinNm').val()==""){
			alert("이름을 입력하십시요.");
			$('#joinNm').focus();
			return;
		}  
		if ($('#passwd').val()==""){
			alert("비밀번호를 입력하십시요.");
			$('#passwd').focus();
			return;
		}
		if ($('#passwd_re').val()==""){
			alert("비밀번호 확인을 입력하십시요.");
			$('#passwd_re').focus();
			return;
		}		
		if ($('#passwd').val()!=$('#passwd_re').val()){
			alert("비밀번호확인이 일치하지 않습니다.");
			$('#passwd_re').focus();
			return;
		}

		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []); 
		
		/*
		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []); 
		if(strTrim(frm.content) == "")
		{
			alert("내용을 입력해주세요");
			frm.ir1.focus();
			return;
		}
		*/
		$('#frmWrite').attr("action","modify_exec.php");
		$('#frmWrite').submit();
	}

	function formReset(obj){
		
		$('form')[0].reset();
	}
	
	function deleteConfirm(){
		if (confirm("삭제하시겠습니까?")){
			$('#frmWrite').attr("action","delete_exec.php");
			$('#frmWrite').submit();
		}
		return;
	}
	
	var file_count = 1;
	function addFile(){
		file_count++;
		$("#fileAttach").append("<br/><input type='file' name='upfile2[]' style='height:35px;'/>");
		
		/*
		//첨부파일 제한
		$('INPUT[type="file"]').filter("[name^='file']").change(function () {
		    var ext = this.value.match(/\.(.+)$/)[1];
		    //alert(ext);
		    if($.inArray(ext, ["txt", "pdf", "ppt", "pptx", "pdf", "jpg", "docx", "doc", "xls", "xlsx", "hwp"]) == -1) {
		        alert('허용된 확장자가 아닙니다.');
		        this.value = '';
		    }
		});
		*/
	}
	//-->
	</script>

	<form name="frmWrite" id="frmWrite" method="post">
	<input type="hidden" name="page" value="<?=$page?>">
	<input type="hidden" name="searchValue" value="<?=$searchValue?>">
	<input type="hidden" name="listSize" value="<?=$listSize?>">
	<input type="hidden" name="startDate" value="<?=$startDate?>">
	<input type="hidden" name="endDate" value="<?=$endDate?>">
	<input type="hidden" name="eduSeq" value="<?=$eduSeq?>">
	<?
	// CSRF 취약점 대응.
	$token = md5(uniqid(rand(),true));
	$_SESSION['TOKEN'] = $token;	
	?>
	<input type="hidden" name="token" value="<?=$token?>">

	<div class="admincontbox">
	  <div class="admincont">
			<h3>온라인예약  수정<?=$Result[0]->company_name?> == </h3>

			<div class="admboard-rapper mt-20">
				<table width="100%" class="adm_boarview">
					<colgroup>
						<col width="20%" />
						<col width="" />
						<col width="" />
					</colgroup>
					<tbody>
					<tr>
						<th scope="row">제목</th>
						<td colspan="4">
							<input type="text" class="textform" style="width:77%;" id="subject" name="subject" value="<?=$Result[0]->subject?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">이름</th>
						<td colspan="4">
							<input type="text" class="textform" style="width:37%;" id="regNm" name="regNm" value="<?=$Result[0]->regNm?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">이메일</th>
						<td colspan="4">
							<input type="text" class="textform" style="width:47%;" id="email" name="email" value="<?=$Result[0]->email?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">비밀번호</th>
						<td>
							<input type="text" class="textform" style="width:37%;" id="passwd" name="passwd" value="<?=$Result[0]->passwd?>"/>
						</td>
						<th scope="row">비밀번호확인</th>
						<td>
							<input type="text" class="textform" style="width:37%;" id="passwd_re" name="passwd_re" value="<?=$Result[0]->passwd?>"/>
						</td>
					</tr>					
					<tr>
						<th scope="row">내용</th>
						<td colspan="4">
							<textarea name="content" id="ir1" rows="10" cols="100" style="width:100%; height:500px; display:none;"><?=nl2br($content);?></textarea>
						</td>
					</tr>
				
					<tr>
						<th scope="row">게시일</th>
						<td colspan="4">
							<input type="text" class="textform" style="width:100px;" name="viewDate" id="viewDate" value="<?=substr($Result[0]->viewDate,0,10)?>"/>
						</td>
					</tr>
					<tr>
						<th scope="row">게시여부</th>
						<td colspan="4">
							<select id="isOpen" name="isOpen" class="selbox">
								<option value="Y" <?if ($Result[0]->isOpen=='Y') { echo 'selected';}?>>오픈</option>
								<option value="N" <?if ($Result[0]->isOpen=='N') { echo 'selected';}?>>닫음</option>
							</select>							
						</td>
					</tr>

					</tbody>
				</table>
			</div>

			<div class="adm_board_btn">
				<a href="javascript:formCheck(this)" class="admbtn_add">저장</a>
				<a href="javascript:formReset(this)" class="admbtn_type02">초기화</a>
				<a href="list.php<?=$CommLink?>" class="admbtn_type03">목록</a>
			</div>

	  </div>
	</div>

	</form>

	<script type="text/javascript" src="../common/smartEditor/js/HuskyEZCreator.js" charset="utf-8"></script>
	<script type="text/javascript">
	<!--
	var oEditors = [];
			nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors,
				elPlaceHolder: "ir1",
				sSkinURI: "../common/smartEditor/SmartEditor2Skin.html",	
				htParams : {bUseToolbar : true,
					fOnBeforeUnload : function(){
							
					}
				}, 
				fCreator: "createSEditor2"
			});	
	//-->
	</script>
	<script>
	$(function() {
		$( "#viewDate" ).datepicker();
	});
	</script>	


<? include "../common/footer.php" ?>