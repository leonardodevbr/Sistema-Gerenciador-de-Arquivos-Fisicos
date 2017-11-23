<div class="table-responsive">
<table class="table table-striped table-bordered table-sm">
	<?php
	require_once("bd/conexao.php");

		try {

			$id_u_documento = $_GET['last_doc'];
			$documentos = $conn->query("SELECT * FROM documento WHERE id_documento = {$id_u_documento}");

			$qnt = $documentos->rowCount();

			echo '
			<thead>
				<tr>
					<th class="text-center">Código</th>
					<th class="text-center">Descrição</th>
					<th class="text-center">Processo</th>
					<th class="text-center">Ação</th>
				</tr>
			</thead>
			<tbody>
			';

			if($qnt > 0){
				foreach ($documentos as $rDoc) {
					echo '
						<tr>
							<td>'.$rDoc['cod_documento'].'</td>
							<td>'.$rDoc['descricao_documento'].'</td>
							<td>'.$rDoc['processo_documento'].'</td>
							<td class="text-center">
								<a class="btn btn-warning btn-sm" target="_blank" href="?pg=form-editar-documento&id='.$rDoc['id_documento'].'">Editar</a>
							</td>
						</tr>
					';
							/*<td class="text-center">
								<a class="btn btn-danger btn-sm" href="?pg=editar-documento&del=1&id='.$rDoc['id_documento'].'">Excluir</a>
							</td>
*/				}
			}else{
				echo '
					<tr>
						<td colspan="5">Ocorreu algum erro ao exibir o último documento cadastrado!</td>
					</tr>
				';
			}
			echo '</tbody>
				</table></div>';

		} catch (Exception $e) {
			echo 'Ocorreu algum erro ao exibir o último documento cadastrado!<br>Detalhes: '.$e->getMessage();
		}
	?>
