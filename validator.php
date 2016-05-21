<?php 
	header("Content-Type: text/html;  charset=UTF8",true);
?>
<?php
	$latitude = trim(@$_POST['latitude']);
	$longitude = trim(@$_POST['longitude']);
	$area = trim(@$_POST['area']);

	$erros = 1;
	$enviar = @$_POST['calcular'];
	if($enviar){
		if(floatval($latitude == "")){
			$erros++;
			echo "<small class=\"erro\">*** Favor verificar o campo Latitude.</small><br />";
		}
		if(floatval($longitude == "")){
			$erros++;
			echo "<small class=\"erro\">*** Favor verificar o campo Longitude.</small><br />";
		}
		if(floatval($area == "")){
			$erros++;
			echo "<small class=\"erro\">*** Favor verificar o campo &Aacuterea.</small><br />";
		}
		if ($erros <= 1) {
			$latitude = floatval($latitude);
			$longitude = floatval($longitude);
			$area = floatval($area);
			header("Location: resultado.php?lat=$latitude&long=$longitude&area=$area");	
		}
	}
?>