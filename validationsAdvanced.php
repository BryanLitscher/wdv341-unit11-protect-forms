<?php

// if ( preg_match("/^\d+$/", trim($_GET["id"]) ))

function validateID( $inId ) {
	//cannot be empty
	
	if( empty($inId) ) {
		return false;	//Failed validation
	}elseif(	!preg_match("/^\d+$/", $inId )){
		return false;	//Failed validation
	}	else {
		return true;	//Passes validation	
	}	
}//end validateID()

function validateName( $inName ) {
	//cannot be empty
	
	if( empty($inName) ) {
		return false;	//Failed validation
	}
	else {
		return true;	//Passes validation	
	}	
}//end validateName()

function validateDescription( $inDesc) {
	//cannot be empty
	
	if( empty($inDesc) ) {
		return false;	//Failed validation
	}
	else {
		return true;
	}		
}//end validateDescription
function validatePresenter( $inPres) {
	//cannot be empty
	
	if( empty($inPres) ) {
		return false;	//Failed validation
	}
	else {
		return true;
	}		
}//end validateDescription

function validateTime( $inTime) {
	//cannot be empty
	if( empty($inTime) ) {
		return false;	//Failed validation
	}
	else {
		$time = date("H:i",strtotime($inTime));
		$time2 = date("H:i:s",strtotime($inTime));
		if (!$time){
			return false;;
		}else{			
			//if ( $time->format('H:i') == $inTime ) {
			if ( $time  == $inTime || $time2  == $inTime  ) {
				return True;
			}else{
				return false;
			}
		}
	}
	
}//end validateTime()


function validateDate( $inDate ) {
	if( empty($inDate) ) {
		return false;	//Failed validation
	}else {
		$date = DateTime::createFromFormat('Y-m-d',$inDate);
		if (!$date ){
			return false;
		} else {
			if ( $date->format('Y-m-d') == $inDate ) {
				return True;
			}else{
				return false;
			}
		}
	}
}//end validateDAte()
?>