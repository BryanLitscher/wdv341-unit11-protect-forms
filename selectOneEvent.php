<?php
require 'dbConnect.php';


class htmlTable{
	private $rowValueArray;
	
	function __construct(){
		$this->rowValueArray = array();
	}
	
	public function setRowValues( $a ) {
		array_push($this->rowValueArray, $a);
	}	
	public function getRowValues(  ) {
		return $this->rowValueArray;
	}
	public function getHtmlTable(  ) {
		$html = "<table>";
		foreach($this->rowValueArray as $row){
			$html .= "<tr>";
			foreach($row as $cell ){
				$html .="<td>" ;
				$html .=$cell;
				$html .="<td>" ;
			}
			$html .= "</tr>";
		}
		$html .= "</table>";
		
		return $html;
	}

}


$resultsTable = new htmlTable();
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if ( preg_match("/^\d+$/", trim($_POST["event_id"]) )){
		try {
			//example from https://www.php.net/manual/en/pdostatement.rowcount.php
			$stmt = $conn->prepare("SELECT 
				COUNT(*) as rows 
				FROM wdv341_event 
				where event_id=$_POST[event_id]");
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$result = $stmt->fetch();  // associative array
			if( $result["rows"] > 0 ){ ;
				$stmt = $conn->prepare("SELECT 
					event_id,
					event_description,
					event_presenter,
					event_date,
					event_time
					FROM wdv341_event
					where event_id=$_POST[event_id]
					");
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				
				$result = $stmt->fetch();  // associative array
				
				foreach($result as $key => $value)		//This will loop through each name-value in the $_POST array
				{
					$resultsTable->setRowValues( array($key, $value) );
				} 
			}else{
				$resultsTable->setRowValues( array("No records returned") );
			}
		}
		catch(PDOException $e) {
			$resultsTable->setRowValues( array($e->getMessage()) );
		}
	}else{$resultsTable->setRowValues( array("ID consists of integers only") );}
}

?>


<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>WDV321 Advanced Javascript Unit 6</title>
		<style>
			body{background-color:linen}
		</style>
	</head>

	<body>
		<form method="POST">
			<p>
				<label for="event_id">Event Id</lable>
				<input id="event_id" name="event_id">
				<!-- <p class='errmessage'><?php echo $name_errMsg ?></p> -->
			</p>
			<input type="submit" name="submit" id="submit" value="Submit">
		</form>
		<?php echo $resultsTable->getHtmlTable( ); ?>
	</body>
	
	


</html>
