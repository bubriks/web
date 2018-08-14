<?php

function getCompanyDetails($id){
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "SELECT id, name FROM company";
	$companyList = mysqli_query($conn, $sql);
	$companies = "<datalist id='Companies'>";
	while($row = mysqli_fetch_assoc($companyList)) {
		$companies .= "<option value='".$row["name"]."' data-id = '".$row["id"]."'>";
	}
	$companies .=	"</datalist>";
	echo $companies;
	
	if($id!=0){
		$sql = "SELECT senderId, receiverId, docNumber, prescriptionDate, receptionDate, paymentDate FROM registry WHERE id=$id";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		
		$sender = companyDetails('divSender',$row["senderId"],$conn);
		$receiver = companyDetails('divReceiver',$row["receiverId"],$conn);
		
		$companyCode = "<!-- Number of document -->
			<input type='button' class='accordion' name='docNumber' value='Dokuments'>
			<div class='panel'>
				<div class='container' id='doc'>
					<input type='text' class='number' name='number' placeholder='Dokumenta numurs' value='".$row["docNumber"]."'>
					<input type='file' accept='.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'/>
					<button class='buttonDownload' style='float: right;'><i class='fa fa-download'></i> Lejuplādēt</button>
				</div>
			</div>
			
			<!-- Date -->
			<input type='button' class='accordion' name='date' value='Datums'>
			<div class='panel'>
				<div class='container' id='dates'>
					<input type='date' name='preDate' class='preDate' placeholder='Izrakstīšanas datums' value='".date('Y-m-d',strtotime($row["prescriptionDate"]))."'>
					<input type='date' name='recDate' class='recDate' placeholder='Saņemšanas datums' value='".date('Y-m-d',strtotime($row["receptionDate"]))."'>
					<input type='date' name='paymentDate' class='paymentDate' placeholder='Apmaksas datums' value='".date('Y-m-d',strtotime($row["paymentDate"]))."'>
				</div>
			</div>";
	}
	else{
		$sender = companyDetails('divSender',0,$conn);
		$receiver = companyDetails('divReceiver',0,$conn);
		
		$companyCode = "<!-- Number of document -->
			<input type='button' class='accordion' name='docNumber' value='Dokuments'>
			<div class='panel'>
				<div class='container' id='doc'>
					<input type='text' class='number' name='number' placeholder='Dokumenta numurs' value=''>
					<input type='file' accept='.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'/>
					<button class='buttonDownload' style='float: right;'><i class='fa fa-download'></i> Lejuplādēt</button>
				</div>
			</div>
			
			<!-- Date -->
			<input type='button' class='accordion' name='date' value='Datums'>
			<div class='panel'>
				<div class='container' id='dates'>
					<input type='date' name='preDate' class='preDate' placeholder='Izrakstīšanas datums'>
					<input type='date' name='recDate' class='recDate' placeholder='Saņemšanas datums'>
					<input type='date' name='paymentDate' class='paymentDate' placeholder='Apmaksas datums'>
				</div>
			</div>";
	}
	$companyCode .= "<!-- Deliverer -->
			<input type='button' class='accordion' name='sender' value='Preču piegādātājs'>
			$sender

			<!-- Receiver -->
			<input type='button' class='accordion' name='receiver' value='Preču saņēmējs'>
			$receiver";
	
	echo $companyCode;
	mysqli_close($conn);
}

function companyDetails($panelId,$id,$conn) {
	if($id!=0){
		$sql = "SELECT name FROM representative WHERE representative.companyId = $id";
		$RepresentativeList = mysqli_query($conn, $sql);
		$representatives = "<datalist id='Representatives'>";
		while($row = mysqli_fetch_assoc($RepresentativeList)) {
			$representatives .= "<option value='".$row["name"]."'>";
		}
		$representatives .=	"</datalist>";
		
		$sql = "SELECT representative.name as representative, company.name, company.regNumber, company.location, company.address, company.bankNumber 
				FROM representative INNER JOIN company ON representative.companyId = company.id WHERE representative.id=$id";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		
		$companyInfo = "<div class='panel' id = '$panelId'>
				<div class='container'>
					<input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value='".$row["name"]."'/>
					<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value='".$row["regNumber"]."'/>
					<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value='".$row["location"]."'/>
					<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value='".$row["address"]."'/>
					<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value='".$row["bankNumber"]."'/>
					<input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' list='Representatives' value='".$row["representative"]."'/>
				</div>
			</div>
			$representatives";
	}
	else{
		$companyInfo = "<div class='panel' id = '$panelId'>
				<div class='container'>
					<input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value=''/>
					<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value=''/>
					<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value=''/>
					<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value=''/>
					<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value=''/>
					<input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' value=''/>
				</div>
			</div>";
	}
	
	return $companyInfo;
}
		
function getProducts($id){
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "SELECT name, tax FROM productgroup";
	$list = mysqli_query($conn, $sql);
	
	$sql = "SELECT transport FROM registry WHERE $id";
	$transport = mysqli_query($conn, $sql);
	$added = mysqli_fetch_assoc($transport)["transport"];
	if($added == null){
		$added = 0;
	}

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
							<td colspan='2'><h3 style='text-align:right;'>Transporta izdevumi:</h3></td>
							<td><input type='text' class='transport' name='transport' placeholder='Transporta izdevumi' onkeypress='return isNumberKey(event,this.value)' value='$added'/></td>
							<td><h3 style='text-align:right;'>Summa:</h3></td>
							<td><input type='hidden' name='added' class='added' value='0'/><input type='text' class='total' name='total' placeholder='0.00' readonly/></td>
						</tr>
					</tfoot>
				<tbody>";
		
	if($id!=0){
		$sql = "SELECT product.name, product.barcode, item.serNumber, productgroup.name as groupName, item.incomingPrice, productgroup.tax, item.id, item.quantity FROM items 
				INNER JOIN item ON items.itemId = item.id 
				INNER JOIN product ON item.productId = product.id 
				INNER JOIN productgroup ON product.productGroupId = productgroup.id
				WHERE items.registryId = $id";
	
		$result = mysqli_query($conn, $sql);
			
		while($row = mysqli_fetch_assoc($result)) {
			 $products .= "<tr>
					<td><input type='hidden' name='id' class='id' value='".$row["id"]."'/><button class='buttonDelete' style='border-radius: 4px;' onclick='deleteRow(this);'>Dzēst</button></td>
					<td><input type='text' class='name' name='name' placeholder='Nosaukums' value='".$row["name"]."'/></td>
					<td><input type='text' class='barcode' name='barcode' placeholder='Svītrkods' value='".$row["barcode"]."'/></td>
					<td><input type='text' class='serNumber' name='serNumber' placeholder='Seriāla numurs' value='".$row["serNumber"]."'/></td>
					<td><input type='text' class='itemGroup' name='itemGroup' placeholder='Preču grupa' list='prodGroups' value='".$row["groupName"]."'/></td>
					<td><input type='text' class='amount' name='amount' placeholder='Daudzums' onkeypress='return isNumberKey(event,this.value)' value='".$row["quantity"]."'/></td>
					<td><input type='text' class='priceIn' name='priceIn' placeholder='Ienākoša cena' onkeypress='return isNumberKey(event,this.value)' value='".$row["incomingPrice"]."'/></td>
					<td><input type='text' class='tax' name='tax' placeholder='PVN' onkeypress='return isNumberKey(event,this.value)' value='".$row["tax"]."'/></td>
					<td><input type='text' class='subTotal' name='subTotal' placeholder='Summa' readonly/></td>
				</tr>";
		}
	}
	
	mysqli_close($conn);

	$products .= "</tbody>
				</table>
			</div>
		</div>";
	
	echo $products;
	
	$groups = "<datalist id='prodGroups'>";
	while($row = mysqli_fetch_assoc($list)) {
		$groups .= "<option value='".$row["name"]."' label = '".$row["tax"]."'  data-tax = '".$row["tax"]."' >";
	}
	$groups .=	"</datalist>
		<button class='buttonSave' onclick='saveInfo($id)'>Apstiprināt</button>
		<button class='buttonDelete' onclick='location.href = \"registry.php\";'>Dzēst</button>";
	echo $groups;
}	

?>