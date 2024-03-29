<div class="table-responsive hidden-print">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Ver Detalhes</th>
				<th>Solicitante</th>
				<th>Documentos Solicitados</th>
				<th>Status</th>
				<th>Ação</th>
			</tr>
		</thead>
		<tbody>
	<?php
	require_once("bd/conexao.php");

		try {
			session_start();
			$tipo_user = $_SESSION['tipo_usuario'];

			if($tipo_user == 1){
				$where = "WHERE u.tipo_usuario != {$tipo_user} ORDER BY status_solicitacao ASC, id_solicitacao DESC";
			}else{
				$where = "WHERE s.status_solicitacao != 2 and u.tipo_usuario != {$tipo_user} ORDER BY status_solicitacao ASC, id_solicitacao DESC";
			}

			$select = $conn->query("SELECT * FROM solicitacao s INNER JOIN usuario u ON s.usuario_id_usuario = u.id_usuario {$where}");
			$qnt = $select->rowCount();

			if($qnt > 0){
				foreach ($select as $row) {

					$id_solicitacao = $row['id_solicitacao'];
					$btnVerSolicitacao = '
						<a class="text-muted" title="Visualizar" onclick="carregaModal('.$id_solicitacao.')">
							<i class="fa fa-eye fa-2x"></i>
						</a>';
					$id_usuario = $row['id_usuario'];
					$setor_usuario_db = $row['tipo_usuario'];
					$nome_usuario = $row['nome_usuario'];
					$status_solicitacao = $row['status_solicitacao'];
					$item_solicitado = '';
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

						$item_solicitado .= '<option>'.$documento['cod_documento'].'</option>';
						$table_item_solicitado .= '<tr><td>'.$documento['cod_documento'].'</td><td>'.$documento['descricao_documento'].'</td><td>'.$localizacao.'</td><td></td></tr>';;
					}
					$qnt_item_solicitado = $solicitacoes->rowCount();
					if($qnt_item_solicitado == 1){
						$info_qnt_item_solicitado = "1 documento solicitado";
					}else{
						$info_qnt_item_solicitado = $qnt_item_solicitado." documentos solicitados";
					}

					if($status_solicitacao == '0'){
						$status = 'Solicitado';
						$btn_acao = '<button onclick="empresta('.$id_solicitacao.', '.$id_usuario.');" class="w-100 btn btn-sm btn-warning" type="button">Emprestar</button>';
					}else if($status_solicitacao == '3'){
						$status = 'Cancelado';
						$btn_acao = '<button onclick="remove('.$id_solicitacao.', '.$id_usuario.');" class="w-100 btn btn-sm btn-secondary" type="button">Remover</button>';
					}else if($status_solicitacao == '2' && $tipo_user == 1){
						$status = 'Finalizado';
						$btn_acao = '<button onclick="reabrir('.$id_solicitacao.', '.$id_usuario.');" class="w-100 btn btn-sm btn-outline-danger" type="button">Reabrir</button>';
					}else{
						$status = 'Emprestado';
						$btn_acao = '<button onclick="devolve('.$id_solicitacao.', '.$id_usuario.');" class="w-100 btn btn-sm btn-info btnDevolve" type="button">Devolver</button>';
					}

					echo '
						<tr>
							<td class="text-center">'.$btnVerSolicitacao.'</td>
							<td>'.$nome_usuario.'</td>
							<td>
								<select class="form-control form-control-sm">
									<option>'.$info_qnt_item_solicitado.'</option>
									'.$item_solicitado.'
								</select></td>
							<td>'.$status.'</td>
							<td>'.$btn_acao.'</td>
						</tr>
					';

				}/*Fim do foreach*/
			}else{
				echo '<tr><td colspan="5" class="text-center">Não encontramos nenhuma solicitação!</td></tr>';
			}

		} catch (Exception $e1) {
			$msg = "Erro ao listar as solicitações existentes!<br>Detalhes: ".$e1->getMessage();
			echo mensagem_close($msg, "danger");
		}

	?>
		</tbody>
	</table>
</div>