<?php //Google Maps Dinâmico
	include_once "includes/seguranca.php";

	If ((!isset($_POST["latitude"])) AND (!isset($_POST["longitude"]))) {
		echo "<img width='549' height='388' src='css/images/mapa.jpg' alt='SGCA' style='border: 1px solid black;' />";
	} else {
		$lat = $_POST["latitude"];
		$long = $_POST["longitude"];
		echo "<iframe width='549' height='388' style='border: 1px solid black' src='http://maps.google.com.br/maps?hl=pt-BR&ll=$lat,$long&z=16&output=embed'></iframe>";
	}
?>