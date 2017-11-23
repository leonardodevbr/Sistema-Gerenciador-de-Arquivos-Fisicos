<br>
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
		$modal = '';

		if($qnt > 0){
			foreach ($select as $row) {

				$id_solicitacao = $row['id_solicitacao'];
				$solicitacao = '
					<a class="text-muted" href="" title="Visualizar" data-toggle="modal" data-target="#verSolicitacao-'.$id_solicitacao.'">
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
				// switch ($setor_usuario_db) {
				// 	case '1':
				// 		$setor_usuario = 'Tecnologia';
				// 	break;
				// 	case '2':
				// 		$setor_usuario = 'Secretaria';
				// 	break;
				// 	case '3':
				// 		$setor_usuario = 'Jurídico';
				// 	break;
				// 	case '4':
				// 		$setor_usuario = 'Administrativo';
				// 	break;
				// }
				echo '
					<tr>
						<td class="text-center">'.$solicitacao.'</td>
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
				$print = '<h5 class="modal-title" id="VerSolicitacao">Solicitação '.$id_solicitacao.' - '.$nome_usuario.'</h5>';
				$print .= '<div class="table-responsive">
					      	<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Descrição</th>
										<th>Localização</th>
										<th>Observação</th>
									</tr>
								</thead>
								<tbody>
									'.$table_item_solicitado.'
								</tbody>
					      	</table>
					      </div>';

				$modal .= '
					<div class="modal fade" id="verSolicitacao-'.$id_solicitacao.'" tabindex="-1" role="dialog" aria-labelledby="VerSolicitacao" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="VerSolicitacao">Solicitação '.$id_solicitacao.' - '.$nome_usuario.'</h5>
					        <button type="button" class="close hidden-print" data-dismiss="modal" aria-label="Fechar">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body table-responsive d-block">
					      	<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Código</th>
										<th>Descrição</th>
										<th>Localização</th>
										<th>Observação</th>
									</tr>
								</thead>
								<tbody>
									'.$table_item_solicitado.'
								</tbody>
					      	</table>
					      </div>
					      <div class="modal-footer hidden-print">
					      	'.$btn_acao.'
					        <button onclick="imprimirModal();" type="button" class="w-100 btn btn-sm btn-warning">Imprimir</button>
					        <button type="button" class="w-50 btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
					      </div>
					    </div>
					  </div>
					</div>
				';

			}/*Fim do foreach*/
			echo "</tbody></table>";
		}else{
			echo '<tr><td colspan="5" class="text-center">Não encontramos nenhuma solicitação!</td></tr>';
		}

	} catch (Exception $e1) {
		$msg = "Erro ao listar as solicitações existentes!<br>Detalhes: ".$e1->getMessage();
		echo mensagem_close($msg, "danger");
	}

?>
</div>
<?php echo $modal; ?>