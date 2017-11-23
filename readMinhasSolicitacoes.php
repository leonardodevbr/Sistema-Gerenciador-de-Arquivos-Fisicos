<table class="table table-striped table-bordered hidden-print">
	<thead>
		<tr>
			<th class="text-center text-muted">#</th>
			<th class="text-center text-muted">Documentos Solicitados</th>
			<th class="text-center text-muted">Status</th>
			<th colspan="2" class="text-center text-muted">Ação</th>
		</tr>
	</thead>
	<tbody>
<?php
$id_usuario = $_GET['id_usuario'];
require_once("bd/conexao.php");

	try {

		$select = $conn->query("SELECT * FROM solicitacao s INNER JOIN usuario u ON s.usuario_id_usuario = u.id_usuario WHERE usuario_id_usuario = {$id_usuario} AND status_solicitacao < 2 ORDER BY id_solicitacao DESC");
		$qnt = $select->rowCount();
		$modal = '';

		if($qnt > 0){
			foreach ($select as $row) {

				$id_solicitacao = $row['id_solicitacao'];
				$id_usuario = $row['id_usuario'];
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
					$table_item_solicitado .= '<tr><td>'.$documento['cod_documento'].'</td><td>'.$localizacao.'</td><td></td></tr>';;
				}
				$qnt_item_solicitado = $solicitacoes->rowCount();
				if($qnt_item_solicitado == 1){
					$info_qnt_item_solicitado = "1 documento solicitado";
				}else{
					$info_qnt_item_solicitado = $qnt_item_solicitado." documentos solicitados";
				}

				if($status_solicitacao == '0'){
					$status = '<i class="h-100 my-auto fa fa-hourglass-start fa-2x hidden-md-up"></i><span class="hidden-sm-down">Aguardando receber</span>';
					$btn_info = '<td><button onclick="infoSolicitacao('.$id_solicitacao.');" class="btn btn-sm btn-info w-100 text-center" type="button"><i class="h-100 my-auto p-1 fa fa-info hidden-md-up"></i><span class="hidden-sm-down">Informações</span></button></td>';

					$btn_cancelar = '<td><button onclick="btnCancelaSolicitacao('.$id_solicitacao.');" class="btn btn-sm btn-danger w-100 text-center" type="button"><i class="h-100 my-auto p-1 fa fa-close hidden-md-up"></i><span class="hidden-sm-down">Cancelar</span></button></td>';
					$btn_acao = $btn_info.$btn_cancelar;
				}else{
					$status = '<i class="h-100 my-auto fa fa-user-circle-o fa-2x hidden-md-up"></i><span class="hidden-sm-down">Em minha posse</span>';
					$btn_info = '<td colspan="2"><button onclick="infoSolicitacao('.$id_solicitacao.');" class="btn btn-sm btn-info w-100 text-center" type="button"><i class="h-100 my-auto p-1 fa fa-info hidden-md-up"></i><span class="hidden-sm-down">Informações</span></button></td>';
					$btn_acao = $btn_info;
				}

				echo '
					<tr>
						<td class="text-center text-muted">'.$id_solicitacao.'</td>
						<td class="text-center text-muted">
							<select class="form-control form-control-sm">
								<option>'.$info_qnt_item_solicitado.'</option>
								'.$item_solicitado.'
							</select></td>
						<td class="text-center text-muted">'.$status.'</td>
						'.$btn_acao.'
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