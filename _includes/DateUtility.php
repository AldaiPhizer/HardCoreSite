<?php
/*include_once("Zend/Date.php");*/
class DateUtility{
	public function getCurrentDate(){
		echo date("j-M-Y");
	}
	
	public function getCurrentTime(){
		/*$zendtime = new Zend_Date();*/
		date_default_timezone_set('UTC');
		echo date("g:i a");
/*		echo "<br/>";
		echo $zendtime->toString("MMM dd, of YYYY");*/
	}

}

?>