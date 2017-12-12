<?php
	if(!isset($_POST['id'])){
		header("Location: /");
	}else{
		$id_solicitacao = $_POST['id'];
		// $id_solicitacao = 25;
		try {
		require_once("bd/conexao.php");

			$select = $conn->query("SELECT * FROM solicitacao s INNER JOIN usuario u ON s.usuario_id_usuario = u.id_usuario WHERE s.id_solicitacao = {$id_solicitacao}");
			$qnt = $select->rowCount();

			foreach ($select as $row) {
				$id_usuario = $row['id_usuario'];
				$nome_usuario = $row['nome_usuario'];
				$status_solicitacao = $row['status_solicitacao'];
				$table_item_solicitado = '';


				$solicitacoes = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

				foreach ($solicitacoes as $sol) {
					$id_documento = $sol['documento_id_documento'];
					$documento = $conn->query("SELECT * FROM documento WHERE id_documento = {$id_documento}")->fetch();
					if($documento['alocado'] == '1'){
						$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
							$id_localizacao = ($id_local[0] == '' ? 0 : $id_local[0]);
						$nome_localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira WHERE id_localizacao = {$id_localizacao}")->fetch();
							$localizacao = $nome_localizacao['tipo_localizacao']." ".$nome_localizacao['num_localizacao']." - P ".$nome_localizacao['num_prateleira'];

					}else{
						$localizacao = 'Não Definido';
					}

					$table_item_solicitado .= '<tr><td>'.$documento['cod_documento'].'</td><td>'.$documento['descricao_documento'].'</td><td>'.$localizacao.'</td></tr>';;
				}

				if($status_solicitacao == '0'){
					$status = 'Solicitado';
				}else if($status_solicitacao == '3'){
					$status = 'Cancelado';
				}else if($status_solicitacao == '2'){
					$status = 'Finalizado';
				}else{
					$status = 'Emprestado';
				}
			}

		} catch (Exception $e1) {
			$msg = "Erro ao listar as solicitações existentes!<br>Detalhes: ".$e1->getMessage();
		}

		$result['id_solicitacao'] = $id_solicitacao;
		$result['id_usuario'] = $id_usuario;
		$result['usuario'] = $nome_usuario;
		$result['msg'] = $msg;
		$result['status'] = $status;
		$result['linhas'] = $table_item_solicitado;

		echo json_encode($result);
	}
?>