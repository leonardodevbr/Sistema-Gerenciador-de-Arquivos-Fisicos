<br>
<div class="table-responsive">
<table class="table table-striped table-bordered table-sm">
	<?php
	require_once("bd/conexao.php");

		try {
			if(isset($_GET['filter_doc'])){
				$id_documento = $_GET['filter_doc'];
				$documentos = $conn->query("SELECT * FROM documento WHERE id_documento = {$id_documento}");
				$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
			}else if(isset($_GET['filter_cli'])){
				$id_cliente = $_GET['filter_cli'];
				$documentos = $conn->query("SELECT * FROM documento WHERE caso_cliente_id_cliente = {$id_cliente}");
				$btn_back = '';
			}else{
				$documentos = $conn->query("SELECT * FROM documento ORDER BY id_documento ASC");
				$btn_back = '';
			}
			$qnt = $documentos->rowCount();

			echo '
			<thead>';
			if($qnt > 0){
				echo '
					<tr>
						<td colspan="5" class="text-center">Número de documentos escontrados: '.$qnt.'</td>
					</tr>
				';
			}
			echo '
				<tr>
					<th class="text-center">Código</th>
					<th class="text-center">Descrição</th>
					<th class="text-center">Processo</th>
					<th colspan="2" class="text-center">Ação</th>
				</tr>
			</thead>
			<tbody>
			';

			if($qnt > 0){
				foreach ($documentos as $row) {
					echo '
						<tr>
							<td>'.$row['cod_documento'].'</td>
							<td>'.$row['descricao_documento'].'</td>
							<td>'.$row['processo_documento'].'</td>
							<td class="text-center">
								<a class="btn btn-info btn-sm" href="?pg=form-editar-documento&id='.$row['id_documento'].'">Editar</a>
							</td>
							<td class="text-center hidden-xs-up">
								<a class="btn btn-danger btn-sm" href="?pg=editar-documento&del=1&id='.$row['id_documento'].'">Excluir</a>
							</td>
						</tr>
					';
				}
			}else{
				echo '
					<tr>
						<td colspan="5">Nenhum documento cadastrado</td>
					</tr>
				';
			}
			echo '</tbody>
				</table></div>'.$btn_back;

		} catch (Exception $e) {
			echo 'Error: '.$e->getMessage();
		}

		if(isset($_GET['del']) && $_GET['del'] == 1){
			$id = $_GET['id'];

			/*Guarda o ID da localização atual do documento*/
			$id_loc = $conn->query("SELECT localizacao_id_localizacao FROM localizacao_has_documento WHERE documento_id_documento = $id")->fetch();
			/*Deleta o documento selecionado de sua localização atual*/
			$delLoc = $conn->query("DELETE FROM localizacao_has_documento WHERE documento_id_documento = $id");
			/*Verifica se ainda existem documentos na localização*/
			if($conn->query("SELECT * FROM localizacao_has_documento WHERE localizacao_id_localizacao = $id_loc[0]")->rowCount() > 0){
				/*Se existem documentos, remove a flag "etiquetada" da localização*/
				$conn->query("UPDATE localizacao SET etiquetada = 0 WHERE id_localizacao = {$id_loc[0]}");
			}else{
				/*Se não existem mais documentos, deleta a localização.*/
				/*Deleta a antiga etiqueta*/
				$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = {$id_loc[0]}");
				/*Deleta a localização selecionada da prateleira atual*/
				$id_prateleira = $conn->query("SELECT prateleira_id_prateleira FROM localizacao WHERE id_localizacao = $id_loc[0]")->fetch();
				$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira - 1) WHERE id_prateleira = {$id_prateleira[0]}");
				/*Deleta a localização*/
				$conn->query("DELETE FROM localizacao WHERE id_localizacao = {$id_loc[0]}");
				$complemento = "";
			}
			/*Deleta o documento selecionado*/
			$delDoc = $conn->query("DELETE FROM documento WHERE id_documento = $id");

			echo mensagem("Documento excluído!".$complemento, "info", "editar-documento", "1000");
		}
	?>
