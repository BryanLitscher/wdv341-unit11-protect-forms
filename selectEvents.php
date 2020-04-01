<?php
session_start();
if ($_SESSION['validUser']??false){
	$signedIn = true;
}

if ( !$signedIn ){header("Location: login.php");}

require 'dbConnect.php';

class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() {
        return "<td>" . parent::current(). "</td>\n";
    }

    function beginChildren() {
        echo "<tr>";
    }

    function endChildren() {
        echo "</tr>" . "\n\n";
    }
}


$message = "";
if(!empty($_SESSION['message'])) {
   $message = $_SESSION['message'];
}
$_SESSION['message'] = "";

try {
    $stmt = $conn->prepare("SELECT 
		event_id,
		event_name,
		event_description,
		event_presenter,
		event_date,
		event_time
		FROM wdv341_event");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
	
	$result = $stmt->fetchAll();  // associative array
	
	//echo json_encode($result);

	$iterator = new RecursiveArrayIterator($result);
	$tableRows = new TableRows($iterator);  //returns object
	
	$stmt2 = $conn->prepare("SELECT 
		event_id,
		event_name,
		event_description,
		event_presenter,
		event_date,
		event_time
		FROM wdv341_event");
    $stmt2->execute();

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>WDV341 Advanced Javascript Unit 9 Select Events</title>
		<style>
			body{background-color:linen}
			td{ width:2000px;border:1px solid black }
			table {border: solid 1px black}
			.eventBlock{
				width:500px;
				margin-left:auto;
				margin-right:auto;
				background-color:#CCC;	
				//display: flex;
			}
			
			.displayEvent{
				text_align:left;
				font-size:18px;	
			}
			
			.displayDescription {
				//margin-left:100px;
			}
			.samemonth{color:red;font-weight: bold;}
			.futureevent{font-style: italic;}
			.flex{
				display: flex;
				color:red;
				border:thin solid black
				}
			.control{border-bottom:thin solid black;}
		</style>
	</head>

	<body>
		<?php
		
		echo "<h1>". $message . "</h1>";
		if ( count($result) > 0){
			while ( $row = $stmt2 -> fetch(pdo::FETCH_ASSOC)) 
			{
				$datestring = date("M d Y",strtotime($row["event_date"]));
				echo "<p > 
						<div class='eventBlock'>	
							<div class='control'>
								<a href='deleteEvent.php?source=$_SERVER[PHP_SELF]&id=$row[event_id]'>Delete</a><br />
								<a href='updateEventsForm.php?source=$_SERVER[PHP_SELF]&id=$row[event_id]'>Update</a>
							</div>
							<div>
								<span class='displayEvent'>Event:$row[event_id] </span>
								<span>Presenter:$row[event_presenter]</span>
							</div>
							<div>
								<span class='displayDescription'>Name:$row[event_name]</span>
							</div>							
							<div>
								<span class='displayDescription'>Description:$row[event_description]</span>
							</div>
							<div>
								<span class='displayTime'>Time:$row[event_time]</span>
							</div>
							<div>
								<span class='displayDate'>Date: $datestring</span>
							</div>
						</div>
					</p>";
			}
			echo "<p>After all records are deleted, you will be given a link to insert a new set of records</p>";
		}else{
			echo "<h1>No records returned</h1>";
			echo "<a href='reset_recoreds.php?source=$_SERVER[PHP_SELF]'>Fill the database again</a>"; 
		}
		?>
		<ul>
		<li><a href="selectEvents.php">Show a list of events.  Update and Delete option for each event.</a></li>
		<li><a href="processEvents.php">Add New Event</a></li>
		<li><a href="logout.php">Log out</a></li>
		</ul>
	</body>
</html>
