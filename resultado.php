<?php
	header("Content-Type: text/html;  charset=UTF8",true);
?>
<?php
	include_once "classes/crud.class.php";
	include_once "bd/crud_configuracao.php";
	include_once "includes/seguranca.php";

	$latitude_post = antiInjection(trim($_GET["lat"]));
	$longitude_post = antiInjection(trim($_GET["long"]));
	$area = antiInjection(trim($_GET["area"]));

	$resultado = $crud->rawSelect("SELECT latitude.ID AS id_latitude, latitude.LATITUDE,
		longitude.ID AS id_longitude, longitude.LONGITUDE FROM latitude INNER JOIN longitude
		ON latitude.ID = longitude.ID");
	$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
	$i = 0;
	foreach($resultado as $rs){
		$id_latitude = $rs["id_latitude"];
		$latitude = $rs["LATITUDE"];
		$id_longitude = $rs["id_longitude"];
		$longitude = $rs["LONGITUDE"];

		//Distancia euclidiana entre o ponto desejado ao ponto conhecido (quando o ponto desejado for 0 ele recebe 0.00001).
		if (pow(pow(($latitude - $latitude_post),2) + pow(($longitude - $longitude_post),2),0.5) == 0) {
			$aux[$i] = 0.00001;
		} else {
		//Distancia euclidiana entre o ponto desejado ao ponto conhecido (diferente de 0 ele recebe o valor da formula).
			$auxiliar = pow(pow(($latitude - $latitude_post),2) + pow(($longitude - $longitude_post),2),0.5);
			$aux[$i] = $auxiliar;
		}
		$aux2[$i] = $aux[$i];
		$i++;
	}
	sort($aux2);

	$inv = 0;
	$idwS = 0;
	$idwM = 0;
	$idwG = 0;
	$somaQm = 0;

	for ($i = 0; $i < 6; $i++) {
		for ($j = 0; $j < 189; $j++) {
			if ($aux2[$i] == $aux[$j]) {
				$j = $j + 1;
				$resultado = $crud->rawSelect("SELECT sigma.SIGMA, mi.MI, gama.GAMA
					FROM sigma INNER JOIN mi INNER JOIN gama
					ON sigma.ID = $j AND mi.ID = ($j) AND gama.ID = ($j)");
				$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
				foreach($resultado as $rs){
					$sigma[$i] = $rs["SIGMA"];
					$mi[$i] = $rs["MI"];
					$gama[$i] = $rs["GAMA"];

				}
				$j = $j - 1;

				//Interpolação espacial pelo inverso da distancia euclidiana ao quadrado (sobre a distancia nas 6 posicoes do vetor somadas).
				$inv += 1/pow($aux2[$i],2);

				//Interpolação espacial pelo inverso da distancia euclidiana ao quadrado (sobre o sigma, mi e gama nas 6 posicoes do vetor somadas).
				$idwS += $sigma[$i]/pow($aux2[$i],2);
				$idwM += $mi[$i]/pow($aux2[$i],2);
				$idwG += $gama[$i]/pow($aux2[$i],2);

			}
		}
	}
	$sigmaInt = $idwS / $inv;
	$miInt = $idwM / $inv;
	$gamaInt = $idwG / $inv;

	include('includes/inversa_normal_cumulativa.php');

	$resultado = $crud->rawSelect("SELECT prob_inv.ID AS id_prob_inv, prob_inv.PROB_INV,
		prob_perm.ID AS id_prob_perm, prob_perm.PROB_PERM FROM prob_inv INNER JOIN prob_perm
		ON prob_inv.ID = prob_perm.ID");
	$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
	$i = 0;
	foreach($resultado as $rs){
		$id_prob_inv[$i] = $rs["id_prob_inv"];
		$prob_inv[$i] = $rs["PROB_INV"];
		$id_prob_perm[$i] = $rs["id_prob_perm"];
		$prob_perm[$i] = $rs["PROB_PERM"];

		inverse_ncdf($prob_inv[$i],$x);

		$inversa_nc[$i] = $x;
		$curvaPerm[$i] = ($gamaInt + exp($miInt + ($sigmaInt * $inversa_nc[$i]))) * $area;
		$i++;
	}
	for ($i = 4; $i < 17; $i++) {
		$somaQm += $curvaPerm[$i];
		$i = $i + 1;
	}
	$somaQm;
	$qm = 0.025 * ($curvaPerm[0] + $curvaPerm[21]) + 0.05 * ($curvaPerm[1] + $curvaPerm[19]) + 0.075 * ($curvaPerm[2] + $curvaPerm[18]) + 0.1 * ($somaQm);
	$q7_10 = 0.829 * $curvaPerm[20];
	$q90 = $curvaPerm[18];
	$q95 = $curvaPerm[19];
	$q98 = $curvaPerm[20];

	$consulta = $crud->rawSelect("SELECT ID FROM resultados WHERE SIGMA = $sigmaInt AND MI = $miInt AND GAMA = $gamaInt AND AREA = $area ORDER BY ID DESC LIMIT 1");
	$consulta = $consulta->fetchAll(PDO::FETCH_ASSOC);
	$confirmacao_busca = count($consulta);
	if($confirmacao_busca <> 0){
		foreach($consulta as $rs){
			$id = $rs["ID"];
		}
	} else{
		$dados = array (
			array ("SIGMA"=>$sigmaInt, "MI"=>$miInt, "GAMA"=>$gamaInt, "AREA"=>$area, "Q7_10"=>$q7_10, "QM"=>$qm)
		);

		$crud->dbInsert("resultados", $dados);
		$resultado = $crud->rawSelect("SELECT ID FROM resultados ORDER BY ID DESC LIMIT 1");
		$resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultado as $rs){
			$id = $rs["ID"];
		}
	}

	$curva = join(",", array($curvaPerm[0],$curvaPerm[1],$curvaPerm[2],$curvaPerm[3],$curvaPerm[4],$curvaPerm[5],$curvaPerm[6],$curvaPerm[7],$curvaPerm[8],$curvaPerm[9],$curvaPerm[10],$curvaPerm[11],$curvaPerm[12],$curvaPerm[13],$curvaPerm[14],$curvaPerm[15],$curvaPerm[16],$curvaPerm[17],$curvaPerm[18],$curvaPerm[19],$curvaPerm[20],$curvaPerm[21],$curvaPerm[22]));
	$curva = "[$curva]";

	echo "<script>" . "\n";
	echo "var curva = $curva" . "\n";

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
            var line = new RGraph.Line('cvs', curva);
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
				<section class="container">
					<h2 class="tittle">Curva de Permanência</h2>
					<p class="rotate">Q (m³/s)</p>
					<div class="left">
						<div>
							<canvas id="cvs" width="600" height="255" style="border: 1px solid black;"><b>Para visualizar corretamente os gráficos, é necessário habilitar o JavaScript.</b></canvas>
							<p class="legenda">Permanência %</p>
						</div>
					</div>
					<div class="right">
						<h2 style="text-align: left;">Resultados</h2>
						<table >
							<tr class="refer"><td>Vazão</td><td>(m³/s)</td><td>(m³/h)</td></tr>
							<tr><td class="refer3"><p><strong>Q</strong><b>90</b></p></td><td class="refer2"><?php echo number_format($q90,4, '.', '');?></td><td class="refer2"><?php echo number_format(($q90 * 3600),4, '.', '');?></td></tr>
							<tr><td class="refer3"><p><strong>Q</strong><b>95</b></p></td><td class="refer2"><?php echo number_format($q95,4, '.', '');?></td><td class="refer2"><?php echo number_format(($q95 * 3600),4, '.', '');?></td></tr>
							<tr><td class="refer3"><p><strong>Q</strong><b>98</b></p></td><td class="refer2"><?php echo number_format($q98,4, '.', '');?></td><td class="refer2"><?php echo number_format(($q98 * 3600),4, '.', '');?></td></tr>
							<tr><td class="refer3"><p><strong>Q</strong><b>7,10</b></p></td><td class="refer2"><?php echo number_format($q7_10,4, '.', '');?></td><td class="refer2"><?php echo number_format(($q7_10 * 3600),4, '.', '');?></td></tr>
							<tr><td class="refer3"><p><strong>Q</strong><b>m</b></p></td><td class="refer2"><?php echo number_format($qm,4, '.', '');?></td><td class="refer2"><?php echo number_format(($qm * 3600),4, '.', '');?></td></tr>
						</table>
					</div><br />
					<table class="tabela">
						<tr class="refer">
							<td>Permanência (%)</td><td>5</td><td>10</td><td>15</td><td>20</td><td>25</td><td>30</td>
							<td>35</td><td>40</td><td>45</td><td>50</td>
						</tr>
						<tr class="refer2">
							<td>Vazão (m³/s)</td>
							<td><?php echo number_format($curvaPerm[1],6); ?></td>
							<td><?php echo number_format($curvaPerm[2],6); ?></td>
							<td><?php echo number_format($curvaPerm[3],6); ?></td>
							<td><?php echo number_format($curvaPerm[4],6); ?></td>
							<td><?php echo number_format($curvaPerm[5],6); ?></td>
							<td><?php echo number_format($curvaPerm[6],6); ?></td>
							<td><?php echo number_format($curvaPerm[7],6); ?></td>
							<td><?php echo number_format($curvaPerm[8],6); ?></td>
							<td><?php echo number_format($curvaPerm[9],6); ?></td>
							<td><?php echo number_format($curvaPerm[10],6); ?></td>
						</tr>
					</table>
					<table class="tabela">
						<tr class="refer">
							<td>Permanência (%)</td><td>55</td><td>60</td><td>65</td>
							<td>70</td><td>75</td><td>80</td><td>85</td><td>90</td><td>95</td><td>100</td>
						</tr>
						<tr class="refer2">
							<td>Vazão (m³/s)</td>
							<td><?php echo number_format($curvaPerm[11],6); ?></td>
							<td><?php echo number_format($curvaPerm[12],6); ?></td>
							<td><?php echo number_format($curvaPerm[13],6); ?></td>
							<td><?php echo number_format($curvaPerm[14],6); ?></td>
							<td><?php echo number_format($curvaPerm[15],6); ?></td>
							<td><?php echo number_format($curvaPerm[16],6); ?></td>
							<td><?php echo number_format($curvaPerm[17],6); ?></td>
							<td><?php echo number_format($curvaPerm[18],6); ?></td>
							<td><?php echo number_format($curvaPerm[19],6); ?></td>
							<td><?php echo number_format($curvaPerm[20],6); ?></td>
						</tr>
					</table>
					<div class="cl">&nbsp;</div>
				</section>
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
				<p class="copy">Desenvolvido por <a href="contato.php">Wagner Wolff</a> e <a href="contato.php" >Diego de Liz</a></p>
			</div>
		</div>
	</div>
</body>
</html>
