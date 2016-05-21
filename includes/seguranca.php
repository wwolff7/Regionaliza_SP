<?php
	function antiInjection($_variavel){
		$_variavel = preg_replace("/(drop |show |name |like |select |delete |insert |update |from |where |\\\\|\--|\%|\#|\'|\")/","",$_variavel);
		$_variavel = strip_tags($_variavel);
		$_variavel = addslashes($_variavel);
		return $_variavel;
	}
?>