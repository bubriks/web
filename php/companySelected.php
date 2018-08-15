<?php
$id = intval($_GET['id']);

$conn = mysqli_connect("localhost", "root", "", "meansdb");

$sql = "SELECT name FROM representative WHERE representative.companyId = $id";
$RepresentativeList = mysqli_query($conn, $sql);
$representatives = "<datalist id='Representatives$id'>";
while($row = mysqli_fetch_assoc($RepresentativeList)) {
	$representatives .= "<option value='".$row["name"]."'>";
}
$representatives .=	"</datalist>";
echo $representatives;

$sql = "SELECT representative.name as representative, company.name, company.regNumber, company.location, company.address, company.bankNumber 
			FROM representative INNER JOIN company ON representative.companyId = company.id WHERE representative.id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo "<div class='container'>
			<input type='text' name='title' class='title' placeholder='Kompānijas nosaukums' list='Companies' value='".$row["name"]."'/>
			<input type='text' name='reNumber' class='reNumber' placeholder='Kompānijas reģistrācijas numurs' value='".$row["regNumber"]."'/>
			<input type='text' name='location' class='location' placeholder='Kompānijas juridiskā adrese' value='".$row["location"]."'/>
			<input type='text' name='address' class='address' placeholder='Kompānijas faktiskā adrese' value='".$row["address"]."'/>
			<input type='text' name='bank' class='bank' placeholder='Kompānijas konta numurs' value='".$row["bankNumber"]."'/>
			<input type='text' name='representative' class='representative' placeholder='Kompānijas pārstāvis' list='Representatives$id' value='".$row["representative"]."'/>
		</div>";

mysqli_close($conn);

?>