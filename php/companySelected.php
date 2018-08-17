<?php
$id = intval($_GET['id']);

$conn = mysqli_connect("localhost", "root", "", "meansdb");

if($id > 0){
	$sql = "SELECT name, regNumber, location, address, bankNumber FROM company WHERE id=$id;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	echo "<div class='container'>
				<input type='hidden' name='companyId' class='companyId' value='$id'/><input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value='".$row["name"]."'/>
				<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value='".$row["regNumber"]."'/>
				<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value='".$row["location"]."'/>
				<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value='".$row["address"]."'/>
				<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value='".$row["bankNumber"]."'/>
				<input type='hidden' name='representativeId' class='representativeId' value='0'/><input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' list='Representatives$id' value=''/>
			</div>";
}
else{
	$id = $id * -1;
	$sql = "SELECT company.id, representative.name, company.name as companyName, regNumber, location, address, bankNumber FROM representative
			INNER JOIN company on company.id = representative.companyId
			WHERE representative.id=$id;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	echo "<div class='container'>
				<input type='hidden' name='companyId' class='companyId' value='".$row["id"]."'/><input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value='".$row["companyName"]."'/>
				<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value='".$row["regNumber"]."'/>
				<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value='".$row["location"]."'/>
				<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value='".$row["address"]."'/>
				<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value='".$row["bankNumber"]."'/>
				<input type='hidden' name='representativeId' class='representativeId' value='$id'/><input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' list='Representatives$id' value='".$row["name"]."'/>
			</div>";
}

$sql = "SELECT id, name FROM representative WHERE representative.companyId = $id";
$RepresentativeList = mysqli_query($conn, $sql);
$representatives = "<datalist id='Representatives$id'>";
while($row = mysqli_fetch_assoc($RepresentativeList)) {
	$representatives .= "<option value='".$row["name"]."' data-id='".$row["id"]."'>";
}
$representatives .=	"</datalist>";
echo $representatives;
		
mysqli_close($conn);

?>