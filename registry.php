<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" />	
		<script src="js/registryJs.js"></script>
		<?php
			include "php/moreRegistry.php";
			session_start();
			$_SESSION["id"] = 1;
		?>
	</head>
	<body onload="start()">
		<h2 style="text-align:center;">Preču pavadokumentu reģistrācijas žurnāls</h2>
		
		<button class="accordion">Meklēt</button>
		<?php
			echo $search;
		?>
		
		<div class='container'>
			<button onclick="location.href = 'invoice.php';" class="buttonAdd">+</button>
			<?php
				echo $documentTable;
			?>
		</div>
	</body>
</html>
