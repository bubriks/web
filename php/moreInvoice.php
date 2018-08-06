<?php

function getCompanyDetails($id){
	if($id!=0){
		$conn = mysqli_connect("localhost", "root", "", "meansdb");
		
		$sql = "SELECT senderId, receiverId, docNumber, prescriptionDate, receptionDate, paymentDate FROM registry WHERE id=$id";
		
		$result = mysqli_query($conn, $sql);
		mysqli_close($conn);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			
			$sender = companyDetails('divSender',$row["senderId"]);
			$receiver = companyDetails('divReceiver',$row["receiverId"]);
			
			echo "<!-- Number of document -->
				<button class='accordion' id='docNumber' onclick='documentClick()'>Dokuments</button>
				<div class='panel'>
					<div class='container'>
						<input type='text' id='number' placeholder='Dokumenta numurs' value='".$row["docNumber"]."'>
						<input type='file' accept='.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'/>
						<button class='buttonDownload' style='float: right;'><i class='fa fa-download'></i> Lejuplādēt</button>
					</div>
				</div>
				
				<!-- Date -->
				<button class='accordion' id='date' onclick='dateClick()'>Datums</button>
				<div class='panel'>
					<div class='container'>
						<input type='text' id='preDate' placeholder='Izrakstīšanas datums' value='".$row["prescriptionDate"]."'>
						<input type='text' id='recDate' placeholder='Saņemšanas datums' value='".$row["receptionDate"]."'>
						<input type='text' id='paymentDate' placeholder='Apmaksas datums' value='".$row["paymentDate"]."'>
					</div>
				</div>
				
				<!-- Deliverer -->
				<button class='accordion' id='sender' onclick=\"companyClick('divSender')\">Preču piegādātājs</button>
				$sender

				<!-- Receiver -->
				<button class='accordion' id='receiver' onclick=\"companyClick('divReceiver')\">Preču saņēmējs</button>
				$receiver";
		}
	}
	else{
		$sender = companyDetails('divSender',0);
		$receiver = companyDetails('divReceiver',0);
		
		echo "<!-- Number of document -->
			<button class='accordion' id='docNumber' onclick='documentClick()'>Dokuments</button>
			<div class='panel'>
				<div class='container'>
					<input type='text' id='number' placeholder='Dokumenta numurs'>
					<input type='file' accept='.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'/>
					<button class='buttonDownload' style='float: right;'><i class='fa fa-download'></i> Lejuplādēt</button>
				</div>
			</div>
			
			<!-- Date -->
			<button class='accordion' id='date' onclick='dateClick()'>Datums</button>
			<div class='panel'>
				<div class='container'>
					<input type='text' id='preDate' placeholder='Izrakstīšanas datums'>
					<input type='text' id='recDate' placeholder='Saņemšanas datums'>
					<input type='text' id='paymentDate' placeholder='Apmaksas datums'>
				</div>
			</div>
			
			<!-- Deliverer -->
			<button class='accordion' id='sender' onclick=\"companyClick('divSender')\">Preču piegādātājs</button>
			$sender

			<!-- Receiver -->
			<button class='accordion' id='receiver' onclick=\"companyClick('divReceiver')\">Preču saņēmējs</button>
			$receiver";
	}
}

function companyDetails($panelId,$id) {
	if($id!=0){
		$conn = mysqli_connect("localhost", "root", "", "meansdb");
		
		$sql = "SELECT representative.name as representative, company.name, company.regNumber, company.location, company.address, company.bankNumber 
				FROM representative INNER JOIN company ON representative.companyId = company.id WHERE representative.id=$id";

		$result = mysqli_query($conn, $sql);
		mysqli_close($conn);
		
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			
			return 
				"<div class='panel' id = '$panelId'>
					<div class='container'>
						<input type='text' id='title' placeholder='Kompānijas nosaukums' value='".$row["name"]."'/>
						<input type='text' id='reNumber' placeholder='Kompānijas reģistrācijas numurs' onkeypress='return isNumberKey(event)' value='".$row["regNumber"]."'/>
						<input type='text' id='location' placeholder='Kompānijas juridiskā adrese' value='".$row["location"]."'/>
						<input type='text' id='address' placeholder='Kompānijas faktiskā adrese' value='".$row["address"]."'/>
						<input type='text' id='bank' placeholder='Kompānijas konta numurs' onkeypress='return isNumberKey(event)' value='".$row["bankNumber"]."'/>
						<input type='text' id='representative' placeholder='Kompānijas pārstāvis' value='".$row["representative"]."'/>
					</div>
				</div>";
		}
	}
	else{
		return "<div class='panel' id = '$panelId'>
				<div class='container'>
					<input type='text' id='title' placeholder='Kompānijas nosaukums'/>
					<input type='text' id='reNumber' placeholder='Kompānijas reģistrācijas numurs' onkeypress='return isNumberKey(event)'/>
					<input type='text' id='location' placeholder='Kompānijas juridiskā adrese'/>
					<input type='text' id='address' placeholder='Kompānijas faktiskā adrese'/>
					<input type='text' id='bank' placeholder='Kompānijas konta numurs' onkeypress='return isNumberKey(event)'/>
					<input type='text' id='representative' placeholder='Kompānijas pārstāvis'/>
				</div>
			</div>";
	}
}
		
function getProducts($id){
	if($id!=0){
		$conn = mysqli_connect("localhost", "root", "", "meansdb");
		
		$sql = "SELECT product.name, product.barcode, item.serNumber, productgroup.name as groupName, item.incomingPrice, productgroup.tax FROM items 
				INNER JOIN item ON items.itemId = item.id 
				INNER JOIN product ON item.productId = product.id 
				INNER JOIN productgroup ON product.productGroupId = productgroup.id WHERE items.registryId = $id";

		$result = mysqli_query($conn, $sql);
		
		$sql = "SELECT name, tax FROM productgroup";

		$list = mysqli_query($conn, $sql);
		
		mysqli_close($conn);
		
		if (mysqli_num_rows($result) > 0) {
			
			$products = 
			"<div class='panel'>
				<div class='container'>
					<table id='productTable'>
						<thead>
							<tr>
								<th>Darbība</th>
								<th>Nosaukums</th>
								<th>Svītrkods</th>
								<th>Seriāla numurs</th>
								<th>Preču grupa</th>
								<th>Daudzums</th>
								<th>Ienākoša cena</th>
								<th>PVN</th>
								<th>Summa</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td><button onclick='addRow()' class='buttonAdd'>+</button></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><input type='text' class='total' name='total' placeholder='0.00' readonly/></td>
							</tr>
						</tfoot>
					<tbody>";
			
			while($row = mysqli_fetch_assoc($result)) {
				 $products .= "<tr>
								<td><button class='buttonDelete' style='border-radius: 4px;' onclick='DeleteRow(this);'>Dzēst</button></td>
								<td><input type='text' class='name' name='name' placeholder='Nosaukums' value='".$row["name"]."'/></td>
								<td><input type='text' class='barcode' name='barcode' placeholder='Svītrkods' value='".$row["barcode"]."'/></td>
								<td><input type='text' class='serNumber' name='serNumber' placeholder='Seriāla numurs' value='".$row["serNumber"]."'/></td>
								<td><input type='text' class='itemGroup' name='itemGroup' placeholder='Preču grupa' list='prodGroups' value='".$row["groupName"]."'/></td>
								<td><input type='text' class='amount' name='amount' placeholder='Daudzums' onkeypress='return isNumberKey(event)' value='1'/></td>
								<td><input type='text' class='priceIn' name='priceIn' placeholder='Ienākoša cena' onkeypress='return isNumberKey(event)' value='".$row["incomingPrice"]."'/></td>
								<td><input type='text' id='tax' class='tax' name='tax' placeholder='PVN' onkeypress='return isNumberKey(event)' value='".$row["tax"]."'/></td>
								<td><input type='text' class='subTotal' name='subTotal' placeholder='Summa' readonly/></td>
							</tr>";
			}
			
			$products .= "</tbody>
						</table>
					</div>
				</div>";
			
			echo $products;
			
			$groups = "<datalist id='prodGroups'>";
			while($row = mysqli_fetch_assoc($list)) {
				$groups .= "<option value='".$row["name"]."' label = '".$row["tax"]."'  data-xyz = '".$row["tax"]."' >";
			}
			$groups .=	"</datalist>";
			echo $groups;
		}
	}
	else{
		$products = 
			"<div class='panel'>
				<div class='container'>
					<table id='productTable'>
						<thead>
							<tr>
								<th>Darbība</th>
								<th>Nosaukums</th>
								<th>Svītrkods</th>
								<th>Seriāla numurs</th>
								<th>Preču grupa</th>
								<th>Daudzums</th>
								<th>Ienākoša cena</th>
								<th>PVN</th>
								<th>Summa</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td><button onclick='addRow()' class='buttonAdd'>+</button></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><input type='text' class='total' name='total' placeholder='0.00' readonly/></td>
							</tr>
						</tfoot>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>";
		echo $products;
	};
}	
?>