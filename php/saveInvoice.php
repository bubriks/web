<?php
	$data = stripcslashes($_POST['data']);
	$data = json_decode($data,TRUE);
	
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "UPDATE registry
			INNER JOIN representative as sender ON registry.senderId = sender.id
			INNER JOIN company as senderC on sender.companyId = senderC.id
			INNER JOIN representative as receiver ON registry.receiverId = receiver.id
			INNER JOIN company as receiverC on receiver.companyId = receiverC.id
			SET registry.docNumber = '".$data[1]."',
				registry.prescriptionDate = '".$data[2][0]."',
				registry.receptionDate = '".$data[2][1]."',
				registry.paymentDate = '".$data[2][2]."',
				registry.transport = ".$data[5].",
				sender.name = '".$data[3][5]."',
				senderC.name = '".$data[3][0]."',
				senderC.regNumber = '".$data[3][1]."',
				senderC.location = '".$data[3][2]."',
				senderC.address = '".$data[3][3]."',
				senderC.bankNumber = '".$data[3][4]."',
				receiver.name = '".$data[4][5]."',
				receiverC.name = '".$data[4][0]."',
				receiverC.regNumber = '".$data[4][1]."',
				receiverC.location = '".$data[4][2]."',
				receiverC.address = '".$data[4][3]."',
				receiverC.bankNumber = '".$data[4][4]."'
			where registry.id = ".$data[0].";";
	
	foreach ($data[6] as $row){
		$id = floatval($row['id']);
		if($id != 0){
			$sql .= "SET @update_id := ".floatval($row['productGroupId']).";
					INSERT INTO productgroup(id, name, tax) VALUES (@update_id,'".$row['itemGroup']."',".floatval($row['tax']).")
					ON DUPLICATE KEY UPDATE tax = VALUES(tax), id=LAST_INSERT_ID(id);

					UPDATE item 
					INNER JOIN product ON item.productId = product.id
					SET item.serNumber = '".$row['serNumber']."', 
						item.incomingPrice = ".floatval($row['priceIn']).",
						item.quantity = ".intval($row['amount']).",
						product.barcode = '".$row['barcode']."',
						product.name = '".$row['name']."',
						product.productGroupId = LAST_INSERT_ID()
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