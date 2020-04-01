<?php
session_start();
if ($_SESSION['validUser']??false){
	$signedIn = true;
}

if ( !$signedIn ){header("Location: login.php");}

require "threerecords.php";
require 'dbConnect.php';


$dataobj =json_decode($data);


	try
	{
		//$sql = "DELETE FROM wdv341_event WHERE event_id=$x->event_id";
		$sql = "DELETE FROM wdv341_event";
		$conn->exec($sql);
		//echo "\nRecord deleted successfully";
		

		
			try{
				$sql = "INSERT INTO wdv341_event (
						event_name, 
						event_description,
						event_presenter,
						event_date,
						event_time
						)
					VALUES (
						:eventName, 
						:eventDescription, 
						:eventPresenter,
						:eventDate,
						:eventTime
						)";
				$stmt = $conn->prepare($sql);
				//Bind parameters to the prepared statement object, one for each parameter
				foreach($dataobj as $x){
					$stmt->bindParam(':eventName', $x->event_name);
					$stmt->bindParam(':eventDescription', $x->event_description);
					$stmt->bindParam(':eventPresenter', $x->event_presenter);
					$stmt->bindParam(':eventDate', $x->event_date);
					$stmt->bindParam(':eventTime', $x->event_time);
					
					//print_r($x->event_presenter);
					
					$stmt->execute();
					$_SESSION['message'] = '';
					header("Location: " . $_GET['source']);
				}
			}
			catch(PDOException $e)
			{
				echo $sql . "<br>" . $e->getMessage();
				//header("Location: " . $_GET['source']);
			}
		
	}
	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}



?>