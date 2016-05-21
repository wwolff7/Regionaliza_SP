<?php
	$nome = trim(@$_POST['nome']);
	$email = trim(@$_POST['email']);
	$assunto_user = trim(@$_POST['assunto']);
	$mensagem = trim(@$_POST['mensagem']);
	$enviar = @$_POST['enviar'];
	
	$erros = 1;
	global $email;
	
	if($enviar){		
		if($nome == ""){
			$erros++;
			echo "<small class=\"erro\">*** O Campo nome esta vazio</small><br />";
		} elseif(is_numeric($nome)) {
			$erros++;
			echo "<small class=\"erro\">*** Voc&ecirc; digitou numero no campo nome</small><br />";
		}
		if($email == "") {
			$erros++;
			echo "<small class=\"erro\">*** O Campo email est&aacute; vazio</small><br />";
		}
		if($assunto_user == ""){
			$erros++;
			echo "<small class=\"erro\">*** O Campo assunto est&eacute; vazio</small><br />";
		}
		if($mensagem == ""){
			$erros++;
			echo "<small class=\"erro\">*** O Campo mensagem est&eacute; vazio</small><br />";
		}	
							
		$email = str_replace (" ", "", $email);
		$email = str_replace ("/", "", $email);
		$email = str_replace ("@.", "@", $email);
		$email = str_replace (".@", "@", $email);
		$email = str_replace (",", ".", $email);
		$email = str_replace (";", ".", $email);
		
		if(strlen($email)<8 || substr_count($email, "@")!=1 || substr_count($email, ".")==0) {
			$erros++;
			echo "<small class=\"erro\">*** Por favor, digite seu <b>e-mail</b> corretamente.</small><br />";
		}
		if($erros <= 1){
			$seuemail = "diego.liz@hotmail.com, wwolff@usp.br";

			$assunto = "contato do site";
			
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "From: $seuemail \r\n";

			$conteudo = "<strong>Nome:</strong> $nome<br />";
			$conteudo .= "<strong>Email:</strong> $email<br />";
			$conteudo .= "<strong>Assunto:</strong> $assunto_user<br />";
			$conteudo .= "<strong>Mensagem:</strong> $mensagem<br />";
			
			$enviando = mail($seuemail, $assunto, $conteudo, $headers);

			if($enviando) {
				echo "Mensagem enviada com sucesso!";
				echo "<script>alert(\"Mensagem enviada com sucesso!\")</script>";
				echo "<script>window.location = \"index.php\"</script>";
			} else {
				echo "<p><b>$nome</b><br />Ouve um erro no envio, desculpe-nos pelo transtorno!!!</p>";
			}				
		}
	}
?>
