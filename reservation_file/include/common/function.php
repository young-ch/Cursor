<?php
	// 문자열 자르기
	function strSplit($src, $n=100) 
	{ 
		if(strlen($src) <= $n) 
		{ 
			return $src; 
		} else {
			$tmp = substr($src, 0, $n); 
			preg_match('/^([\x00-\x7e]|.{2})*/', $tmp, $res); 

			return $res[0]."..."; 
		} 
	} // end function

// 문자열 자르기.
	function sub_string($string,$start,$length,$charset=NULL) {     
		if($charset==NULL) {
			$charset='UTF-8';
		}
		/* 정확한 문자열의 길이를 계산하기 위해, mb_strlen 함수를 이용 */
		$str_len=mb_strlen($string,'UTF-8'); 
		if($str_len>$length) {   
			/* mb_substr  PHP 4.0 이상, iconv_substr PHP 5.0 이상 */
			$string=mb_substr($string,$start,$length,'UTF-8');   
			$string.="..";
		}         
		return $string;             
	}


	// Html코드 복구하기
	function strHtmlRe($src) 
	{ 
		//$src=str_replace("&amp;","&",$src);
		$src=str_replace("&quot;","\"",$src);
		$src=str_replace("&lt;","<",$src);
		$src=str_replace("&gt;",">",$src);

		return $src;

	} // end function
	

	// Html코드 변환하기
	function strHtml($src) 
	{ 
		$src=str_replace("\"","&quot;",$src);
		$src=str_replace("<","&lt;",$src);
		$src=str_replace(">","&gt;",$src);

		return $src;

	} // end function

	// scriptHtml코드 변환하기
	function scriptHtml($src) 
	{ 
        $pSrcLow=strtolower($src);
        if(strpos("##".$pSrcLow,"<script") || strpos("##".$pSrcLow,"<</script")) { //스크립트 함수가 있으면
    		$src=str_replace("<script","#script",strtolower($src));
    		$src=str_replace("</script","#/script",strtolower($src));				
        }    

		return $src;

	} // end function


	function excelUp($upfile, $upfile_name, $dirpath) 
	{
     		move_uploaded_file($upfile,$dirpath.$upfile_name);
            chmod( $dirpath.$upfile_name, 0777 );	
	}

		// 파일 업로드 하기
	function fileUp($upfile,  $upfile_name, $upfileSize, $dirpath) 
	{
		$ext_exp=explode(".",$upfile_name);
		$ext_cnt=count($ext_exp)-1;
		$file_ext=strtolower($ext_exp[$ext_cnt]);
		
		$upfile_result = array(); // 0 : 원본명 / 1 : 서비스명 / 2 : 용량 / 3 : 썸네일

		if($file_ext == 'html' || $file_ext == 'htm' || $file_ext == 'php' || $file_ext == 'php3' || $file_ext == 'exe' 
				|| $file_ext == 'cmd' || $file_ext == 'pl' || $file_ext == 'dll' || $file_ext == 'ph' ||	$file_ext == 'inc'  ||	$file_ext == 'asp'  ||	$file_ext == 'do'){ 
			echo "
			<Script>
			alert('등록 불가능한 파일입니다.\\n다른 파일을 등록하십시오.');
			history.back();
			</Script>
			";
			exit;
		} 
		else { //첨부가능 파일인 경우
			$upfile_name= str_replace(" ","",$upfile_name);          
			$upfile_rename= $upfile_name;    
			$upfile_rename = uniqid().".".$file_ext;
			
			$att_size = filesize($upfile);

			//파일 크기 제한

			if ($upfileSize<$att_size) { 
				echo "
				<Script>
				alert('파일 업로드는 최고 ".$upfileSize."Byte 까지 가능합니다');
				history.back();
				</Script>
				";
				exit;
			} 

			$j = 0;
			while(1) {
				$j++;
				if(!file_exists("$dirpath.$upfile_rename")) break;
				else $upfile_rename = "[$j]".$upfile_name;
			}

			move_uploaded_file($upfile, $dirpath.$upfile_rename);
			//chmod( $dirpath.$upfile_rename, 0777 );		

			$upfile_result[0] = $upfile_name; // 원본명
			$upfile_result[1] = $upfile_rename; // 서비스명
			$upfile_result[2] = $att_size; // 용량
			$upfile_result[3] = "";

			//return $upfile_rename;
			return $upfile_result;
		}
	}

/*
	// 파일 업로드 하기
	function fileUserUp($upfile, $upfile_name, $dirpath) 
	{
		$ext_exp=explode(".",$upfile_name);
		$ext_cnt=count($ext_exp)-1;
		$file_ext=strtolower($ext_exp[$ext_cnt]);
		
		$upfile_result = array(); // 0 : 원본명 / 1 : 서비스명 / 2 : 용량 / 3 : 썸네일

		if($file_ext == 'html' || $file_ext == 'htm' || $file_ext == 'php' || $file_ext == 'php3' || $file_ext == 'exe' 
				|| $file_ext == 'cmd' || $file_ext == 'pl' || $file_ext == 'dll' || $file_ext == 'ph' ||	$file_ext == 'inc'  ||	$file_ext == 'asp'  ||	$file_ext == 'do'){ 
			echo "
			<Script>
			alert('등록 불가능한 파일입니다.\\n다른 파일을 등록하십시오.');
			history.back();
			</Script>

			";
			exit;
		} 
		else { //첨부가능 파일인 경우
			$upfile_name= str_replace(" ","",$upfile_name);          
			$upfile_rename= $upfile_name;    
			$upfile_rename = uniqid().".".$file_ext;

			$j = 0;
			while(1) {
				$j++;
				if(!file_exists("$dirpath.$upfile_rename")) break;
				else $upfile_rename = "[$j]".$upfile_name;
			}

			move_uploaded_file($upfile, $dirpath.$upfile_rename);
			//chmod( $dirpath.$upfile_rename, 0777 );		

			$upfile_result[0] = $upfile_name; // 원본명
			$upfile_result[1] = $upfile_rename; // 서비스명
			$upfile_result[2] = filesize($dirpath.$upfile_rename); // 용량
			$upfile_result[3] = "";

			//return $upfile_rename;
			return $upfile_result;
		}
	}
*/
	// 이미지 업로드 하기
	function imgUp($upfile, $upfile_name, $dirpath, $thumb_width, $thumb_height)
	{
		$MAX_UPLOAD_SIZE = 1024 * 1024 * 10;  // 첨부가능용량 10MB

		$ext_exp=explode(".",$upfile_name);
		$ext_cnt=count($ext_exp)-1;
		$file_ext=strtolower($ext_exp[$ext_cnt]);


		$upfile_result = array(); // 0 : 원본명 / 1 : 서비스명 / 2 : 용량 / 3 : 썸네일

		if($file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'bmp' || $file_ext == 'gif' || $file_ext == 'png'){

			$upfile_name= str_replace(" ","",$upfile_name);
			$upfile_rename= $upfile_name;
			$upfile_rename = uniqid().".".$file_ext;

			$att_size = filesize($upfile);

			//파일 크기 제한

			if ($MAX_UPLOAD_SIZE<$att_size) {
				echo "
				<Script>
				alert('파일 업로드는 최고 ".$MAX_UPLOAD_SIZE."Byte 까지 가능합니다');
				history.back();
				</Script>
				";
				exit;
			}

			move_uploaded_file($upfile, $dirpath.$upfile_rename);
			//chmod( $dirpath.$upfile_rename, 0777 );

			$upfile_result[0] = $upfile_name; // 원본명
			$upfile_result[1] = $upfile_rename; // 서비스명
			$upfile_result[2] = $att_size; // 용량
			$upfile_result[3] = "";

			$j = 0;
			while(1) {
				$j++;
				if(!file_exists("$dirpath.$upfile_rename")) break;
				else $upfile_rename = "[$j]".$upfile_name;
			}
			/*
			// 이미지 회전시작.
			$type = $file_ext;
			@ini_set('gd.jpeg_ignore_warning', 1);
			if($type == "jpg" || $type == "jpeg"){
			  $upfile = @imagecreatefromjpeg($dirpath.$upfile_rename);
			}else if($type == "png"){
			  $upfile = imagecreatefrompng($dirpath.$upfile_rename);
			}else if($type == "bmp" || $type == "wbmp"){
			  $upfile = imagecreatefromwbmp($dirpath.$upfile_rename);
			}else if($type == "gif"){
			  $upfile = imagecreatefromgif($dirpath.$upfile_rename);
			}
			$exif = exif_read_data($upfile);
			if(!empty($exif['Orientation'])) {
			  switch($exif['Orientation']) {
					case 8:
						 $upfile = imagerotate($upfile,90,0);
						 break;
					case 3:
						 $upfile = imagerotate($upfile,180,0);
						 break;
					case 6:
						 $upfile = imagerotate($upfile,-90,0);
						 break;
			  }
			  if($ext == "jpg" || $ext == "jpeg"){
					imagejpeg($upfile,$dirpath.$upfile_rename);
			  }else if($ext == "png"){
					imagepng($upfile,$dirpath.$upfile_rename);
			  }else if($ext == "bmp" || $ext == "wbmp"){
					imagewbmp($upfile,$dirpath.$upfile_rename);
			  }else if($ext == "gif"){
					imagegif($upfile,$dirpath.$upfile_rename);
			  }
			}
			// 이미지 회전종료.
			*/
			move_uploaded_file($upfile, $dirpath.$upfile_rename) ; //or die();

			//echo($dirpath.$upfile_rename);
			//chmod( $dirpath.$upfile_rename, 0777 );

			//$upload[$i][source] = $filename;
			//$upload[$i][filesize] = $filesize;

			$upfile_result[0] = $upfile_name; // 원본명
			$upfile_result[1] = $upfile_rename; // 서비스명
			$upfile_result[2] = $att_size; // 용량
			//$upfile_result[3] = ""; // 썸네일
			//$thumnailImgSize = 120;
			//$upfile_result[2] = filesize($upfile); // 용량
			//$upfile_result[3] = thumnailImg($dirpath.$upfile_rename, $dirpath."thumb_".$upfile_rename, $thumnailImgSize, $thumnailImgSize); // 썸네일

			// 이미지 썸네일
			$param1 = array(
				'o_path' => $dirpath.$upfile_rename, $dirpath, 'n_path' => $dirpath."thumb_".$upfile_rename,
				'mode' => 'ratio', 'width' => $thumb_width, 'height' => $thumb_height
			);
			$makeThumb = getThumb($param1);
			$upfile_result[3] = "thumb_" .  $upfile_rename ;


			//return $upfile_rename;
			return $upfile_result;
		}
		else { //이미지가 아닌경우

			echo "
				<Script>
				alert('이미지 파일이 아닙니다.\\n이미지 파일만 등록하실 수 있습니다.');
				history.back();
				</Script>
			";
			exit;
		}
	}

	/*
	* 첨부파일 다운로드 체크.
	*/
	function fileDownLoadCk($tmp)
	{
		echo $tmp;
		if ($tmp=="txt"||$tmp=="doc"||$tmp=="docx"||$tmp=="xls"||$tmp=="xlsx"||$tmp=="hwp"||$tmp=="zip"||$tmp=="jpg"||$tmp=="png"||$tmp=="bmp"||$tmp=="gif"||$tmp=="jpeg"||$tmp=="pdf"||$tmp=="pptx"||$tmp=="dwg"||$tmp=="ppt"||$tmp=="wmv"||$tmp=="mp3"||$tmp=="ai"||$tmp=="mp4"||$tmp=="wmv")
			return "Y";
		else
			return "N" ; 
	}


	//jpg 확장자 확인하기
	function jpgCheck($upfile_name) 
	{
		$ext_exp=explode(".",$upfile_name);
		$ext_cnt=count($ext_exp)-1;
		$file_ext=strtolower($ext_exp[$ext_cnt]);

		if($file_ext != 'jpg' && $file_ext != 'jpeg' && $file_ext != 'bmp' && $file_ext != 'gif' && $file_ext != 'png'){ 
		  echo "
		  <Script>
				  alert('이미지 파일만 등록하실 수 있습니다.');
				  history.back();
		  </Script>
		  ";
		  exit;
		}else{
               $rand=rand(1000,9999);
               $upfile_name=date("Ymd")."-".$rand.".".$file_ext;
				return $upfile_name;
          }
	}

/*
	// 배경크기고정, 이미지 비율로 축소
	function thumnail($file, $save_filename, $max_width, $max_height)
	{

       $src_img = ImageCreateFromJPEG($file);		

       $img_info = getImageSize($file);
       $img_width = $img_info[0];
       $img_height = $img_info[1];

       if($img_width > $max_width || $img_height > $max_height)
       {
              if($img_width == $img_height) {
                     $dst_width = $max_width;
                     $dst_height = $max_height;
              }elseif($img_width > $img_height){
                     $dst_width = $max_width;
                     $dst_height = ceil(($max_width / $img_width) * $img_height);
              }else{
                     $dst_height = $max_height;
                     $dst_width = ceil(($max_height / $img_height) * $img_width);
              }
               if($dst_height > $max_height) {
                     $dst_height = $max_height;
                     $dst_width = ceil(($max_height / $img_height) * $img_width);
               }     

       }else{
              $dst_width = $img_width;
              $dst_height = $img_height;

              //$max_width = $img_width; //이미지가 작은경우 이미지크기로
              //$max_height = $img_height; //이미지가 작은경우 이미지크기로             
       }

		if($dst_width < $max_width) $srcx = ceil(($max_width - $dst_width)/2); else $srcx = 0;
		if($dst_height < $max_height) $srcy = ceil(($max_height - $dst_height)/2); else $srcy = 0;

		if($img_info[2] == 1) $dst_img = imagecreate($max_width, $max_height);
		else $dst_img = imagecreatetruecolor($max_width, $max_height);

		$bgc = ImageColorAllocate($dst_img, 255, 255, 255);
		ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc); 
		ImageCopyResampled($dst_img, $src_img, $srcx, $srcy, 0, 0, $dst_width, $dst_height, ImageSX($src_img),ImageSY($src_img));

		ImageInterlace($dst_img);
        ImageJPEG($dst_img, $save_filename, '100');

		ImageDestroy($dst_img);
		ImageDestroy($src_img);

		chmod( $save_filename, 0777 );
	} 

	*/
	/*
	// 배경크기를 이미지크기로 축소, 이미지 비율로 축소
	function thumnailImg($file, $save_filename, $max_width, $max_height){
		$type = end(explode('.',$file));
		if($type == 'jpeg') $type = 'jpg';
		
		//: 기존
		//switch($type){
		//	case 'bmp': $src_img = imagecreatefromwbmp($file); break;
		//	case 'gif': $src_img = imagecreatefromgif($file); break;
		//	case 'jpg': $src_img = imagecreatefromjpeg($file); break;
		//	case 'png': $src_img = imagecreatefrompng($file); break;
		//	default : return "Unsupported picture type!";
		//}
				
		// 이미지 회전시작.
		@ini_set('gd.jpeg_ignore_warning', 1);
		if($type == "jpg" || $type == "jpeg"){
		  $src_img = @imagecreatefromjpeg($file);
		}else if($type == "png"){
		  $src_img = imagecreatefrompng($file);
		}else if($type == "bmp" || $type == "wbmp"){
		  $src_img = imagecreatefromwbmp($file);
		}else if($type == "gif"){
		  $src_img = imagecreatefromgif($file);
		}
		$exif = exif_read_data($file);
		if(!empty($exif['Orientation'])) {
		  switch($exif['Orientation']) {
				case 8:
					 $src_img = imagerotate($src_img,90,0);
					 break;
				case 3:
					 $src_img = imagerotate($src_img,180,0);
					 break;
				case 6:
					 $src_img = imagerotate($src_img,-90,0);
					 break;
		  }
		  if($ext == "jpg" || $ext == "jpeg"){
				imagejpeg($src_img,$file);
		  }else if($ext == "png"){
				imagepng($src_img,$file);
		  }else if($ext == "bmp" || $ext == "wbmp"){
				imagewbmp($src_img,$file);
		  }else if($ext == "gif"){
				imagegif($src_img,$file);
		  }
		}
		// 이미지 회전종료.
		
       //$src_img = ImageCreateFromJPEG($file);		

	   $img_info = getImageSize($file);
       $img_width = $img_info[0];
       $img_height = $img_info[1];

       if($img_width > $max_width || $img_height > $max_height)
       {
              if($img_width == $img_height) {
                     $dst_width = $max_width;
                     $dst_height = $max_height;
              }elseif($img_width > $img_height){
                     $dst_width = $max_width;
                     $dst_height = ceil(($max_width / $img_width) * $img_height);
              }else{
                     $dst_height = $max_height;
                     $dst_width = ceil(($max_height / $img_height) * $img_width);
              }
               if($dst_height > $max_height) {
                     $dst_height = $max_height;
                     $dst_width = ceil(($max_height / $img_height) * $img_width);
               }     

              $max_width = $dst_width; //배경을 이미지크기로
              $max_height = $dst_height; //배경을 이미지크기로             
       }else{
              $dst_width = $img_width;
              $dst_height = $img_height;

              $max_width = $img_width; //이미지가 작은경우 이미지크기로
              $max_height = $img_height; //이미지가 작은경우 이미지크기로             
       }

		if($dst_width < $max_width) $srcx = ceil(($max_width - $dst_width)/2); else $srcx = 0;
		if($dst_height < $max_height) $srcy = ceil(($max_height - $dst_height)/2); else $srcy = 0;

		if($img_info[2] == 1) $dst_img = imagecreate($max_width, $max_height);
		else $dst_img = imagecreatetruecolor($max_width, $max_height);


		$bgc = ImageColorAllocate($dst_img, 255, 255, 255);
		ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc); 
		ImageCopyResampled($dst_img, $src_img, $srcx, $srcy, 0, 0, $dst_width, $dst_height, ImageSX($src_img),ImageSY($src_img));

		ImageInterlace($dst_img);

		switch($type){
			case 'bmp': imagewbmp($dst_img, $save_filename); break;
			case 'gif': imagegif($dst_img, $save_filename); break;
			case 'jpg': imagejpeg($dst_img, $save_filename); break;
			case 'png': imagepng($dst_img, $save_filename); break;
		}

       // ImageJPEG($dst_img, $save_filename, '100');

		ImageDestroy($dst_img);
		ImageDestroy($src_img);

		//chmod( $save_filename, 0777 );
		return basename($save_filename);  // 파일명만 추출하여 전달.
	} 
	


	/*
		Function	: getThumb($param)
		Param		: $param['o_path'] = 원본 파일 경로
					  $param['n_path'] = 새 파일 경로
					  $param['width'] = 썸네일 이미지 넓이
					  $param['height'] = 썸네일 이미지 높이
					  $param['mode'] = ratio or fixed	(ratio => 비율유지, fixed => 파라메터의 크기로 강제 변경)
					  $param['fill_yn'] = 'Y' or 'N'	(mode가 ratio일 경우 부족한부분 투명 배경처리)
					  $param['preview_yn'] = 'Y' or 'N' (미리보기 방지 여부 => 미리보기방지 대체 이미지 제공)
		Return		: array('bool' => true or false, 'src' => 썸네일 이미지 url, 'msg' => 성공, 실패 메세지)
	*/
	function getThumb($param){

		if(empty($param['o_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
		if(empty($param['n_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
		if(!in_array($param['mode'], array('ratio', 'fixed')))	$param['mode'] = 'ratio';
		if(empty($param['width']))		$param['width'] = 900;
		if(empty($param['height']))		$param['height'] = 900;
		if(!in_array($param['fill_yn'], array('Y', 'N')))		$param['fill_yn'] = 'N';
		if(!in_array($param['preview_yn'], array('Y', 'N')))	$param['preview_yn'] = 'Y';

		// 미리보기 방지 이미지 url
		if($param['preview_yn'] == 'N')	$param['o_path'] = './hidden.png';

		$src = array();
		$dst = array();

		$src['path'] = $param['o_path'];
		$dst['path'] = $param['n_path'];

		// 썸네일 이미지 갱신 기간 (1주일)
		if(file_exists($dst['path'])){
			if(mktime() - filemtime($dst['path']) < 60 * 60 * 24 * 7)	return array('bool' => true, 'src' => $dst['path']);
		}

		$imginfo = getimagesize($src['path']);
		$src['mime'] = $imginfo['mime'];

		// 원본 이미지 리소스 호출

		/*
		// 이미지 회전시작.
		@ini_set('gd.jpeg_ignore_warning', 1);
		if($src['mime']=='image/jpeg'){
		  $src['img'] = @imagecreatefromjpeg($src['path']);
		}else if($src['mime']=='image/png'){
		  $src['img'] = imagecreatefrompng($src['path']);
		}else if($src['mime']=='image/bmp'){
		  $src['img'] = imagecreatefromwbmp($src['path']);
		}else if($src['mime']=='image/gif'){
		  $src['img'] = imagecreatefromgif($src['path']);
		}
		$exif = @exif_read_data($src['path']);
		if(!empty($exif['Orientation'])) {
		  switch($exif['Orientation']) {
				case 8:
					 $src['img'] = imagerotate($src['img'],90,0);
					 break;
				case 3:
					 $src['img'] = imagerotate($src['img'],180,0);
					 break;
				case 6:
					 $src['img'] = imagerotate($src['img'],-90,0);
					 break;
		  }
		  if($src['mime']=='image/jpeg'){
				imagejpeg($src['img'],$src['path']);
		  }else if($src['mime']=='image/png'){
				imagepng( $src['img'],$src['path']);
		  }else if($src['mime']=='image/bmp'){
				imagewbmp( $src['img'],$src['path']);
		  }else if($src['mime']=='image/gif'){
				imagegif( $src['img'],$src['path']);
		  }
		}
		// 이미지 회전종료.
		*/

		switch($src['mime']){
			case 'image/jpeg' :	$src['img'] = imagecreatefromjpeg($src['path']);	break;
			case 'image/gif' :	$src['img'] = imagecreatefromgif($src['path']);		break;
			case 'image/png' :	$src['img'] = imagecreatefrompng($src['path']);		break;
			case 'image/bmp' :	$src['img'] = imagecreatefrombmp($src['path']);		break;
			// mime 타입이 해당되지 않으면 return false
			default :		return array('bool' => false, 'msg' => '이미지 파일이 아닙니다.');						break;
		}

		// 원본 이미지 크기 / 좌표 초기값
		//echo $imginfo[0];
		//echo "..";
		//echo $imginfo[1];
		//exit ;

		$src['w'] = $imginfo[0];
		$src['h'] = $imginfo[1];
		$src['x'] = 0;
		$src['y'] = 0;

		// 썸네일 이미지 좌표 초기값 설정
		$dst['x'] = 0;
		$dst['y'] = 0;

		$img_width = $imginfo[0];
		$img_height = $imginfo[1];

		$max_width = $param['width'];
		$max_height = $param['height'];



		// 썸네일 이미지 가로, 세로 비율 계산
		//$dst['ratio']['w'] = $src['w'] / $param['width'];
		//$dst['ratio']['h'] = $src['h'] / $param['height'];

		switch($param['mode']){
			case 'ratio' :
				if($img_width > $max_width || $img_height > $max_height)
				{
				  // 썸네일 이미지의 비율계산 (가로 == 세로)
					if($src['w'] == $dst['ratio']['h']){
						$dst['w'] = $param['width'];
						$dst['h'] = $param['height'];
					}
					// 썸네일 이미지의 비율계산 (가로 > 세로)
					elseif($src['w'] > $src['h']){
						$dst['w'] = $param['width'];
						//$dst['h'] = round(($param['width'] * $src['h']) / $src['w']);
						$dst['h'] = ceil(($max_width / $img_width) * $img_height);
					}
					// 썸네일 이미지의 비율계산 (가로 < 세로)
					else{
						$dst['w'] = ceil(($max_height / $img_height) * $img_width);
						$dst['h'] = $param['height'];
					}
				}else{
					  $dst['w'] = $img_width;
					  $dst['h'] = $img_height;

					  $max_width = $img_width; //이미지가 작은경우 이미지크기로
					  $max_height = $img_height; //이미지가 작은경우 이미지크기로
				}

				if($param['fill_yn'] == 'Y'){
					$dst['canvas']['w'] = $param['width'];
					$dst['canvas']['h'] = $param['height'];
					$dst['x'] = $param['width'] > $dst['w'] ? ($param['width'] - $dst['w']) / 2 : 0;
					$dst['y'] = $param['height'] > $dst['h'] ? ($param['height'] - $dst['h']) / 2 : 0;
				}
				else{
					$dst['canvas']['w'] = $dst['w'];
					$dst['canvas']['h'] = $dst['h'];
				}
				break;
			case 'fixed' :
				// 썸네일 이미지의 비율계산 (가로 == 세로)
				if($dst['ratio']['w'] == $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = $param['height'];
				}
				// 썸네일 이미지의 비율계산 (가로 > 세로)
				elseif($dst['ratio']['w'] > $dst['ratio']['h']){
					$dst['w'] = $src['w'] / $dst['ratio']['h'];
					$dst['h'] = $param['height'];

					$src['x'] = ($dst['w'] - $param['width']) / 2;
				}
				// 썸네일 이미지의 비율계산 (가로 < 세로)
				elseif($dst['ratio']['w'] < $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = $src['h'] / $dst['ratio']['w'];

					$dst['y'] = 0;
				}
				$dst['canvas']['w'] = $param['width'];
				$dst['canvas']['h'] = $param['height'];
				break;
		}

		// 썸네일 이미지 리소스 생성
		$dst['img'] = imagecreatetruecolor($dst['canvas']['w'], $dst['canvas']['h']);

		// 배경색 처리
		if(in_array($src['mime'], array('image/png', 'image/gif'))){
			// 배경 투명 처리. png 이미지 썸네일시 이미지 깨짐현상이 발생하여 주석처리함.
			imagetruecolortopalette($dst['img'], false, 255);
			$bgcolor = imagecolorallocatealpha($dst['img'], 255, 255, 255, 127);
			imagefilledrectangle($dst['img'], 0, 0, $dst['canvas']['w'],$dst['canvas']['h'], $bgcolor);
		}
		else{
			// 배경 흰색 처리
			$bgclear = imagecolorallocate($dst['img'],255,255,255);
			imagefill($dst['img'],0,0,$bgclear);
		}

		// 원본 이미지 썸네일 이미지 크기에 맞게 복사
		imagecopyresampled($dst['img'] ,$src['img'] ,$dst['x'] ,$dst['y'] ,$src['x'] ,$src['y'] ,$dst['w'] ,$dst['h'] ,$src['w'] ,$src['h']);

		// imagecopyresampled 함수 사용 불가시 사용
		//imagecopyresized($dst['img'] ,$src['img'] ,$dst['x'] ,$dst['y'] ,$src['x'] ,$src['y'] ,$dst['w'] ,$dst['h'] ,$src['w'] ,$src['h']);

		ImageInterlace($dst['img']);

		// 썸네일 이미지 리소스를 기반으로 실제 이미지 생성
		switch($src['mime']){
			case 'image/jpeg' :	imagejpeg($dst['img'], $dst['path']);	break;
			case 'image/gif' :	imagegif($dst['img'], $dst['path']);	break;
			case 'image/png' :	imagepng($dst['img'], $dst['path']);	break;
			case 'image/bmp' :	imagebmp($dst['img'], $dst['path']);	break;
		}

		// 원본 이미지 리소스 종료
		imagedestroy($src['img']);
		// 썸네일 이미지 리소스 종료
		imagedestroy($dst['img']);

		// 썸네일 파일경로 존재 여부 확인후 리턴
		return file_exists($dst['path']) ? array('bool' => true, 'src' => $dst['path']) : array('bool' => false, 'msg' => '파일 생성에 실패하였습니다.');
	}

	//'alert창으로 메세지를 보여준후 strurl로 이동
	function alertMsgUrl ($strMsg, $strUrl) {
		echo "<Script language=javascript>"; 
		echo "alert('".$strMsg."');"; 
		echo "window.location='".$strUrl."';"; 
		echo "</script>";
		exit;
	}
	
	/*
	// 관리자 권한 보여주기.
	function adminGbnView ($temp) {
		if ($temp=="00"){
			return "슈퍼관리자";
		}
		else if ($temp=="01"){
			return "일반관리자";
		}
		
	}
	
	function adminSuperUser($temp){
		if ($temp=="00"){
			return true;
		}
		else if ($temp=="01"){
			return false;
		}
	}
	*/
	//	2013-04-30 
	// Sql Injection
	function sqlInject($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		$str = str_replace("'","&#39;",$str);
		//$str = preg_replace("(select|union|insert|update|delete|and|or|drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $str); 
		$str = preg_replace("(select|union|insert|update|delete|drop|\"|\'|#|\/\*|\*\/|\\\|\;)", "", $str); 

		return $str;
	}

	function webContent($str){
		$str = str_replace("'","&#39;",$str);
		return $str;
	}

	function reWebContent($str){
		$str = str_replace("&39","'", $str);
		//$str = str_replace("&#39;","'", $str);
		return $str;
	}

	//	2013-04-30 
	// Xss처리
	function rejectXss($str) {
		$str = str_replace("&","&amp;",$str);
		$str = str_replace("<","&lt;",$str);
		$str = str_replace(">","&gt;",$str);
		//$str = str_replace("""","&quot;",$str);
		$str = str_replace("'","&#39;",$str);

		$str = preg_replace("#\/\*.*\*\/#", "", $str);

		$str = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $str);
		$str = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $str);
		$str = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $str);
		$str = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $str);
		$str = preg_replace_callback("#<([^>]+)#", create_function('$m', 'return "<".str_replace("<", "&lt;", $m[1]);'), $str);
		//$str = preg_replace("/\<(\w|\s|\?)*(xml)/i", "", $str);
		$str = preg_replace("/\<(\w|\s|\?)*(xml)/i", "_$1$2_", $str);

		// 플래시의 액션스크립트와 자바스크립트의 연동을 차단하여 악의적인 사이트로의 이동을 막는다.
		$str = preg_replace("/((?<=\<param|\<embed)[^>]+)(\s*=\s*[\'\"]?)always([\'\"]?)([^>]+(?=\>))/i", "$1$2never$3$4", $str);

		// 이미지 태그의 src 속성에 삭제등의 링크가 있는 경우 게시물을 확인하는 것만으로도 데이터의 위변조가 가능하므로 이것을 막음
		$str = preg_replace("/<(img[^>]+delete\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $str);
		$str = preg_replace("/<(img[^>]+delete_comment\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $str);
		$str = preg_replace("/<(img[^>]+logout\.php[^>]+)/i", "*** CSRF 감지 : &lt;$1", $str);
		$str = preg_replace("/<(img[^>]+download\.php[^>]+bo_table[^>]+)/i", "*** CSRF 감지 : &lt;$1", $str);

		return $str;
	}

	// 페이지 이동 스크립트
	function movePage($url) {
		global $conn;
		echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
		if($conn) @mysql_close($conn);
		exit;
	}

	//value check
	function is_empty($v) {
		$v = trim($v);
		if ( !isset($v) || ( $v == "" )  || ( is_null($v) ) || ( $v == "(null)")) :
			return true;
		else:
			return false;
		endif;
	}

	/*
	 * 첨부파일 용량 단위 환산 
	 */

	function fChangeSizeUnit($argByte) {
		$ret = "";
	 
		if (is_numeric($argByte)) {
			if ($argByte > 1024 && $argByte < 1048576) {
				$ret = Round($argByte / 1024 ,2 );
				$ret = $ret."KB";
	 
			} elseif ($argByte > 1048576 && $argByte < 1073741824) {
				$ret = Round($argByte / (1024 * 1024), 2);
				$ret = $ret."MB";
			} elseif ($argByte > 1073741824 && $argByte < 1099511627776 ) {
				$ret = Round($argByte / (1024 * 1024 * 1024) ,2 );
				$ret = $ret."GB";
			} elseif ($argByte > 1099511627776) {
				$ret = Round($argByte / (1024 * 1024 * 1024 * 1024) ,2 );
				$ret = $ret."TB";
			} else {
				$ret = $argByte."B";
			} 
		} 
		return  $ret;
	}
	
	// 관리자 로그인 체크.
	function AdminAuthCheck($temp)
	{
		$AdminLoginGoUrl = $_SERVER["REQUEST_URL"];
		$AdminLoginQuery = $_SERVER["QUERY_STRING"];
		$AdminLoginQuery = str_replace($AdminLoginQuery,"=","-");
		$AdminLoginQuery = str_replace($AdminLoginQuery,"&","^");

		//echo "$_SESSION[SS_ADM_ID] : " . $_SESSION[SS_ADM_ID];
		if ( is_empty($_SESSION[SS_ADM_ID]) ) {
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=/admin/main/login.php\">";
			alertMsgUrl ("로그인이 필요합니다.", "/admin/main/login.php");
			exit;
		}
	}
	
	// 밀리세컨드 시간구하기.
	function exact_time() {
		$t = explode(' ',microtime());
		return floor(($t[0] + $t[1])*1000);
	}

	
	//금지어 체크
	function word_filter($words, $deny_words){
		 $deny_to_words = "*";
		 if ($deny_words!=""&&$words!=""){
			$ndwords=array();
			$mdeny_words=explode(",",$deny_words);
			foreach ($mdeny_words as $rdwords){
				$r_deny_words=str_repeat("$deny_to_words", strlen($rdwords));
				$ndwords["$rdwords"]="$r_deny_words";
			 }
			 $r=strtr("$words",$ndwords);
		 }else{
			$r=$words;
		 }
		 return $r;
	 }

	//'EMAIL 발송 함수
	//'argMID : EMAIL ID, argSENDER : 발송자, argRECEIVER : 수신자, 
	//'argSUBJECT : 메일제목
	//'argRECEIVE_CHECK_URL : 수신체크 URL
	function sendMail($argSENDER, $argRECEIVER, $argSUBJECT, $strBODY) {
		
		//'발송자 가 없을경우 기본값
		if (is_empty($argSENDER)) {
			$argSENDER = $SYSTEM_EMAIL; 
		}

		if (is_empty($argRECEIVER)) {
			return  False;
			break;
		}

		/* HTML 메일을 보내려면, Content-type 헤더를 설정 */
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=euc-kr\r\n";
		//$headers = "From: localhost@localhost.com\nReply-To: localhost@localhost.com";

		/* 추가 헤더 */
		$headers .= "Return-Path: SNNC <" . $argSENDER . ">\r\n";
		$headers .= "From: SNNC <" . $argSENDER . ">\r\nReply-To: ". $argSENDER;
		
		if (mail($argRECEIVER, $argSUBJECT, $strBODY, $headers)) 
			return  True;
		else
			return false;
	}


	// 사용자 로그인 체크.
	function userAuthCheck()
	{		
		if ( is_empty($_SESSION[SS_USER_ID]) ) {
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=/admin/main/login.php\">";
			alertMsgUrl ("로그인이 필요합니다.", "#");
			exit;
		}
	}


//난수 발생
function fnGetRePwd(){

	$maxlength = 8;

    $defaultString = "ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789";
    srand((double)microtime()*1000000); 

   $length = strlen($defaultString);

   for($i=0;$i<$maxlength;$i++)
  {
     $Str = rand(0,$length-1);
     $resultStr .= substr( $defaultString, $Str, 1 );
   }

  return $resultStr;
}

//금지어 추출
function getBadWord($words, $deny_words){
		$Banned_words = "";

		$mdeny_words=explode(",",$deny_words);
		foreach ($mdeny_words as $rdwords){
			if(strpos($words,$rdwords) !== false)
			{
				$Banned_words .= $rdwords.", ";
			}
		 }
		 return $Banned_words;
	 }

/**
 *  PHP 파일 다운로드 함수.
 *  아래와 같은 기능을 수행한다.
 *
 *  1. UTF-8 파일명이 깨지지 않도록 한다. (RFC2231/5987 표준 및 브라우저 버전별 특성 감안)
 *  2. 일부 OS에서 파일명에 사용할 수 없는 문자가 있는 경우 제거 또는 치환한다.
 *  3. 캐싱을 원할 경우 적절한 Cache-Control, Expires 등의 헤더를 넣어준다.
 *  4. IE 8 이하에서 캐싱방지 헤더 사용시 다운로드 오류가 발생하는 문제를 보완한다.
 *  5. 이어받기를 지원한다. (Range 헤더 자동 감지, Accept-Ranges 헤더 자동 생성)
 *  6. 일부 PHP 버전에서 대용량 파일 다운로드시 메모리 누수를 막는다.
 *  7. 다운로드 속도를 제한할 수 있다.
 *
 *  사용법  :  send_attachment('클라이언트에게 보여줄 파일명', '서버측 파일 경로', [캐싱할 기간], [속도 제한]);
 *
 *             아래의 예는 foo.jpg라는 파일을 사진.jpg라는 이름으로 다운로드한다.
 *             send_attachment('사진.jpg', '/srv/www/files/uploads/foo.jpg');
 *
 *             아래의 예는 bar.mp3라는 파일을 24시간 동안 캐싱하고 다운로드 속도를 300KB/s로 제한한다.
 *             send_attachment('bar.mp3', '/srv/www/files/uploads/bar.mp3', 60 * 60 * 24, 300);
 *
 *  반환값  :  전송에 성공한 경우 true, 실패한 경우 false를 반환한다.
 *
 *  주  의  :  1. 전송이 완료된 후 다른 내용을 또 출력하면 파일이 깨질 수 있다.
 *                가능하면 그냥 곧바로 exit; 해주기를 권장한다.
 *             2. PHP 5.1 미만, UTF-8 환경이 아닌 경우 정상 작동을 보장할 수 없다.
 *                특히 EUC-KR 환경에서는 틀림없이 파일명이 깨진다.
 *             3. FastCGI/FPM 환경에서 속도 제한 기능을 사용할 경우 PHP 프로세스를 오랫동안 점유할 수 있다.
 *                따라서 가능하면 웹서버 자체의 속도 제한 기능을 사용하는 것이 좋다.
 *             4. 안드로이드 일부 버전의 기본 브라우저에서 한글 파일명이 깨질 수 있다.
 */
function send_attachment($filename, $server_filename, $expires = 0, $speed_limit = 0) {
    
    // 서버측 파일명을 확인한다.
    
    if (!file_exists($server_filename) || !is_readable($server_filename)) {
        return false;
    }
    if (($filesize = filesize($server_filename)) == 0) {
        return false;
    }
    if (($fp = @fopen($server_filename, 'rb')) === false) {
        return false;
    }
    
    // 파일명에 사용할 수 없는 문자를 모두 제거하거나 안전한 문자로 치환한다.
    
    $illegal = array('\\', '/', '<', '>', '{', '}', ':', ';', '|', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '?');
    $replace = array('', '', '(', ')', '(', ')', '_', ',', '_', '', '_', '\'', '_', '_', '_', '_', '_', '_', '', '');
    $filename = str_replace($illegal, $replace, $filename);
    $filename = preg_replace('/([\\x00-\\x1f\\x7f\\xff]+)/', '', $filename);
    
    // 유니코드가 허용하는 다양한 공백 문자들을 모두 일반 공백 문자(0x20)로 치환한다.
    
    $filename = trim(preg_replace('/[\\pZ\\pC]+/u', ' ', $filename));
    
    // 위에서 치환하다가 앞뒤에 점이 남거나 대체 문자가 중복된 경우를 정리한다.
    
    $filename = trim($filename, ' .-_');
    $filename = preg_replace('/__+/', '_', $filename);
    if ($filename === '') {
        return false;
    }
    
    // 브라우저의 User-Agent 값을 받아온다.
    
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $old_ie = (bool)preg_match('#MSIE [3-8]\.#', $ua);
    
    // 파일명에 숫자와 영문 등만 포함된 경우 브라우저와 무관하게 그냥 헤더에 넣는다.
    
    if (preg_match('/^[a-zA-Z0-9_.-]+$/', $filename)) {
        $header = 'filename="' . $filename . '"';
    }
    
    // IE 9 미만 또는 Firefox 5 미만의 경우.
    
    elseif ($old_ie || preg_match('#Firefox/(\d+)\.#', $ua, $matches) && $matches[1] < 5) {
        $header = 'filename="' . rawurlencode($filename) . '"';
    }
    
    // Chrome 11 미만의 경우.
    
    elseif (preg_match('#Chrome/(\d+)\.#', $ua, $matches) && $matches[1] < 11) {
        $header = 'filename=' . $filename;
    }
    
    // Safari 6 미만의 경우.
    
    elseif (preg_match('#Safari/(\d+)\.#', $ua, $matches) && $matches[1] < 6) {
        $header = 'filename=' . $filename;
    }
    
    // 안드로이드 브라우저의 경우. (버전에 따라 여전히 한글은 깨질 수 있다. IE보다 못한 녀석!)
    
    elseif (preg_match('#Android #', $ua, $matches)) {
        $header = 'filename="' . $filename . '"';
    }
    
    // 그 밖의 브라우저들은 RFC2231/5987 표준을 준수하는 것으로 가정한다.
    // 단, 만약에 대비하여 Firefox 구 버전 형태의 filename 정보를 한 번 더 넣어준다.
    
    else {
        $header = "filename*=UTF-8''" . rawurlencode($filename) . '; filename="' . rawurlencode($filename) . '"';
    }
    
    // 캐싱이 금지된 경우...
    
    if (!$expires) {
        
        // 익스플로러 8 이하 버전은 SSL 사용시 no-cache 및 pragma 헤더를 알아듣지 못한다.
        // 그냥 알아듣지 못할 뿐 아니라 완전 황당하게 오작동하는 경우도 있으므로
        // 캐싱 금지를 원할 경우 아래와 같은 헤더를 사용해야 한다.
        
        if ($old_ie) {
            header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
        }
        
        // 그 밖의 브라우저들은 말을 잘 듣는 착한 어린이!
        
        else {
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
        }
    }
    
    // 캐싱이 허용된 경우...
    
    else {
        header('Cache-Control: max-age=' . (int)$expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (int)$expires) . ' GMT');
    }
    
    // 이어받기를 요청한 경우 여기서 처리해 준다.
    
    if (isset($_SERVER['HTTP_RANGE']) && preg_match('/^bytes=(\d+)-/', $_SERVER['HTTP_RANGE'], $matches)) {
        $range_start = $matches[1];
        if ($range_start < 0 || $range_start > $filesize) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            return false;
        }
        header('HTTP/1.1 206 Partial Content');
        header('Content-Range: bytes ' . $range_start . '-' . ($filesize - 1) . '/' . $filesize);
        header('Content-Length: ' . ($filesize - $range_start));
    } else {
        $range_start = 0;
        header('Content-Length: ' . $filesize);
    }
    
    // 나머지 모든 헤더를 전송한다.
    
    header('Accept-Ranges: bytes');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; ' . $header);
    
    // 출력 버퍼를 비운다.
    // 파일 앞뒤에 불필요한 내용이 붙는 것을 막고, 메모리 사용량을 줄이는 효과가 있다.
    
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // 파일을 64KB마다 끊어서 전송하고 출력 버퍼를 비운다.
    // readfile() 함수 사용시 메모리 누수가 발생하는 경우가 가끔 있다.
    
    $block_size = 16 * 1024;
    $speed_sleep = $speed_limit > 0 ? round(($block_size / $speed_limit / 1024) * 1000000) : 0;
    
    $buffer = '';
    if ($range_start > 0) {
        fseek($fp, $range_start);
        $alignment = (ceil($range_start / $block_size) * $block_size) - $range_start;
        if ($alignment > 0) {
            $buffer = fread($fp, $alignment);
            echo $buffer; unset($buffer); flush();
        }
    }
    while (!feof($fp)) {
        $buffer = fread($fp, $block_size);
        echo $buffer; unset($buffer); flush();
        usleep($speed_sleep);
    }
    
    fclose($fp);
    
    // 전송에 성공했으면 true를 반환한다.
    
    return true;
}

class Log {
    public static function debug($str) {
        print "DEBUG: " . $str . "\n";
    }
    public static function info($str) {
        print "INFO: " . $str . "\n";
    }
}
function Curl($url, $post_data, &$http_status, &$header = null) {
    //Log::debug("Curl $url JsonData=" . $post_data);
    $ch=curl_init();
    // user credencial
    curl_setopt($ch, CURLOPT_USERPWD, "username:passwd");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	 curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

    // post_data
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    if (!is_null($header)) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $response = curl_exec($ch);
   // Log::debug('Curl exec=' . $url);
      
    $body = null;
    // error
    if (!$response) {
        $body = curl_error($ch);
        // HostNotFound, No route to Host, etc  Network related error
        $http_status = -1;
       // Log::error("CURL Error: = " . $body);
    } else {
       //parsing http status code
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!is_null($header)) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
        } else {
            $body = $response;
        }
    }
    curl_close($ch);
    return $body;
}

// 관심고객 접속구분.
	function UserRegisterType($temp)
	{
		if ($temp=='W'){
			return 'PC';
		}
		else if ($temp=='M'){
			return '모바일';
		}
	}

	// 관심고객 성별.
	function UserGender($temp)
	{
		if ($temp=='m'){
			return '남';
		}
		else if ($temp=='w'){
			return '여';
		}
	}
