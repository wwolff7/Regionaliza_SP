<?php
	header("Content-Type: text/html;  charset=UTF8",true);
?>
<?php
	include_once "classes/crud.class.php";
	include_once "bd/crud_configuracao.php";
	include "includes/normal_cumulativa.php";
	include "includes/simpson.php";
	include "includes/inversa_normal_cumulativa.php";
	include_once "includes/seguranca.php";
	
	$id = antiInjection(trim($_POST["id"]));	
	$resultado = $crud->rawSelect("SELECT * FROM resultados WHERE ID = $id LIMIT 1");
	$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
	foreach($resultado as $rs){
		$sigma = $rs["SIGMA"];
		$mi = $rs["MI"];
		$gama = $rs["GAMA"];
		$q7_10 = $rs["Q7_10"];
		$area = $rs["AREA"];
		$qm = $rs["QM"];
	}
	
	$outorgada = antiInjection(trim($_POST["vOut"]));		
	$demandada = antiInjection(trim($_POST["vDem"]));	
	
	$qj = $q7_10 - $outorgada;
	$qf = $demandada + $qj;
	$auxQf = $qf/$area;
	$qfGama = $auxQf - $gama;
	$logNep = log($qfGama);
	$logNepMi = $logNep - $mi;
	$lnSigma = $logNepMi / $sigma;

	cumnormdist($lnSigma,$norm);
	$qfInt = 1 - $norm;

	simpsonsrule($qfInt, 0.9999, 100, $sigma, $mi, $gama, $area, $simp);
	$vReserv =((((1 - $qfInt) * $qf) - $simp) * 23 * 2592000) / pow(10, 6);
	
	//Simpson para criar a tabela da regularizacao, Tempo/Vr
	simpsonsrule($qfInt, 0.5, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){
		$vReserv2 =((((0.5 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv2 = number_format($vReserv2,4);
	} else {
		$vReserv2 = "*Inviável";
		$nota = "Nota: *Inviável - Para o período de retorno em questão, torna-se inviável a regularização de um volume, já que, a vazão disponível é maior que a vazão firme.";
	}
	simpsonsrule($qfInt, 0.75, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){	
		$vReserv4 =((((0.75 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv4 = number_format($vReserv4,4);
	} else {
		$vReserv4 = "*Inviável";
	}
	simpsonsrule($qfInt, 0.8, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){	
		$vReserv5 =((((0.8 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv5 = number_format($vReserv5,4);
	} else {
		$vReserv5 = "*Inviável";
	}
	simpsonsrule($qfInt, 0.9, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){		
		$vReserv10 =((((0.9 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv10 = number_format($vReserv10,4);
	} else {
		$vReserv10 = "*Inviável";
	}
	simpsonsrule($qfInt, 0.95, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){	
		$vReserv20 =((((0.95 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv20 = number_format($vReserv20,4);
	} else {
		$vReserv20 = "*Inviável";
	}
	simpsonsrule($qfInt, 0.98, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){		
		$vReserv50 =((((0.98 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv50 = number_format($vReserv50,4);
	} else {
		$vReserv50 = "*Inviável";
	}	
	simpsonsrule($qfInt, 0.99, 100, $sigma, $mi, $gama, $area, $simpvr2);
	if($simpvr2 >= 0){				
		$vReserv100 =((((0.99 - $qfInt) * $qf) - $simpvr2) * 31536000) / pow(10, 6);
		$vReserv100 = number_format($vReserv100,4);
	} else {
		$vReserv100 = "*Inviável";
	}	
	
	$resultado = $crud->rawSelect("SELECT prob_inv.PROB_INV FROM prob_inv");
	$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
	$i = 0;
	foreach($resultado as $rs){
		$prob_inv[$i] = $rs["PROB_INV"];
		
		inverse_ncdf($prob_inv[$i],$x);
		
		$inversa_nc[$i] = $x;
		$curvaPerm[$i] = ($gama + exp($mi + ($sigma * $inversa_nc[$i]))) * $area;
		$i++;
	}
				 
	$curva = join(",", array($curvaPerm[0],$curvaPerm[1],$curvaPerm[2],$curvaPerm[3],$curvaPerm[4],$curvaPerm[5],$curvaPerm[6],$curvaPerm[7],$curvaPerm[8],$curvaPerm[9],$curvaPerm[10],$curvaPerm[11],$curvaPerm[12],$curvaPerm[13],$curvaPerm[14],$curvaPerm[15],$curvaPerm[16],$curvaPerm[17],$curvaPerm[18],$curvaPerm[19],$curvaPerm[20],$curvaPerm[21],$curvaPerm[22]));
	$curva = "[$curva]";
	$teste = join(",", array($qf,$qf));
	$teste = "[$teste]";
	echo "<script>" . "\n";
	echo "var curva = $curva" . "\n";
	echo "var teste = $teste" . "\n";
	for ($i = 0; $i < 23; $i++) {
		echo "var curva$i = $curvaPerm[$i];";
	}
	echo "</script>"  . "\n";
							
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
    <script type="text/javascript" src="libraries/RGraph.common.core.js" ></script>
	<script type="text/javascript" src="libraries/RGraph.common.dynamic.js" ></script>
    <script type="text/javascript" src="libraries/RGraph.common.tooltips.js" ></script>
    <script type="text/javascript" src="libraries/RGraph.common.effects.js" ></script>	
    <script type="text/javascript" src="libraries/RGraph.common.key.js" ></script>	
    <script type="text/javascript" src="libraries/RGraph.line.js" ></script>
	<script type="text/javascript" type="text/javascript" src="js/mascara_valor.js"></script>
	<script type="text/javascript" src="libraries/jquery.min.js" ></script>
	<script>		
        window.onload = function () {
            var line = new RGraph.Line('cvs', curva, teste);
			line.Set('chart.background.barcolor1', '#FFF');
			line.Set('chart.background.barcolor2', '#FFF');			
            line.Set('chart.curvy', true);
            line.Set('chart.curvy.tickmarks', true);
            line.Set('chart.curvy.tickmarks.fill', null);
            line.Set('chart.curvy.tickmarks.stroke', '#aaa');
            line.Set('chart.linewidth', 2);
            line.Set('chart.hmargin', 5);
			line.Set('chart.labels', ['0', '5', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55', '60', '65', '70', '75', '80', '85', '90', '95', '100']);
			line.Set('chart.tooltips', ['1° posição ' + curva0 + ' Q (m³/s)', '2° posição ' + curva1 + ' Q (m³/s)', '3° posição ' + curva2 + ' Q (m³/s)', '4° posição ' + curva3 + ' Q (m³/s)', '5° posição ' + curva4 + ' Q (m³/s)', '6° posição ' + curva5 + ' Q (m³/s)', '7° posição ' + curva6 + ' Q (m³/s)', '8° posição ' + curva7 + ' Q (m³/s)', '9° posição ' + curva8 + ' Q (m³/s)', '10° posição ' + curva9 + ' Q (m³/s)', '11° posição ' + curva10 + ' Q (m³/s)', '12° posição ' + curva11 + ' Q (m³/s)', '13° posição ' + curva12 + ' Q (m³/s)', '14° posição ' + curva13 + ' Q (m³/s)', '15° posição ' + curva14 + ' Q (m³/s)', '16° posição ' + curva15 + ' Q (m³/s)', '17° posição ' + curva16 + ' Q (m³/s)', '18° posição ' + curva17 + ' Q (m³/s)', '19° posição ' + curva18 + ' Q (m³/s)', '20° posição ' + curva19 + ' Q (m³/s)', '21° posição ' + curva20 + ' Q (m³/s)', '22° posição ' + curva21 + ' Q (m³/s)', '23° posição ' + curva22 + ' Q (m³/s)']);
            line.Set('noaxes', true);				
            RGraph.Effects.Line.jQuery.Trace(line);
        }    
	</script>
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
			<span class="shadow-top"></span>
			<div class="shell">
				<?php if ($qf > $qm) { ?>
					<h2 style="text-align: center;">Aviso!</h2>
						<p style="text-align: center; font-size: 14px;"> A vazão firme não pode ser maior que a vazão média plurianual, 
						a barragem não armazenará o volume necessário! <br />Repita a ação inserindo os dados corretamente.</p>
				<?php } elseif ($outorgada > (0.5 * $q7_10)) { ?>
					<h2 style="text-align: center;">Aviso!</h2>
						<p style="text-align: center; font-size: 14px;"> 
						A vazão outorgada pelo DAEE não pode ser maior que 50% da Q<sub>7,10</sub>.
						<br />Critério estabelecido por meio da Lei 9034/94 (SÃO PAULO, 1994).
						<br />Repita a ação inserindo os dados corretamente!</p>
				<?php } else { ?>
					<section class="container">
						<h2 style="text-align: center;">Curva de Regularização</h2>
						<p class="rotate">Q (m³/s)</p>	
						<div class="left">		
							<div>
								<canvas id="cvs" width="600" height="330" style="border: 1px solid black;">[No canvas support]</canvas>
								<p class="legenda">Permanência %</p>
							</div>
						</div>
						<div class="right"><br />
							<p>Vazão a Jusante</p>						
							<p><strong>Q</strong><b>j</b> <?php echo "= " .  number_format($qj,4) . " m³/s";?></p><br />					
							<p>Vazão Firme</p>						
							<p><strong>Q</strong><b>f</b> <?php echo "= " .  number_format($qf,4) . " m³/s";?></p><br />
						</div>
						<h2 style="float: left; Width: 1024px; text-align: center;">Volume do Reservatório</h2>	
						<table class="tabela2">
							<tr class="refer">
								<td>Tempo de Retorno (Anos)</td><td>2</td><td>4</td><td>5</td><td>10</td><td>20</td><td>50</td><td>100</td>
							</tr>
							<tr class="refer2">
								<td>VR .10<sup>6</sup> (m³)</td>
								<td><?php echo $vReserv2; ?></td>
								<td><?php echo $vReserv4; ?></td>
								<td><?php echo $vReserv5; ?></td>
								<td><?php echo $vReserv10; ?></td>
								<td><?php echo $vReserv20; ?></td>
								<td><?php echo $vReserv50; ?></td>
								<td><?php echo $vReserv100; ?></td>					
							</tr>
						</table>
						<?php if(isset($nota)) {echo "<small class='nota'>" . $nota . "</small>";} ?>					
						<div class="cl">&nbsp;</div>
					</section>
				<?php } ?>
			</div>
		</div>
		<form action="resultadoReg.php" method="post" class="shell">
			<div class="regularizacao">
				<br /><h2>Regularização de Vazão</h2>
				<div class="left">
					<label for="nome">Vazão Outorgada - DAEE (m³/s): </label>
					<input type="text" id="vOut" name="vOut" title="Vazão Outorgada - DAEE (m³/s)" onKeyPress="return Apenas_Numeros2(event);" />
				</div>
				<div class="left">
					<label for="nome">Vazão Demandada (m³/s):</label>
					<input type="text" id="vDem" name="vDem" title="Vazão Demandada (m³/s)" onKeyPress="return Apenas_Numeros2(event);" />	
				</div>
				<div class="left2">
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="submit" name="Submit" class="reg" value="" />
				</div>	
			</div>
		</form>
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