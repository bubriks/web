<!DOCTYPE html>
<html>
	<head>
		<script src="js/jquery-3.3.1.min.js"></script><!-- jquery -->
		<script src="js/jquery.json.min.js"></script><!-- JSON -->
		<script src="js/invoiceJs.js"></script>
		<link rel="stylesheet" href="css/style.css" />	
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