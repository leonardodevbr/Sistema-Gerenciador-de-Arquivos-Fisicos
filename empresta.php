<?php
	require_once("bd/conexao.php");
	$id_solicitacao = $_POST['id_solicitacao'];
	$id_usuario = $_POST['id_usuario'];

	// echo "Solicitação: ".$id_solicitacao."\nUsuário: ".$id_usuario;

	try {
		$empresta = $conn->query("INSERT INTO emprestimo (status_emprestimo, usuario_id_usuario, solicitacao_id_solicitacao) VALUES ('1', {$id_usuario}, {$id_solicitacao})");

		if($empresta){
			$up = $conn->query("UPDATE solicitacao SET status_solicitacao = 1 WHERE id_solicitacao = {$id_solicitacao}");
			$documento = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

			foreach ($documento as $doc) {
				$id_documento = $doc['documento_id_documento'];
				$up = $conn->query("UPDATE documento SET status_documento = 0 WHERE id_documento = {$id_documento}");
			}
			echo 'ok';
		}else{
			return false;
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>