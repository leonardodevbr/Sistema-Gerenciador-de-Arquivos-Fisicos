<div id="carregando"></div>

<?php
	if($tipo_usuario == '0'){
		include_once("login.php");
	}else if($tipo_usuario == '3'){
		include_once("solicitar.php");
	}else if($tipo_usuario == '4'){
		include_once("solicitacoes.php");
	}else if($tipo_usuario == '2'){
		include_once("solicitacoes.php");
	}else{
		include_once("solicitacoes.php");
	}
?>