<?php
$barcode = intval($_GET['barcode']);

$conn = mysqli_connect("localhost", "root", "", "meansdb");

$sql = "SELECT id, productGroupId, name, barcode  FROM product WHERE barcode = '$barcode'";
$product = mysqli_fetch_assoc(mysqli_query($conn, $sql));

echo "".$product["id"]."|".$product["productGroupId"]."|".$product["name"]."";

mysqli_close($conn);
	
?>