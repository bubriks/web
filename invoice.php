<!DOCTYPE html>
<html>
	<head>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="js/invoiceJs.js"></script>
		<link rel="stylesheet" href="css/style.css" />	
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<?php
			include "php/moreInvoice.php";
			session_start();
		?>
	</head>
	<body onload="start()">
	
		<h2 style="text-align:center;">Pavadzīme</h2>
		<!-- company -->
		<?php
			getCompanyDetails($_SESSION["id"])
		?>

		<!-- Product -->
		<button class="accordion" id="product">Prece</button>
		<?php
			getProducts($_SESSION["id"]);
		?>
		
		<button class="buttonSave" onclick="location.href = 'registry.php';">Apstiprināt</button>
		
		<button class="buttonDelete" onclick="location.href = 'registry.php';">Dzēst</button>
		
	</body>
</html>