<br>
<div id="retorno" class="my-3"></div>
<div class="row">
	<div class="form-group col">
		<label for="filter_cli">Filtrar Cliente:</label>
		<select id="filter_cli" name="filter_cli" class="form-control">
			<option>Todos</option>
		<?php
			require("bd/conexao.php");

			$cliente = $conn->query("
				SELECT
				  *
				FROM
				  cliente
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC");

			if($cliente->rowCount() > 0){
				foreach ($cliente as $row) {
					if(isset($_GET['filter_cli']) AND !empty($_GET['filter_cli'])){
						if($_GET['filter_cli'] == $row['id_cliente']){
							echo '<option selected value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
						}else{
							echo '<option value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
						}
					}else{
						echo '<option value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
					}
				}
			}else{
				echo '<option>Nenhum cliente cadastrado</option>';
			}

		?>
		</select>
	</div>

	<div class="form-group col">

		<label for="filter_caso">Filtrar Caso:</label>
		<select id="filter_caso" name="filter_caso" class="form-control">
		<?php
			if(isset($_GET['filter_cli'])){
				$cliente_cliente = $_GET['filter_cli'];
				$caso_selecionado = $_GET['filter_caso'];
				try {
					$sql = "SELECT 	cs.id_caso, cs.descricao_caso, cs.num_caso FROM caso cs WHERE cs.cliente_id_cliente = {$cliente_cliente}";

					$casosCliente = $conn->query($sql);
					$qnt = $casosCliente->rowCount();

					if($qnt > 0){

						foreach ($casosCliente as $csRow) {
							$id_caso = $csRow['id_caso'];
							$num_caso = $csRow['num_caso'];
							$descricao_caso = $csRow['descricao_caso'];
							if($caso_selecionado == $id_caso){
								echo '<option selected value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
							}else{
								echo '<option value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
							}
						}

					}

				} catch (Exception $e1) {
					$erro = $e1->getMessage();
					echo '<option>{$erro}</option>';
				}
			}else{
				echo '<option>Selecione um cliente</option>';
			}
		?>
		</select>
	</div>
	<form action="" method="get">
		<input type="hidden" name="pg" value="editar-documento">
		<div class="col">
			<label for="filter_cod">Filtrar Código:</label>
			<div class="input-group">
				<input type="text" id="filter_cod" value="<?=(isset($_GET['filter_cod']) ? $_GET['filter_cod'] : '')?>" name="filter_cod" class="form-control">
				<span class="input-group-btn">
					<input type="submit" class="btn btn-info">
				</span>
			</div>
		</div>
	</form>
</div>

<div class="table-responsive">
<?php
	echo '<table class="table table-striped table-bordered table-sm">';
	if(isset($_GET['filter_cli']) || isset($_GET['filter_cod'])){
		require_once("bd/conexao.php");

		try {
			if(isset($_GET['filter_doc'])){
				$id_documento = $_GET['filter_doc'];
				$documentos = $conn->query("SELECT * FROM documento WHERE id_documento = {$id_documento}");
				$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
			}else{
				$documentos = $conn->query("SELECT * FROM documento ORDER BY id_documento ASC");
				$btn_back = '';
			}

			if(isset($_GET['filter_cod'])){
				$doc_cod = $_GET['filter_cod'];
				$documentos = $conn->query("SELECT * FROM documento WHERE cod_documento LIKE '%{$doc_cod}%'");
				$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
			}

			if(isset($_GET['filter_cli'])){
				$id_cliente = $_GET['filter_cli'];
				$documentos = $conn->query("SELECT * FROM documento WHERE caso_cliente_id_cliente = {$id_cliente}");
				$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
			}

			if(isset($_GET['filter_cli']) && isset($_GET['filter_caso'])){
				$id_cliente = $_GET['filter_cli'];
				$id_caso = $_GET['filter_caso'];
				$documentos = $conn->query("SELECT * FROM documento WHERE caso_cliente_id_cliente = {$id_cliente} AND caso_id_caso = {$id_caso}");
				$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
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
							'.($tipo_usuario == 1 ? '<td class="text-center">
														<button class="btn btn-danger btn-sm" onclick="delDoc('.$row['id_documento'].');">Excluir</button>
													</td>' : '').'
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
				</table>'.$btn_back;

		} catch (Exception $e) {
			echo 'Error: '.$e->getMessage();
		}
	}
?>
</div>