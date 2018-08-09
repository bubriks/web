<?php
	$tableData = stripcslashes($_POST['pTableData']);
	$tableData = json_decode($tableData,TRUE);
	
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "";
	
	foreach ($tableData as $row){
		$id = floatval($row['id']);
		if($id != 0){
			$sql .= "UPDATE item SET incomingPrice= ".floatval($row['priceIn'])." WHERE id= $id;";
		}
	}
	
	if (mysqli_multi_query($conn, $sql)) {
		echo "Veiksmīgi saglabāts";
	} else {
		echo "Kļūda!";
	}

	mysqli_close($conn);
?>