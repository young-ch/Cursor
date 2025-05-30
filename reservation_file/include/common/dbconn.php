<?php
class DBConn
{
	var $mConn;
	function DBConn()
	{
		$this->mConn = mysqli_connect('localhost', 'sucti', 'suctiDB_P@ss!@34') or die("데이터베이스 연결에 실패했습니다..." . mysqli_error() );
		//mysql_select_db('hilldes', $this->mConn);
		/*
		mysqli_query("set session character_set_connection=utf8;");
		mysqli_query("set session character_set_results=utf8;"); 
		mysqli_query("set session character_set_client=utf8;");
		mysqli_query("set names utf8", $this->mConn);
		*/
		mysqli_select_db($this->mConn, 'sucti') or die("데이터베이스 연결에 실패했습니다..." . mysqli_error());
	}

	function DisConnect()
	{
		@mysqli_close($this->mConn);
		$this->mConn = null;
	}
}


class WebLogDBConn
{
	var $mConn;
	
	function WebLogDBConn()
	{
		$this->mConn = mysqli_connect('localhost', 'sucti', 'suctiDB_P@ss!@34') or die("데이터베이스 연결에 실패했습니다..." . mysqli_error() );
		mysqli_select_db($this->mConn, 'sucti') or die("데이터베이스 연결에 실패했습니다..." . mysqli_error());
	}

	function DisConnect()
	{
		@mysql_close($this->mConn);
		$this->mConn = null;
	}

} // end class	

