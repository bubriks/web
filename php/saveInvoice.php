<?php
	$data = stripcslashes($_POST['data']);
	$data = json_decode($data,TRUE);
	
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "INSERT INTO company(id, name, regNumber, location, address, bankNumber) VALUES (".$data[3][7].",'".$data[3][0]."',
				'".$data[3][1]."','".$data[3][2]."','".$data[3][3]."','".$data[3][4]."') 
			ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), name = VALUES(name), regNumber = VALUES(regNumber), location = VALUES(location), 
				address = VALUES(address), bankNumber = VALUES(bankNumber);

			INSERT INTO representative(id, companyId, name) VALUES (".$data[3][6].",LAST_INSERT_ID(),'".$data[3][5]."') 
			ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), companyId = VALUES(companyId), name = VALUES(name);

			Set @senderId = LAST_INSERT_ID();
			
			INSERT INTO company(id, name, regNumber, location, address, bankNumber) VALUES (".$data[4][7].",'".$data[4][0]."',
				'".$data[4][1]."','".$data[4][2]."','".$data[4][3]."','".$data[4][4]."') 
			ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), name = VALUES(name), regNumber = VALUES(regNumber), location = VALUES(location), 
				address = VALUES(address), bankNumber = VALUES(bankNumber);

			INSERT INTO representative(id, companyId, name) VALUES (".$data[4][6].",LAST_INSERT_ID(),'".$data[4][5]."') 
			ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), companyId = VALUES(companyId), name = VALUES(name);

			Set @receiverId = LAST_INSERT_ID();
			
			INSERT INTO registry(id, senderId, receiverId, docNumber, prescriptionDate, receptionDate, paymentDate, transport) 
				VALUES (".$data[0].",@senderId, @receiverId, '".$data[1]."','".$data[2][0]."','".$data[2][1]."','".$data[2][2]."',".$data[5].") 
			ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), senderId = VALUES(senderId), receiverId = VALUES(receiverId), 
			docNumber = VALUES(docNumber), prescriptionDate = VALUES(prescriptionDate), receptionDate = VALUES(receptionDate), 
			paymentDate = VALUES(paymentDate), transport = VALUES(transport);
			
			Set @registryId = LAST_INSERT_ID();";
	
	foreach ($data[6] as $row){
		$sql .= "INSERT INTO productgroup(id, name, tax) VALUES (".floatval($row['productGroupId']).",'".$row['itemGroup']."',".floatval($row['tax']).")
				ON DUPLICATE KEY UPDATE tax = VALUES(tax), id=LAST_INSERT_ID(id);
				
				INSERT INTO product(id, productGroupId, name, barcode) VALUES (".floatval($row['productId']).",LAST_INSERT_ID(),'".$row['name']."','".$row['barcode']."')
				ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), productGroupId = VALUES(productGroupId), name = VALUES(name), barcode = VALUES(barcode);
				
				INSERT INTO item(id, productId, serNumber, incomingPrice, quantity) VALUES (".floatval($row['id']).",LAST_INSERT_ID(), '".$row['serNumber']."', 
				".floatval($row['priceIn']).", ".intval($row['amount']).")
				ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), productId = VALUES(productId), serNumber = VALUES(serNumber), incomingPrice = VALUES(incomingPrice),
				quantity = VALUES(quantity);
				
				INSERT INTO items(registryId, itemId) VALUES (@registryId,LAST_INSERT_ID())
				ON DUPLICATE KEY UPDATE registryId=registryId;";
	}
	
	if (mysqli_multi_query($conn, $sql)) {
		echo "Veiksmīgi saglabāts";
	} else {
		echo "Kļūda!";
	}

	mysqli_close($conn);
?>