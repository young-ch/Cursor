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
require_once("../../include/common/page_authority.php");
?>
<?
if (!(isset($_SESSION['TOKEN']) && $_POST['token'] == $_SESSION['TOKEN'])){
	echo " 비정상적인 접근 ";
	exit;
}
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
$obj->id=$id;
$obj->DeleteExec($gConn->mConn ) ;
$gConn->DisConnect();

$pHtmlLink="list.php";
$CommLink="?searchValue=$searchValue&page=$page&listSize=$listSize&startDate=$startDate&endDate=$endDate";
$pUrl=$pHtmlLink.$CommLink;

//echo "<meta http-equiv='Refresh' content='0;URL=".$pUrl."'>";

alertMsgUrl("삭제되었습니다.", "$pUrl");
?>