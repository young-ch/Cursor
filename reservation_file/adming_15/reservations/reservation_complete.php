<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("../../include/common/conf.php");
require_once("../../include/common/dbconn.php");
require_once("../../include/common/function.php");
require_once("../../include/dao/class_reserve.php");

$company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
$representative = isset($_POST['representative']) ? $_POST['representative'] : ''; 
$phone = isset($_POST['phone']) ? $_POST['phone'] : ''; 
$address = isset($_POST['address']) ? $_POST['address'] : ''; 
$doc_tax_invoice = isset($_POST['doc_tax_invoice']) ? $_POST['doc_tax_invoice'] : ''; 
$business_number = isset($_POST['business_number']) ? $_POST['business_number'] : '';

$attachment_path = isset($_POST['attachment_path']) ? $_POST['attachment_path'] : ''; 

$usage_accommodation = isset($_POST['usage_accommodation']) ? $_POST['usage_accommodation'] : '0'; 
$usage_hall = isset($_POST['usage_hall']) ? $_POST['usage_hall'] : '0'; 
$usage_facility = isset($_POST['usage_facility']) ? $_POST['usage_facility'] : '0'; 
$use_etc_txt = isset($_POST['use_etc_txt']) ? $_POST['use_etc_txt'] : ''; 

$tax_manager_name = isset($_POST['tax_manager_name']) ? $_POST['tax_manager_name'] : '';
$tax_manager_phone = isset($_POST['tax_manager_phone']) ? $_POST['tax_manager_phone'] : '';
$email_id = isset($_POST['email_id']) ? $_POST['email_id'] : '';
$email_domain = isset($_POST['email_domain']) ? $_POST['email_domain'] : '';
$email = $email_id."@".$email_domain;

$purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
$total_people = isset($_POST['total_people']) ? $_POST['total_people'] : '';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : '0';
$contract_amount = isset($_POST['contract_amount']) ? $_POST['contract_amount'] : '0';
$balance_amount = isset($_POST['balance_amount']) ? $_POST['balance_amount'] : '0';

$room_no_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_1'])));
$room_no_1_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_1_1'])));
$room_no_1t = str_replace(',','',sqlInject(rejectXss($_POST['room_no_1t'])));
$room_no_2 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_2'])));
$room_no_2_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_2_1'])));
$room_no_2t = str_replace(',','',sqlInject(rejectXss($_POST['room_no_2t'])));
$room_no_3 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_3'])));
$room_no_3_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_3_1'])));
$room_no_3t = str_replace(',','',sqlInject(rejectXss($_POST['room_no_3t'])));
$room_no_4 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_4'])));
$room_no_4_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_4_1'])));
$room_no_4t = str_replace(',','',sqlInject(rejectXss($_POST['room_no_4t'])));
$room_no_5 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_5'])));
$room_no_5_1 = str_replace(',','',sqlInject(rejectXss($_POST['room_no_5_1'])));
$room_no_5t = str_replace(',','',sqlInject(rejectXss($_POST['room_no_5t'])));

$lec_no_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_1'])));
$lec_no_1_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_1_1'])));
$lec_no_1t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_1t'])));
$lec_no_2 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_2'])));
$lec_no_2_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_2_1'])));
$lec_no_2t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_2t'])));
$lec_no_3 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_3'])));
$lec_no_3_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_3_1'])));
$lec_no_3t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_3t'])));
$lec_no_4 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_4'])));
$lec_no_4_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_4_1'])));
$lec_no_4t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_4t'])));
$lec_no_5 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_5'])));
$lec_no_5_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_5_1'])));
$lec_no_5t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_5t'])));
$lec_no_6 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_6'])));
$lec_no_6_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_6_1'])));
$lec_no_6t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_6t'])));
$lec_no_7 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_7'])));
$lec_no_7_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_7_1'])));
$lec_no_7t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_7t'])));
$lec_no_8 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_8'])));
$lec_no_8_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_8_1'])));
$lec_no_8t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_8t'])));
$lec_no_9 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_9'])));
$lec_no_9_1 = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_9_1'])));
$lec_no_9t = str_replace(',','',sqlInject(rejectXss($_POST['lec_no_9t'])));

$hansik_type = str_replace(',','',sqlInject(rejectXss($_POST['hansik_type'])));
$hansik_count = str_replace(',','',sqlInject(rejectXss($_POST['hansik_count'])));
$hansik_not = str_replace(',','',sqlInject(rejectXss($_POST['hansik_not'])));
$eat_no_1 = str_replace(',','',sqlInject(rejectXss($_POST['eat_no_1'])));
$eat_no_1_1 = str_replace(',','',sqlInject(rejectXss($_POST['eat_no_1_1'])));
$eat_no_1t = str_replace(',','',sqlInject(rejectXss($_POST['eat_no_1t'])));
$classroom_type = str_replace(',','',sqlInject(rejectXss($_POST['classroom_type'])));
$classroom_type_count = str_replace(',','',sqlInject(rejectXss($_POST['classroom_type_count'])));
$ground_not = str_replace(',','',sqlInject(rejectXss($_POST['ground_not'])));

$facility_name = "";
$room_no_1 = isset($room_no_1) ? $room_no_1 : '0';
$facility_name .= $room_no_1;
$room_no_1_1 = isset($room_no_1_1) ? $room_no_1_1 : '';
$facility_name .= "==".$room_no_1_1;
$room_no_1t = isset($room_no_1t) ? $room_no_1t : '';
$facility_name .= "==".$room_no_1t;

$room_no_2 = isset($room_no_2) ? $room_no_2 : '0';
$facility_name .= "|||".$room_no_2;
$room_no_2_1 = isset($room_no_2_1) ? $room_no_2_1 : '';
$facility_name .= "==".$room_no_2_1;
$room_no_2t = isset($room_no_2t) ? $room_no_2t : '';
$facility_name .= "==".$room_no_2t;

$room_no_3 = isset($room_no_3) ? $room_no_3 : '0';
$facility_name .= "|||".$room_no_3;
$room_no_3_1 = isset($room_no_3_1) ? $room_no_3_1 : '';
$facility_name .= "==".$room_no_3_1;
$room_no_3t = isset($room_no_3t) ? $room_no_3t : '';
$facility_name .= "==".$room_no_3t;

$room_no_4 = isset($room_no_4) ? $room_no_4 : '0';
$facility_name .= "|||".$room_no_4;
$room_no_4_1 = isset($room_no_4_1) ? $room_no_4_1 : '';
$facility_name .= "==".$room_no_4_1;
$room_no_4t = isset($room_no_4t) ? $room_no_4t : '';
$facility_name .= "==".$room_no_4t;

$room_no_5 = isset($room_no_5) ? $room_no_5 : '0';
$facility_name .= "|||".$room_no_5;
$room_no_5_1 = isset($room_no_5_1) ? $room_no_5_1 : '';
$facility_name .= "==".$room_no_5_1;
$room_no_5t = isset($room_no_5t) ? $room_no_5t : '';
$facility_name .= "==".$room_no_5t;
$facility_name .= "///";
$lec_no_1 = isset($lec_no_1) ? $lec_no_1 : '0';
$facility_name .= $lec_no_1;
$lec_no_1_1 = isset($lec_no_1_1) ? $lec_no_1_1 : '';
$facility_name .= "==".$lec_no_1_1;
$lec_no_1t = isset($lec_no_1t) ? $lec_no_1t : '';
$facility_name .= "==".$lec_no_1t;

$lec_no_2 = isset($lec_no_2) ? $lec_no_2 : '0';
$facility_name .= "|||".$lec_no_2;
$lec_no_2_1 = isset($lec_no_2_1) ? $lec_no_2_1 : '';
$facility_name .= "==".$lec_no_2_1;
$lec_no_2t = isset($lec_no_2t) ? $lec_no_2t : '';
$facility_name .= "==".$lec_no_2t;

$lec_no_3 = isset($lec_no_3) ? $lec_no_3 : '0';
$facility_name .= "|||".$lec_no_3;
$lec_no_3_1 = isset($lec_no_3_1) ? $lec_no_3_1 : '';
$facility_name .= "==".$lec_no_3_1;
$lec_no_3t = isset($lec_no_3t) ? $lec_no_3t : '';
$facility_name .= "==".$lec_no_3t;

$lec_no_4 = isset($lec_no_4) ? $lec_no_4 : '0';
$facility_name .= "|||".$lec_no_4;
$lec_no_4_1 = isset($lec_no_4_1) ? $lec_no_4_1 : '';
$facility_name .= "==".$lec_no_4_1;
$lec_no_4t = isset($lec_no_4t) ? $lec_no_4t : '';
$facility_name .= "==".$lec_no_4t;

$lec_no_5 = isset($lec_no_5) ? $lec_no_5 : '0';
$facility_name .= "|||".$lec_no_5;
$lec_no_5_1 = isset($lec_no_5_1) ? $lec_no_5_1 : '';
$facility_name .= "==".$lec_no_5_1;
$lec_no_5t = isset($lec_no_5t) ? $lec_no_5t : '';
$facility_name .= "==".$lec_no_5t;
$facility_name .= "///";
$lec_no_6 = isset($lec_no_6) ? $lec_no_6 : '0';
$facility_name .= $lec_no_6;
$lec_no_6_1 = isset($lec_no_6_1) ? $lec_no_6_1 : '';
$facility_name .= "==".$lec_no_6_1;
$lec_no_6t = isset($lec_no_6t) ? $lec_no_6t : '';
$facility_name .= "==".$lec_no_6t;

$lec_no_7 = isset($lec_no_7) ? $lec_no_7 : '0';
$facility_name .= "|||".$lec_no_7;
$lec_no_7_1 = isset($lec_no_7_1) ? $lec_no_7_1 : '';
$facility_name .= "==".$lec_no_7_1;
$lec_no_7t = isset($lec_no_7t) ? $lec_no_7t : '';
$facility_name .= "==".$lec_no_7t;

$lec_no_8 = isset($lec_no_8) ? $lec_no_8 : '0';
$facility_name .= "|||".$lec_no_8;
$lec_no_8_1 = isset($lec_no_8_1) ? $lec_no_8_1 : '';
$facility_name .= "==".$lec_no_8_1;
$lec_no_8t = isset($lec_no_8t) ? $lec_no_8t : '';
$facility_name .= "==".$lec_no_8t;

$lec_no_9 = isset($lec_no_9) ? $lec_no_9 : '0';
$facility_name .= "|||".$lec_no_9;
$lec_no_9_1 = isset($lec_no_9_1) ? $lec_no_9_1 : '';
$facility_name .= "==".$lec_no_9_1;
$lec_no_9t = isset($lec_no_9t) ? $lec_no_9t : '';
$facility_name .= "==".$lec_no_9t;
$facility_name .= "///";
$hansik_type = isset($hansik_type) ? $hansik_type : '';
$facility_name .= $hansik_type;
$hansik_count = isset($hansik_count) ? $hansik_count : '';
$facility_name .= "==".$hansik_count;
$hansik_not = isset($hansik_not) ? $hansik_not : '';
$facility_name .= "==".$hansik_not;
$eat_no_1 = isset($eat_no_1) ? $eat_no_1 : '0';
$facility_name .= "|||".$eat_no_1;
$eat_no_1_1 = isset($eat_no_1_1) ? $eat_no_1_1 : '';
$facility_name .= "==".$eat_no_1_1;
$eat_no_1t = isset($eat_no_1t) ? $eat_no_1t : '';
$facility_name .= "==".$eat_no_1t;
$classroom_type = isset($classroom_type) ? $classroom_type : '';
$facility_name .= "|||".$classroom_type;
$classroom_type_count = isset($classroom_type_count) ? $classroom_type_count : '0';
$facility_name .= "==".$classroom_type_count;
$ground_not = isset($ground_not) ? $ground_not : '';
$facility_name .= "==".$ground_not;

$company_name = sqlInject(rejectXss($company_name));
$representative = sqlInject(rejectXss($representative));
$phone = sqlInject(rejectXss($phone));
$address = sqlInject(rejectXss($address));
$doc_tax_invoice = sqlInject(rejectXss($doc_tax_invoice));

$business_number = sqlInject(rejectXss($business_number));
$usage_accommodation = sqlInject(rejectXss($usage_accommodation));
$usage_hall = sqlInject(rejectXss($usage_hall));
$usage_facility = sqlInject(rejectXss($usage_facility));
$use_etc_txt = sqlInject(rejectXss($use_etc_txt));
$tax_manager_name = sqlInject(rejectXss($tax_manager_name));
$tax_manager_phone = sqlInject(rejectXss($tax_manager_phone));
$email = sqlInject(rejectXss($email));

$purpose = sqlInject(rejectXss($purpose));
$total_people = sqlInject(rejectXss($total_people));
$start_date = sqlInject(rejectXss($start_date));
$end_date = sqlInject(rejectXss($end_date));
$total_amount = str_replace(',','',sqlInject(rejectXss($total_amount)));
$contract_amount = str_replace(',','',sqlInject(rejectXss($contract_amount)));
$balance_amount = str_replace(',','',sqlInject(rejectXss($balance_amount)));
$facility_name = sqlInject(rejectXss($facility_name));
$status = "pending";

$file_org_name = "";
$file_svc_name = "";

if (is_uploaded_file($_FILES["attachment"]["tmp_name"]))  
{
	$filePath = UPLOAD_DIR.DS."reserve".DS;
	$result_file=fileUp($_FILES["attachment"]["tmp_name"], $_FILES["attachment"]["name"], $MAX_UPLOAD_SIZE, $filePath);

	$file_org_name = $result_file[0];
	$file_svc_name = $result_file[1];
}

$gConn = new DBConn();

$obj = new ReserveSql("reservations");

$obj->company_name=$company_name;
$obj->representative=$representative;
$obj->phone=$phone ;
$obj->address=$address ;
$obj->business_number=$business_number ;
$obj->file_org_name=$file_org_name ;
$obj->file_svc_name=$file_svc_name ;
$obj->doc_tax_invoice=$doc_tax_invoice ;
$obj->usage_accommodation=$usage_accommodation;
$obj->usage_hall=$usage_hall;
$obj->usage_facility=$usage_facility;
$obj->usage_etc=$use_etc_txt;
$obj->tax_manager_name=$tax_manager_name;
$obj->tax_manager_phone=$tax_manager_phone;
$obj->tax_manager_email=$email;
$obj->purpose=$purpose;
$obj->total_people=$total_people;
$obj->start_date=$start_date;
$obj->end_date=$end_date;
$obj->total_amount=$total_amount;
$obj->contract_amount=$contract_amount;
$obj->balance_amount=$balance_amount;
$obj->status=$status;
$obj->facility=$facility_name;

//****************************************************
// 저장
//****************************************************
$obj->Insert($gConn->mConn ) ;
//****************************************************
// 저장 종료
//****************************************************
 
$gConn->DisConnect();


$pUrl = "/reservations/reservation.php";
echo "<script>alert('예약신청이 접수되었습니다.');</script>";
echo "<meta http-equiv='Refresh' content='0;URL=".$pUrl."'>";
?>