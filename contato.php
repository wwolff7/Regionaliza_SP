<?php
	header("Content-Type: text/html;  charset=UTF8",true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Regionalização de Vazões - São Paulo</title>
	<meta name="description" content="No Estado de São Paulo (SP), estudos hidrológicos referentes a outorga de recursos hídricos são dependentes da regionalização hidrológica, para assim calcular as vazões em rios que não dispõe de dados, obtendo a curva de permanência por meio da regionalização de vazões."/>	
	<meta name='keywords' content='regionalização hidrológica, regionalização de vazões, São Paulo SP, estudos hidrológicos, outorga, recursos hídricos, calcular as vazões, calculo de vazão, curva de permanência, contato' />
	<meta name='author' content='www.leb.esalq.usp.br/wolff/rv/' />	
	<meta name='robots' content='index,follow' />
	<meta content="pt-br" http-equiv="Content-Language" />
	<meta name="geo.region" content="BR-SC" />
	<link rel="shortcut icon" href="css/images/mapa.GIF"/>	
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<script type="text/javascript" src="js/mascara_valor.js"></script>
	<script type="text/javascript" src="libraries/jquery.min.js" ></script>
	<!--[if lt IE 10]>
		<link rel="stylesheet" href="css/atualizar2.css" type="text/css" />
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
					<li ><span><a href="index.php">Início</a></span></li>
					<li class="active"><span><a href="contato.php">Contato</a></span></li>
				</ul>
			</div>
		</div>
		<div class="main">
			<span class="shadow-top"></span>
			<div class="shell">
				<div class="container">
					<section class="testimonial">
						<h2>Contato</h2>
						<p> 
							Favor preencher os dados pessoais e deixar a sua mensagem. 
							Após escrever nos campos abaixo, clique no botão "Enviar".
						</p>
					</section>
					<section class="blog">
						<p><?php include("enviar.php")?></p>
						<form action="contato.php" method="post" class="content" style="width: 1024px;">
							<div class="regularizacao">
								<div class="left2"><br />
									<input type="text" id="nome" name="nome" required autofocus value="<?php echo $nome;?>" />
									<label>Nome: </label>
									<img width="30" height="30" src="css/images/pessoa.png" class="imgContato" alt="pessoa" />																
								</div>
								<div class="left2"><br />
									<input type="text" name="email" id="email" required value="<?php echo $email;?>" />			
									<label>Email: </label>
									<img width="30" height="30" src="css/images/email.png" class="imgContato" alt="email" />
								</div>
								<div class="left2"><br />
									<input type="text" id="assunto" name="assunto" required value="<?php echo $assunto_user;?>" />
									<label>Assunto: </label>
									<img width="30" height="30" src="css/images/tel.png" class="imgContato" alt="assunto" />								
								</div>
								<textarea id="mensagem" name="mensagem" ><?php echo $mensagem;?></textarea>
								<input name="enviar" class="enviar" type="submit" required value=" " style="margin-right: 48px; margin-top: 30px;"/>
							</div>
						</form>
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