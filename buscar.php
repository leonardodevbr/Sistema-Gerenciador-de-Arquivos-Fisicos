<div class="mt-3 hidden-print">
	<div class="row">
		<div class="col-sm-12 col-md-4 my-1">
			<div class="input-group">
				<span class="input-group-addon">
					<input checked type="radio" name="tabela" value="geral" id="geral">
				</span>
				<label for="geral" class="form-control">Geral</label>
			</div>
		</div>
		<div class="col-sm-12 col-md-4 my-1">
			<div class="input-group">
				<span class="input-group-addon">
					<input type="radio" name="tabela" value="documento" id="documento">
				</span>
				<label for="documento" class="form-control">Documentos</label>
			</div>
		</div>
		<div class="col-sm-12 col-md-4 my-1">
			<div class="input-group">
				<span class="input-group-addon">
					<input type="radio" name="tabela" value="cliente" id="cliente">
				</span>
				<label for="cliente" class="form-control">Clientes</label>
			</div>
		</div>
	</div>
	<div class="row my-3">
		<div class="col-12">
			<div class="input-group">
				<input placeholder="O que procura?" autofocus class="form-control" id="termo" type="text" name="termo">
				<span class="input-group-btn">
					<button id="btn_buscar" type="button" class="btn btn-secondary">Buscar</button>
		      	</span>
			</div>
		</div>
	</div>
</div>
<div id="carregaBusca">
<div id="limpaPesquisa">
<?php
$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
if(isset($_GET['tabela']) && isset($_GET['id'])){
		$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
		$tabela = $_GET['tabela'];
		$id = $_GET['id'];

	switch ($tabela){
		case 'documento':
			require_once("bd/conexao.php");
			$sql = "
			SELECT
			*
			FROM
			documento d
			INNER JOIN
			cliente c
			ON
			d.caso_cliente_id_cliente = c.id_cliente
			INNER JOIN
			  caso cs
			ON
			  cs.id_caso = d.caso_id_caso
			WHERE
			d.ativo = 1
			AND
			id_documento = '{$id}'";
			$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
							</tr>
						</thead>

						<tbody>
					';
					foreach ($documentos as $row) {
					$id_documento = $row['id_documento'];
					$id_cliente = $row['id_cliente'];
					$id_caso = $row['id_caso'];
					$caso = $row['descricao_caso'];
					if($row['alocado'] == '1'){
						$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
							$id_localizacao = $id_local[0];
						$nome_localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira WHERE id_localizacao = {$id_localizacao}")->fetch();
						$localizacao = $nome_localizacao['tipo_localizacao']." ".$nome_localizacao['num_localizacao']." - P ".$nome_localizacao['num_prateleira'];
					}else{
						$localizacao = 'Não Definido';
						$id_localizacao = 0;
					}

					if(strlen($row["descricao_documento"]) > 46){
						$descricao = "<small>".$row["descricao_documento"]."</small>";
					}else{
						$descricao = $row["descricao_documento"];
					}

					if(strlen($row["processo_documento"]) > 40){
						$processo = "<small>".$row["processo_documento"]."</small>";
					}else{
						$processo = $row["processo_documento"];
					}

						echo '
							<tr>
						<td><a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
								<td>'.$processo.'</td>
							</tr>
						';
					}

					echo '
						</tbody>
					</table></div>
					';
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
			}

		break;
		case 'localizacao_has_documento':

			require_once("bd/conexao.php");
			$sql1 = "SELECT * FROM `localizacao_has_documento` WHERE localizacao_id_localizacao = '{$id}'";

			try {

				$docs = $conn->query($sql1);
				$qnt = $docs->rowCount();

					if($qnt > 0){
						echo '
						<div class="table-responsive">
						<table class="table table-striped table-bordered table-sm">
							<thead>
								<tr>
									<th>Código</th>
									<th>Descrição</th>
									<th>Acondicionamento</th>
									<th>Cliente</th>
									<th>Caso</th>
									<th>Processo</th>
								</tr>
							</thead>

							<tbody>
						';
						foreach ($docs as $doc) {
							$id = $doc['documento_id_documento'];
							$sql = "
							SELECT
							  *
							FROM
							  documento d
							INNER JOIN
							  cliente c
							ON
							  d.caso_cliente_id_cliente = c.id_cliente
							INNER JOIN
							  caso cs
							ON
							  cs.id_caso = d.caso_id_caso
							WHERE
							d.ativo = 1
							AND
							  id_documento = '{$id}'
							ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";

							try {
								$documentos = $conn->query($sql);
								$qnt_documentos = $documentos->rowCount();

								foreach ($documentos as $row) {
								$id_documento = $row['id_documento'];
								$id_cliente = $row['id_cliente'];
								$id_caso = $row['id_caso'];
								$caso = $row['descricao_caso'];
								if($row['alocado'] == '1'){
									$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
										$id_localizacao = $id_local[0];
									$nome_localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira  WHERE id_localizacao = {$id_localizacao}")->fetch();
										$localizacao = $nome_localizacao['tipo_localizacao']." ".$nome_localizacao['num_localizacao']." - P ".$nome_localizacao['num_prateleira'];

								}else{
									$localizacao = 'Não Definido';
									$id_localizacao = 0;
								}

								if(strlen($row["descricao_documento"]) > 46){
									$descricao = "<small>".$row["descricao_documento"]."</small>";
								}else{
									$descricao = $row["descricao_documento"];
								}

								if(strlen($row["processo_documento"]) > 40){
									$processo = "<small>".$row["processo_documento"]."</small>";
								}else{
									$processo = $row["processo_documento"];
								}

								echo '
									<tr>
							<td><a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
										<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
										<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
										<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
										<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
										<td>'.$processo.'</td>
									</tr>
								';
								}

							} catch (Exception $e) {
								echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
							}
						}/*Fim do Foreach*/

					echo '
					</tbody>
				</table></div>
				';
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $eLoc) {
				echo $eLoc->getMessage();
			}

		break;
		case 'cliente':
			require_once("bd/conexao.php");
			$sql = "
			SELECT
			  *
			FROM
			  documento d
			INNER JOIN
			  cliente c
			ON
			  d.caso_cliente_id_cliente = c.id_cliente
			INNER JOIN
			  caso cs
			ON
			  cs.id_caso = d.caso_id_caso
			WHERE
			d.ativo = 1
			AND
			  caso_cliente_id_cliente = '{$id}'
			ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
							</tr>
						</thead>

						<tbody>
					';
					foreach ($documentos as $row) {
					$id_documento = $row['id_documento'];
					$id_cliente = $row['id_cliente'];
					$id_caso = $row['id_caso'];
					$caso = $row['descricao_caso'];
					if($row['alocado'] == '1'){
						$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
							$id_localizacao = $id_local[0];
						$nome_localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira  WHERE id_localizacao = {$id_localizacao}")->fetch();
							$localizacao = $nome_localizacao['tipo_localizacao']." ".$nome_localizacao['num_localizacao']." - P ".$nome_localizacao['num_prateleira'];

					}else{
						$localizacao = 'Não Definido';
						$id_localizacao = 0;
					}

					if(strlen($row["descricao_documento"]) > 46){
						$descricao = "<small>".$row["descricao_documento"]."</small>";
					}else{
						$descricao = $row["descricao_documento"];
					}

					if(strlen($row["processo_documento"]) > 40){
						$processo = "<small>".$row["processo_documento"]."</small>";
					}else{
						$processo = $row["processo_documento"];
					}

						echo '
							<tr>
							<td><a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
								<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
								<td>'.$processo.'</td>
							</tr>
						';
					}

					echo '
						</tbody>
					</table></div>
					';
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				$msg = 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
				echo mensagem($msg, "danger");
			}

		break;
		case 'caso':
			require_once("bd/conexao.php");
			$sql = "
				SELECT
				  *
				FROM
				  documento d
				INNER JOIN
				  cliente c
				ON
				  d.caso_cliente_id_cliente = c.id_cliente
				INNER JOIN
				  caso cs
				ON
				  cs.id_caso = d.caso_id_caso
				WHERE
				d.ativo = 1
				AND
				  d.caso_id_caso = '{$id}'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
							</tr>
						</thead>

						<tbody>
					';
					foreach ($documentos as $row) {
						$id_documento = $row['id_documento'];
						$id_cliente = $row['id_cliente'];
						$id_caso = $row['id_caso'];
						$caso = $row['descricao_caso'];
						if($row['alocado'] == '1'){
							$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
								$id_localizacao = ($id_local[0] == '' ? 0 : $id_local[0]);
							$nome_localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira WHERE id_localizacao = {$id_localizacao}")->fetch();
								$localizacao = $nome_localizacao['tipo_localizacao']." ".$nome_localizacao['num_localizacao']." - P ".$nome_localizacao['num_prateleira'];

						}else{
							$localizacao = 'Não Definido';
							$id_localizacao = 0;
						}

						if(strlen($row["descricao_documento"]) > 46){
							$descricao = "<small>".$row["descricao_documento"]."</small>";
						}else{
							$descricao = $row["descricao_documento"];
						}

						if(strlen($row["processo_documento"]) > 40){
							$processo = "<small>".$row["processo_documento"]."</small>";
						}else{
							$processo = $row["processo_documento"];
						}

							echo '
								<tr>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
									<td>'.$processo.'</td>
								</tr>
							';
						}

						echo '
							</tbody>
						</table></div>';
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				$msg = 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
				echo mensagem($msg, "danger");
			}
		}
	echo $btn_back;

}

?>


</div><!-- Div com todos as buscas -->
</div><br><br>