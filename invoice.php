<!DOCTYPE html>
<html>
	<head>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="js/jquery.json.min.js"></script>
		<script src="js/invoiceJs.js"></script>
		<link rel="stylesheet" href="css/style.css" />	
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<?php
			include "php/moreInvoice.php";
			session_start();
			$id = $_POST['id'];
		?>
	</head>
	<?php
		echo "<body onload='start($id)'>"
	?>
	
		<a href="registry.php"><h2 style="text-align:center;">Pavadzīme</h2></a>
		<!-- company -->
		<?php
			getCompanyDetails($id)
		?>

		<!-- Product -->
		<button class="accordion" id="product">Prece</button>
		<?php
			getProducts($id);
		?>
		
	</body>
</html>