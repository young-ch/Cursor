<?php
// error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
error_reporting(0);
ini_set("display_errors", 0);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
//header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
header('Access-Control-Allow-Origin: *'); // 크로스 도메인 허용
//header("Content-Type: text/css; charset=euc-kr"); 

session_start();

// ################################################################################################################
// 프로젝트 아이디.
$prjId = 'SUCTI';
// ################################################################################################################

/*
//upload 가능한 파일 설정
define('UPLOADABLE_IMG', 'jpg, jpeg, gif, png');
define('UPLOADABLE_FILE', 'jpg, jpeg, gif,doc,xls,hwp,zip,txt,pdf,avi,mpg, mov, wmv, asf, mp3');
*/

//echo $_SERVER['DOCUMENT_ROOT'];

// ################################################################################################################
// 업로드 파일 경로 지정
define('UPLOAD_DIR', "D:\\\\webapp\\sua.saemaul.or.kr\\www\\upfiles\\");

// ################################################################################################################
// 암호화 key
define('ENCRYPT_KEY', "ctthe");

// ################################################################################################################
// 사용자 비밀번호 초기화번호y
define('USER_DEFAULT_PWD', "smu1234!1");

DEFINE('DS', DIRECTORY_SEPARATOR); 

// ################################################################################################################
// CRM I/F 경로
//define('CRM_CUSTOMER_IF_URL', "https://www.apt365.kr/server/if/customers");
//define('CRM_WEBLOG_IF_URL', "https://www.apt365.kr/server/if/outputs");

//define('CRM_CUSTOMER_IF_URL', "http://idcdev.apt365.kr:8080/server/if/customers");
//define('CRM_WEBLOG_IF_URL', "http://idcdev.apt365.kr:8080/server/if/outputs");

// 업로드 파일 경로 지정
$SITE_NAME="새마을운동중앙연수원"; 
$BROWSER_TITLE="새마을운동중앙연수원"; 
$SYSTEM_EMAIL="webmaster@saemaul.or.kr"; 
//$SYSTEM_EMAIL="help@nex-media.co.kr"; 
//$SITE_URL='http://'.$_SERVER['HTTP_HOST']; 

$SITE_URL='http://sua.saemaul.or.kr';
$SITE_URL_MOBILE='http://sua.saemaul.or.kr';

$SITE_URL_SSL='http://sua.saemaul.or.kr';
$SITE_URL_MOBILE_SSL='http://sua.saemaul.or.kr';

$NEAR_URL='http://near.nexsmartmedia.co.kr/';

$ADMIN_SITE_URL='http://sua.saemaul.or.kr';
$ADMIN_SITE_URL_SSL='http://sua.saemaul.or.kr';
$SITE_IMG_SVRVER_URL = $SITE_URL. '/upfiles';

// ################################################################################################################
// HTTPS 되돌리기.
/*
if(!isset($_SERVER["HTTPS"])) {  
	echo "<meta http-equiv=\"refresh\" content=\"0;url=".$SITE_URL_SSL."/\">";	
	exit;	
}
*/
// ################################################################################################################

//세션에서 사용될 상수값 정의
//관리자
define('SS_ADM_ID' , 'SS_ADM_ID');
define('SS_ADM_NM', 'SS_ADM_NM');
define('SS_ADM_GBN' , 'SS_ADM_GBN');
define('SS_ADM_IP' , 'SS_ADM_IP');
define('ADM_SS_ID' , 'ADM_SS_ID');
define('SS_ADM_SEQ' , 'SS_ADM_SEQ');


// ################################################################################################################
// 접근 페이지 확인.
// 관리자페이지 접근인경우 http 이면 https 로 리턴시킨다.
function isAdminPageUrlCk($scriptFileName){
	$arry_admin_page = array("/admin/main/");
	for($i=0; $i<count($arry_admin_page); $i++){
		//echo strtolower($scriptFileName) . ':' . strtolower($arry_admin_page[$i]);
		if (strpos(strtolower($scriptFileName), strtolower($arry_admin_page[$i]))) {
			return true;
			break;
		}
	}
	return false;
}
/*
if (isAdminPageUrlCk($_SERVER['SCRIPT_FILENAME']) == true) {
	if(!isset($_SERVER["HTTPS"])) {  
		echo "<meta http-equiv=\"refresh\" content=\"0;url=".$ADMIN_SITE_URL_SSL."/admin/main/login.php\">";	
		exit;	
	}
}
*/
// ################################################################################################################

if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

// 짧은 환경변수를 지원하지 않는다면
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

    if (!isset($_SESSION))
		$_SESSION = &$HTTP_SESSION_VARS;
}

// ################################################################################################################
// PC 에서 모바일로 접근하는경우 PC 로 이동.
// 모바일에서 PC로 접근하는경우 모바일 이동.

$IS_TEST_SITE = false;  // 테스트사이트 여부. true : 테스트 false : 운영
/*
function isMobile(){
    $ary_m = array("iPhone","iPod","IPad","Android","Blackberry","SymbianOS|SCH-M\d+","Opera Mini","Windows CE","Nokia","Sony","Samsung","LGTelecom","SKT","Mobile","Phone");
    for($i=0; $i<count($ary_m); $i++){
        if(preg_match("/$ary_m[$i]/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            //return $ary_m[$i];
			return true;
            break;
        }
    }
    return false;
}

if (isMobile()==true){  // 모바일 기기면서 모바일웹사이트가 아니면.. 모바일로 이동.
	if ($IS_TEST_SITE == true) {  // 테스트사이트는 예외.
	}
	else{
		if (strpos($_SERVER['HTTP_HOST'], 'm.')===false){
			echo "<meta http-equiv='refresh' content='0;url=".$SITE_URL_MOBILE."'>";	
			exit;		
		}
	}
}
else{  // PC인경우 도메인앞에 www 가없을시 다시 이동.
	if ($IS_TEST_SITE == true) {  // 테스트사이트는 예외.
	}
	else{
		if (strpos($_SERVER['HTTP_HOST'], 'www.')===false){
			echo "<meta http-equiv='refresh' content='0;url=".$SITE_URL."'>";	
			exit;		
		}		
	}
}
*/
// ################################################################################################################


// php.ini 의 magic_quotes_gpc 값이 FALSE 인 경우 addslashes() 적용
// SQL Injection 등으로 부터 보호
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($_GET) )
	{
		while( list($k, $v) = each($_GET) )
		{
			if( is_array($_GET[$k]) )
			{
				while( list($k2, $v2) = each($_GET[$k]) )
				{
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}
			else
			{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if( is_array($_POST) )
	{
		while( list($k, $v) = each($_POST) )
		{
			if( is_array($_POST[$k]) )
			{
				while( list($k2, $v2) = each($_POST[$k]) )
				{
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}
			else
			{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if( is_array($_COOKIE) )
	{
		while( list($k, $v) = each($_COOKIE) )
		{
			if( is_array($_COOKIE[$k]) )
			{
				while( list($k2, $v2) = each($_COOKIE[$k]) )
				{
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}
			else
			{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER); 


$key_time  = time() ;
$todaytime = date("Y.m.d H:i:s",time());
$today     = date("Ymd",time());
$today1    = date("Y년 m월 d일",time());
$today2    = date("Y-m-d",time());
$todaycart = date("YmdHis",time()) ;
$todayauc  = date("mdHis",time()) ;
$estimate_today     = date("Ymd",time()) ;
$one_time           = 86400 ;
$cart_garbage_today = date("Y.m.d", time()-864000 ) ; //카트 삭제시간
$one_time_cart      = 86400 * 3 ;

$time_ma  = date("H") ;
$work_ma  = date("w") ;
$year_ma  = date("Y") ;
$month_ma = date("m") ;
$date_ma  = date("d") ;

//기본 파일용량
$MAX_UPLOAD_SIZE = 1024 * 1024 * 200;  // 200MB
//echo $MAX_UPLOAD_SIZE;

$site['use_guide_phone'] = '031-780-7815';
$site['use_guide_hp']    = '010-5314-7801';

// this is it
