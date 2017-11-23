<br>
<div class="table-responsive">
<table class="table table-striped table-bordered table-sm">
	<?php
	require_once("bd/conexao.php");

		try {
			$clientes = $conn->query("SELECT * FROM cliente ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC,	(0 + SUBSTRING(cod_cliente, 2)) ASC");
			$qnt = $clientes->rowCount();

			echo '
			<thead>';
			if($qnt > 0){
				echo '
					<tr>
						<td colspan="5" class="text-center">Número de cliente escontrados: '.$qnt.'</td>
					</tr>
				';
			}
			echo '
				<tr>
					<th class="text-center">Cliente</th>
					<th class="text-center">Casos</th>
					<th colspan="3"  class="text-center">Ação</th>
				</tr>
			</thead>
			<tbody>
			';

			if($qnt > 0){
				foreach ($clientes as $row) {
					$casos = $conn->query("SELECT * FROM caso WHERE cliente_id_cliente = {$row['id_cliente']}");
					echo '
						<tr>
							<td>'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</td>
							<td class="text-center">
								<select style="width:100%" class="form-control-sm">';

								if($casos->rowCount() > 0){
									foreach ($casos as $caso) {
										echo '<option>'.$caso['descricao_caso'].'</option>';
									}
								}else{
									echo '<option>Sem casos vinculados</option>';
								}

					echo '		</select>
							</td>
							<td class="text-center">
								<a class="btn btn-info btn-sm" href="?pg=form-editar-cliente&id='.$row['id_cliente'].'">Editar</a>
							</td>
							'.($tipo_usuario == '1'? '
							<td class="text-center">
								<a class="btn btn-warning btn-sm" href="?pg=editar-cliente&del=1&id='.$row['id_cliente'].'">Desativar</a>
							</td>
							<td class="text-center">
								<a class="btn btn-danger btn-sm" href="?pg=editar-cliente&del=1&id='.$row['id_cliente'].'">Excluir</a>
							</td>
							' : '').'
						</tr>
					';
				}
			}else{
				echo '
					<tr>
						<td colspan="4">Nenhum cliente cadastrado</td>
					</tr>
				';
			}

		} catch (Exception $e) {
			echo 'Error: '.$e->getMessage();
		}
		if(isset($_GET['del'])){
			$id = $_GET['id'];
			$del = $_GET['del'];
			if($tipo_usuario == 1){
				switch ($del) {
					case '1':
						$del = $conn->query("DELETE FROM caso WHERE cliente_id_cliente = $id; DELETE FROM cliente WHERE id_cliente = $id;");
							if($del){
								echo mensagem("Cliente Excluído!", "info", "editar-cliente", "1000");
							}
						break;
					default:
						$upd = $conn->query("UPDATE cliente SET ativo_cliente = 0 WHERE id_cliente = $id");
							if($upd){
								echo mensagem("Cliente desativado!", "info", "editar-cliente", "1000");
							}
						break;
				}
			}
		}
	?>
	</tbody>
</table>
</div>