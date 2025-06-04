<?php	
	Header("Content-type: text/html; charset=utf-8");
	
	error_reporting(E_ALL);
ini_set("display_errors", 1);
	require_once("../../include/common/conf.php");
	require_once("../../include/common/dbconn.php");
	require_once("../../include/common/function.php");
	require_once("../../include/dao/board_file.php");

	$orgName = isset($_REQUEST['orgName']) ? $_REQUEST['orgName'] : '';			//일련번호
	$svcName = isset($_REQUEST['svcName']) ? $_REQUEST['svcName'] : '';
	
	$orgName = sqlInject(rejectXss($orgName));
	$svcName = sqlInject(rejectXss($svcName));

		
	if(is_empty($svcName)||is_empty($orgName)) {
		exit;
	}

	// 다운로드 접근 페이지 확인. (허용된 페이지에서만 다운로드 실행되도록.)
	function isPageAuth($serverRequestUri){
		$ary_page = array("/admin/free_board/read.php","/admin/notice/read.php","/admin/bbs/read.php","/sub/information/speech_list.php"
		,"/sub/information/ci_list.php","/sub/information/publication_view.php","/sub/information/speech_view.php","/sub/information/ci_view.php",
		"/sub/support/notice_view.php","/sub/support/board_view.php","/eng/sub/publications/news_list.php","news_view.php","publication_view.php",
			"/sub/introduce/related_group_Y-SMU_speech_list.php", "related_group_Y-SMU_speech_view.php");

		for($i=0; $i<count($ary_page); $i++){
			if (strpos(strtolower($serverRequestUri), strtolower($ary_page[$i]))) {
			//	echo $ary_page[$i] . ":::::::::" . $serverRequestUri . "<br /><br />";
				return true;
				break;
			}
				
		}
		return false;
	}
	/*
	if (isPageAuth(getenv("HTTP_REFERER")) == false) {
		echo "page 인증실패.(".getenv("HTTP_REFERER").")";
		exit;	
	}
	*/


	$filePath = UPLOAD_DIR.DS."reserve".DS;

	if (!file_exists($filePath.$svcName)) {
		echo $filePath.$svcName ; 
		echo "<br />";
		echo "파일이 존재하지 않습니다.";
		exit;
	}
	
	//echo $filePath.$svcName ; 
		//exit;
		
	//확장자 검사
	$ext_exp=explode(".",$svcName);
	$ext_cnt=count($ext_exp)-1;
	$file_ext=strtolower($ext_exp[$ext_cnt]);
	
	// 파일 확장자 비교 검색.
	if (fileDownLoadCk($file_ext)=="N")
	{
		echo "<br />";
		echo "다운로드가 불가능한 파일입니다.";
		exit;
	}

	send_attachment($orgName, $filePath.$svcName);
	?>