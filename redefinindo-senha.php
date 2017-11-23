<?php

if(isset($_GET['code']) && !empty($_GET['code'])){
	$code = $_GET['code'];
	require_once('bd/conexao.php');
	$temp_pass = base64_encode("mlaa@2017");

	try {
		$user = $conn->query("SELECT id_usuario FROM usuario WHERE codigo_redefinicao = '{$code}'");
		if($user->rowCount() > 0){
			$dados = $user->fetch();
			$id = $dados['id_usuario'];
			$conn->query("UPDATE usuario SET password_usuario = '{$temp_pass}', atualizar_senha = 1, codigo_redefinicao = NULL WHERE id_usuario = {$id}");
			header("Location: /");
		}else{
			header("Location: /");
		}
	} catch (Exception $e) {
		echo 'Ocorreu um erro durante o processo!<br>Mais detalhes: <b>'.$e->getMessage()."</b>";
		echo '<br><a href="index.php">Voltar para o sistema</a>';
	}

}
?>