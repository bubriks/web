<?php
	$data = stripcslashes($_POST['data']);
	$data = json_decode($data,TRUE);
	
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "";
	
	foreach ($data[6] as $row){
		$id = floatval($row['id']);
		if($id != 0){
			$sql .= "UPDATE item 
					INNER JOIN product ON item.productId = product.id
					INNER JOIN productgroup on product.productGroupId = productgroup.id
					SET item.serNumber = '".$row['serNumber']."', 
						item.incomingPrice = ".floatval($row['priceIn']).",
						product.barcode = '".$row['barcode']."',
						product.name = '".$row['name']."',
						productgroup.name = '".$row['itemGroup']."',
						productgroup.tax = ".floatval($row['tax'])."   
					where item.id = $id;";
		}
	}
	
	if (mysqli_multi_query($conn, $sql)) {
		echo "Veiksmīgi saglabāts";
	} else {
		echo "Kļūda!";
	}

	mysqli_close($conn);
?>