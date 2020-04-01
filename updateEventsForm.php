
<?php

session_start();
if ($_SESSION['validUser']??false){
	$signedIn = true;
}

if ( !$signedIn ){header("Location: login.php");}

require 'dbConnect.php';
$time_errMsg=$date_errMsg=$name_errMsg=$description_errMsg=$presenter_errMsg=$submit_errMsg="";
$event_id="";
$event_name="";
$event_description="";
$event_presenter="";
$event_date="";
$event_time="";

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {

	$result = $_POST;  // associative array

	$event_id=$result["event_id"];
	$event_name=$result["event_name"];
	$event_description=$result["event_description"];
	$event_presenter=$result["event_presenter"];
	$event_date=$result["event_date"];
	$event_time=$result["event_time"];
	$valid_form = true;
	include 'validationsAdvanced.php';
	
	if( !validateID( $event_id )  ) {
		$valid_form = false;
		//session_start();
		$_SESSION['message'] = "Record update failed. Failed record id";
		header("Location: selectEvents.php");
		exit();
	}

	
	if( !validateTime($event_time) ) {
		$valid_form = false;
		$time_errMsg = "Please enter a valid time<br /> In older browsers, 24 hour HH:MM including leading zeroes";
	}
	
	if( !validateDate($event_date) ) {
		$valid_form = false;
		$date_errMsg = "Please enter a valid date<br />In older browsers, YYYY-MM-DD including leading zeroes";
	}
	if( !validateName($event_name) ) {
		$valid_form = false;
		$name_errMsg = "Please enter a valid name";
	}
	if( !validateDescription($event_description) ) {
		$valid_form = false;
		$description_errMsg = "Please enter a valid description";
	}
	if( !validatePresenter($event_presenter) ) {
		$valid_form = false;
		$presenter_errMsg = "Please enter a valid presenter";
	}
	
	if ( $valid_form ){
	
		try {

			$sql = "UPDATE wdv341_event
			SET 
			event_name = '$event_name', 
			event_description = '$event_description', 
			event_presenter = '$event_presenter', 
			event_date = '$event_date', 
			event_time = '$event_time'
			WHERE event_id=$event_id;";


			// Prepare statement
			$stmt = $conn->prepare($sql);

			// execute the query
			$stmt->execute();
			
			session_start();
			$_SESSION['message'] = "Record UPDATED successfully";
			header("Location: selectEvents.php");

			}
		catch(PDOException $e)
			{
			echo $sql . "<br>" . $e->getMessage();
			}
	}
	
	//exit();
}





if (isset($_GET['id']) )
{
	if ( preg_match("/^\d+$/", trim($_GET["id"]) )){
		try {
			//example from https://www.php.net/manual/en/pdostatement.rowcount.php
			$stmt = $conn->prepare("SELECT 
				COUNT(*) as rows 
				FROM wdv341_event 
				where event_id=" . $_GET["id"] );
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$result = $stmt->fetch();  // associative array
			if( $result["rows"] > 0 ){ ;
				$stmt = $conn->prepare("SELECT 
					event_id,
					event_name,
					event_description,
					event_presenter,
					event_date,
					event_time
					FROM wdv341_event
					where event_id=" . $_GET["id"]
					);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				
				$result = $stmt->fetch();  // associative array
				
				$event_id=$result["event_id"];
				$event_name=$result["event_name"];
				$event_description=$result["event_description"];
				$event_presenter=$result["event_presenter"];
				$event_date=$result["event_date"];
				$event_time=$result["event_time"];
				
				
				//exit();
				//foreach($result as $key => $value)		//This will loop through each name-value in the $_POST array
				//{
				//	$resultsTable->setRowValues( array($key, $value) );
				//} 
			}//else{
			//	$resultsTable->setRowValues( array("No records returned") );
			//}
		}
		catch(PDOException $e) {
			$resultsTable->setRowValues( array($e->getMessage()) );
		}
	}else{$resultsTable->setRowValues( array("ID consists of integers only") );}
}

?>

<!DOCTYPE html>


<!--
event_id
event_name
event_description
event_presenter
event_date
event_time
-->
<html lang="en">

	<head>
		<!-- <link href="style.css" rel="stylesheet" type="text/css" /> -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>WDV341 Unit 6</title>
		<style>
			body{background-color:linen}
			.errmessage{color:red}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
		<script src="https://www.google.com/recaptcha/api.js?render=<?php echo  $reCaptchaSiteKey; ?>"></script>
		<script>
			grecaptcha.ready(function () {
				grecaptcha.execute(<?php echo "'" . $reCaptchaSiteKey . "'" ; ?>, { action: 'contact' }).then(function (token) {
					var recaptchaResponse = document.getElementById('recaptchaResponse');
					recaptchaResponse.value = token;
				});
			});
		</script>

	</head>

	<body>

	
<form id="eventinput_form" name="eventinput" method="post" action="updateEventsForm.php">
<input type="hidden" name="event_id" value="<?php echo $event_id?>">
<p>
	<label for="event_name">Event Name</lable>
	<input id="event_name" name="event_name" value="<?php echo $event_name ?>" size="50">
	<p class='errmessage'><?php echo $name_errMsg ?></p>
</p>
<p>
	<label for="event_description">Event Description</lable>
	<input id="event_description" name="event_description" value="<?php echo $event_description ?>" size="50">
	<p class='errmessage'><?php echo $description_errMsg ?></p>
</p>
<p>
	<label for="event_presenter">Event Presenter</lable>
	<input id="event_presenter" name="event_presenter" value="<?php echo $event_presenter ?>" size="50">
	<p class='errmessage'><?php echo $presenter_errMsg ?></p>
</p>
<p>
	<label for="event_date">Event Date</lable>
	<input type="date" id="event_date" name="event_date" value="<?php echo $event_date ?>">
	<p class='errmessage'><?php echo $date_errMsg ?></p>
</p>
<p>
	<label for="event_time">Event Time</lable>
	<input type="time" id="event_time" name="event_time" value="<?php echo $event_time ?>">
	<p class='errmessage'><?php echo $time_errMsg ?></p>
</p>



<input type="submit" name="submit" id="submit" value="Submit">
</form>



	</body>
	
	


</html>


