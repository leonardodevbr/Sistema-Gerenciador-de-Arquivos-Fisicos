<?php
	require_once("bd/conexao.php");
	$id_solicitacao = $_POST['id_solicitacao'];

	try {

		$up = $conn->query("UPDATE solicitacao SET status_solicitacao = 2 WHERE id_solicitacao = {$id_solicitacao}");

		if($up){
			$devolve = $conn->query("UPDATE emprestimo SET status_emprestimo = 0 WHERE solicitacao_id_solicitacao = {$id_solicitacao}");
			$documento = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

			foreach ($documento as $doc) {
				$id_documento = $doc['documento_id_documento'];
				$up = $conn->query("UPDATE documento SET status_documento = 1, solicitado = 0 WHERE id_documento = {$id_documento}");
			}

			echo 'ok';
		}else{
			return false;
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>
