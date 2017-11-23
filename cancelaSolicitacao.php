<?php
$id_solicitacao = $_POST['id_solicitacao'];
require_once("bd/conexao.php");

	try {
		$up = $conn->query("UPDATE solicitacao SET status_solicitacao = 3 WHERE id_solicitacao = {$id_solicitacao}");

		if($up){
			$devolve = $conn->query("UPDATE emprestimo SET status_emprestimo = 0 WHERE solicitacao_id_solicitacao = {$id_solicitacao}");
			$documento = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

			foreach ($documento as $doc) {
				$id_documento = $doc['documento_id_documento'];
				$up = $conn->query("UPDATE documento SET status_documento = 1, solicitado = 0 WHERE id_documento = {$id_documento}");
			}

			if($devolve){
				emailCancelaSolicitacao($id_solicitacao, $conn);
				echo 'Solicitação cancelada com sucesso!';
			}else{
				echo 'Ocorreu um erro!';
			}
		}else{
			return false;
		}

	} catch (Exception $e1) {
		$msg = "Erro ao cancelar a solicitação selecionada!<br>Detalhes: ".$e1->getMessage();
		echo $msg;
	}
?>