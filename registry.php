<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" />
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/registryJs.js"></script>
		<?php
			include "php/moreRegistry.php";
		?>
	</head>
	<body onload="start()">
		<h2 style="text-align:center;">Preču pavadokumentu reģistrācijas žurnāls</h2>
		
		<button class="accordion">Meklēt</button>
		<?php
			echo $search;
		?>
		
		<div class="container">
			<form action="invoice.php" method="post">
				<input type="hidden" name="id" value="0"/>
				<input type="submit" name="submit" value="Pievienot Jaunu" onclick="readMore()"class="buttonAdd"/>
			</form>
			<?php
				echo getRegistry();
			?>
		</div>
	</body>
</html>