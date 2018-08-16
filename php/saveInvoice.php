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
		$sql .= "INSERT INTO productgroup(id, name, tax) VALUES (".floatval($row['productGroupId']).",'".$row['itemGroup']."',".floatval($row['tax']).")
				ON DUPLICATE KEY UPDATE tax = VALUES(tax), id=LAST_INSERT_ID(id);
				
				INSERT INTO product(id, productGroupId, name, barcode) VALUES (".floatval($row['productId']).",LAST_INSERT_ID(),'".$row['name']."','".$row['barcode']."')
				ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), productGroupId = VALUES(productGroupId), name = VALUES(name);
				
				INSERT INTO item(id, productId, serNumber, incomingPrice, quantity) VALUES (".floatval($row['id']).",LAST_INSERT_ID(), '".$row['serNumber']."', 
				".floatval($row['priceIn']).", ".intval($row['amount']).")
				ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), productId = VALUES(productId), serNumber = VALUES(serNumber), incomingPrice = VALUES(incomingPrice),
				quantity = VALUES(quantity);
				
				INSERT INTO items(registryId, itemId) VALUES (".$data[0].",LAST_INSERT_ID())
				ON DUPLICATE KEY UPDATE registryId=registryId;";
	}
	
	if (mysqli_multi_query($conn, $sql)) {
		echo "Veiksmīgi saglabāts";
	} else {
		echo "Kļūda!";
	}

	mysqli_close($conn);
?>