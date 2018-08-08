<?php
$search =
	"<div class='panel'>
		<div class='container'>
			No:<input type='date' id='startDate' value='2000-01-01' style='resize:none'>
			Līdz:<input type='date' id='endDate' style='resize:none'>
			Kompānijas nosaukums: <input type='text' list='companies' placeholder='Kompānijas'/>
			<datalist id='companies'>
				<option value='Means'>
				<option value='Lattelecom'>
				<option value='Grindex'>
				<option value='Nasa'>
			</datalist>
		</div>
	</div>";
	
function getRegistry(){
	$conn = mysqli_connect("localhost", "root", "", "meansdb");
	
	$sql = "SELECT registry.id, receptionDate, company.name, company.bankNumber, paymentDate, docNumber, transport,
			SUM(item.incomingPrice) as incomingPrice, SUM(item.incomingPrice * (productgroup.tax / 100)) as tax FROM registry
			INNER JOIN representative on representative.id = registry.senderId
			INNER JOIN company on company.id = representative.companyId
			INNER JOIN items on items.registryId = registry.id
			INNER JOIN item on item.id = items.itemId
			INNER JOIN product on product.id = item.productId
			INNER JOIN productgroup on productgroup.id = product.productGroupId";

	$result = mysqli_query($conn, $sql);
	
	mysqli_close($conn);
	
	if (mysqli_num_rows($result) > 0) {
		
		$registries = "<div class='container' style='padding: 0px;'>
			<table id='ducuments'>
				<thead>
					<tr>
						<th>Datums</th>
						<th>PP-R. norādītais darījuma partneris</th>
						<th>Citi rekvizīti</th>
						<th>Datums</th>
						<th>Sērija</th>
						<th>Numurs</th>
						<th>Darijuma vērtība bez PVN</th>
						<th>Transporta izdevumi</th>
						<th>Atlaide</th>
						<th>PVN summa</th>
						<th>Kopējā summa</th>
						<th>Papildus</th>
					</tr>
				</thead>
				<tbody>";
		
		while($row = mysqli_fetch_assoc($result)) {
			$arr = explode(" ", $row["docNumber"], 2);
			$ser = $arr[0];
			$num = $arr[1];
			
			$registries .= "<tr>
								<td><input type='text' value='".$row["receptionDate"]."' readonly/></td>
								<td><input type='text' value='".$row["name"]."' readonly/></td>
								<td><input type='text' value='".$row["bankNumber"]."' readonly/></td>
								<td><input type='text' value='".$row["paymentDate"]."' readonly/></td>
								<td><input type='text' value='$ser' readonly/></td>
								<td><input type='text' value='$num' readonly/></td>
								<td><input type='text' value='".$row["incomingPrice"]."' readonly/></td>
								<td><input type='text' value='".$row["transport"]."' readonly/></td>
								<td><input type='text' readonly/></td>
								<td><input type='text' value='".$row["tax"]."' readonly/></td>
								<td><input type='text' value='".($row["incomingPrice"] + $row["tax"])."' readonly/></td>
								<td><form action='invoice.php' method='post'>
									<input type='hidden' name='id' value='".$row["id"]."'/>
									<input type='submit' name='submit' value='Lasīt vairāk' onclick='readMore()' class='buttonAdd'/>
									</form>
								</td>
							</tr>";
		}
		
		$registries .= "</tbody>
				</table>
			</div>";
	}
	else{
		$registries = "<div class='container' style='padding: 0px;'>
			<table id='ducuments'>
				<thead>
					<tr>
						<th>Datums</th>
						<th>PP-R. norādītais darījuma partneris</th>
						<th>Citi rekvizīti</th>
						<th>Datums</th>
						<th>Sērija</th>
						<th>Numurs</th>
						<th>Darijuma vērtība bez PVN</th>
						<th>Transporta izdevumi</th>
						<th>Atlaide</th>
						<th>PVN summa</th>
						<th>Kopējā summa</th>
						<th>Papildus</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>";
	};
	echo $registries;
}
?>