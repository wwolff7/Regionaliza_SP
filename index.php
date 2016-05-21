<?php ob_start(); 
	header("Content-Type: text/html;  charset=UTF8",true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Regionalização de Vazões - São Paulo</title>
	<meta name="description" content="No Estado de São Paulo (SP), estudos hidrológicos referentes a outorga de recursos hídricos são dependentes da regionalização hidrológica, para assim calcular as vazões em rios que não dispõe de dados, obtendo a curva de permanência por meio da regionalização de vazões."/>	
	<meta name='keywords' content='regionalização hidrológica, regionalização de vazões, São Paulo SP, estudos hidrológicos, outorga, recursos hídricos, calcular as vazões, calculo de vazão, curva de permanência' />
	<meta name='author' content='www.leb.esalq.usp.br/wolff/rv/' />	
	<meta name='robots' content='index,follow' />
	<meta content="pt-br" http-equiv="Content-Language" />
	<meta name="geo.region" content="BR-SC" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />	
	<link rel="shortcut icon" href="css/images/mapa.GIF"/>	
	<script type="text/javascript" src="js/mascara_valor.js"></script>
	<script type="text/javascript" src="libraries/jquery.min.js" ></script>
	<script type="text/javascript" src="js/jquery-1.8.0.min.js" ></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCvEkqXtacmaSNQ9rerabpHggQUDzfFPzQ&sensor=true"></script>
    <script type="text/javascript" >
		$(document).ready(function(){	
			$('#longitude').keyup(function(){
				var latitude = $('#latitude').val();
				var longitude = $('#longitude').val();
				if(!latitude == "" & !longitude == ""){					
					$('.contentTwo').remove();
					$('.contentThree').show();
					var map = null; 
					var latlng = new google.maps.LatLng(latitude,longitude);
					var myOptions = {
					zoom: 14,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.SATELLITE
					};
					map = new google.maps.Map(document.getElementById("contentThree"), myOptions);
					var Ponto = new google.maps.LatLng(latitude, longitude);
					marcadorPonto = new google.maps.Marker({
						position: Ponto,
						map: map,
						title:"Ponto desejado - Latitude: " + latitude + " Longitude: " + longitude,
					});
				}
			});
		});
	</script>
	<!--[if lt IE 10]>
		<link rel="stylesheet" href="css/atualizar.css" type="text/css" />
	<![endif]-->
</head>
<body>
	<div id="wrapper">
		<div class="topogeral">    
			<div class="topocentralizado">  
				<div class="topoesquerda">
					<a href="http://www.usp.br/" class="left"><img width="140" height="85" src="css/images/logo-usp.png" alt="USP" style="border:0;" /></a>
					<a href="http://www.esalq.usp.br/" class="left"><img width="60" height="80" src="css/images/esalq.jpg" alt="SGCA" style="border:0;" /></a>
					<a href="http://www.leb.esalq.usp.br/" class="left"><img width="220" height="85" src="css/images/eng.png" alt="ENGENHARIA DE BIOSSISTEMAS" style="border:0;" /></a>
				</div>
				<div class="topocentro">
					<p><b>Regionalização de Vazões</b><br />São Paulo</p>
				</div>
			</div>
		</div>
		<div class="top-nav">
			<div class="shell">
				<a href="#" class="nav-btn">Regionalização de Vazões<span></span></a>
				<span class="top-nav-shadow"></span>
				<ul>
					<li class="active"><span><a href="index.php">Início</a></span></li>
					<li><span><a href="contato.php">Contato</a></span></li>				
				</ul>
			</div>
		</div>
		<div class="main">
			<div class="shell">
				<div class="container">
					<section class="testimonial">
						<h2>Regionalização de Vazões</h2>
						<p> A regionalização hidrológica é uma técnica que permite transferir informação entre bacias hidrográficas semelhantes, 
							a fim de calcular em sítios que não dispõe de dados, as variáveis hidrológicas de interesse; 
							assim, a mesma caracteriza-se por ser uma ferramenta útil na gestão dos recursos hídricos, 
							principalmente no que diz respeito ao terceiro instrumento da Lei 9433/97 (BRASIL, 1997), 
							a outorga de direito de uso de recursos hídricos.
						</p>
						<p>
							Esse modelo é produto da Dissertação de Mestrado, intitulado de 
							<a href="http://www.teses.usp.br/teses/disponiveis/11/11152/tde-08042013-102503/" 
							title="Avaliação e nova proposta de regionalização hidrológica para o Estado de São Paulo">  
							 "Avaliação e nova proposta de regionalização hidrológica para o Estado de São Paulo"</a>, 
							feito pelo aluno Wagner Wolff com orientação do professor Sergio Nascimento Duarte, 
							no programa de Engenharia de Sistemas Agrícolas da ESALQ/USP.
						</p>
					</section>
					<div class="conteudoie">
						<div id="ie">
							<div class="meio">
								<div class="titulo">O seu navegador n&atilde;o &eacute; mais suportado.<br /><br /><span>Atualize o seu navegador clicando no ícone abaixo.</span>
								</div>
								<div class="sugestoes">
									<a href="http://br.mozdev.org/"><div class="nav1"></div></a>
									<a href="http://www.google.com/chrome"><div class="nav2"></div></a>
									<a href="http://www.apple.com/safari/download/"><div class="nav3"></div></a>
									<a href="http://windows.microsoft.com/pt-BR/internet-explorer/products/ie/home"><div class="nav4"></div></a>
									<a href="http://www.opera.com/download/"><div class="nav5"></div></a>
								</div>
							</div>
						</div>
					</div>
					<section class="blog">
						<p><?php include("validator.php"); ?></p>
						<form action="index.php" method="post" class="content">
							<br /><p>Dados de Entrada</p><br /><br />
							<div>
								<input type="text" id="latitude" maxlength="8" name="latitude" value="<?php echo $latitude;?>" title="Insira a Latitude desejada. Exemplo: -21.4475" onKeyPress="return Apenas_Numeros(event);" />	
								<label for="latitude">Latitude: </label>
							</div><br />
							<div>
								<input type="text" name="longitude" maxlength="8" id="longitude" value="<?php echo $longitude;?>" title="Insira a Longitude desejada. Exemplo: -50.4305" onKeyPress="return Apenas_Numeros(event);" />			
								<label for="longitude">Logitude: </label>
							</div><br />
							<div>
								<input type="text" id="area" name="area" value="<?php echo $area;?>" title="Insira a Área da bacia hidrográfica. Exemplo: 127.001" onKeyPress="return Apenas_Numeros2(event);" />		
								<label for="area">Área (km²): </label>
							</div>
							<input type="submit" name="calcular" id="calcular" class="calcular" value=" " />
						</form>
						<div class="contentTwo">
							<img width="549" height="388" src="css/images/mapa.jpg" alt="SGCA" style="border: 1px solid black;" />						
						</div>
						<div class="contentThree" id="contentThree"></div>
						<div class="cl">&nbsp;</div>
					</section>	
				</div>
			</div>
		</div>
		<div class="cl">&nbsp;</div>
	</div>	
	<div class="footer-bottom">
		<div class="shell">
			<div class="footer-nav">
				<p class="copy"><a href="index.php" class="nav-btn">Início</a><span>|</span><a href="contato.php" class="nav-btn">Contato</a></p>
				<p class="copy">&copy; Copyright 2013<span>|</span>Regionalização de Vazões</p>
				<p class="copy">Desenvolvido por <a href="contato.php">Wagner Wolff</a> e <a href="contato.php">Diego de Liz</a></p>
			</div>
		</div>
	</div>
</body>
</html>