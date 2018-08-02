<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" />	
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
		
		<div class='container'>
			<button onclick="location.href = 'invoice.php';" class="buttonAdd">+</button>
			<?php
				echo $documentTable;
			?>
		</div>
	</body>
</html>

<script>
	function start(){
		var acc = document.getElementsByClassName("accordion");

		for (var i = 0; i < acc.length; i++) {
			acc[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var panel = this.nextElementSibling;
				if (panel.style.display === "block") {
					panel.style.display = "none";
				} else {
					panel.style.display = "block";
				}
			})
		};
		
		document.getElementById("endDate").valueAsDate = new Date();
	}
</script>
