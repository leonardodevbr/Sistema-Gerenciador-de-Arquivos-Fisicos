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
<div id="carregando"></div>
<div id="carregaBusca">
<div id="limpaPesquisa">
<?php

if(isset($_POST['solicitar'])){
	require_once("bd/conexao.php");

	if(empty($_POST['doc'])){
		echo mensagem("Nenhum documento selecionado!", "danger");
	}else{
		$email_usuario = $usuario_logado;
		$doc = $_POST['doc'];
		$tipoEmail = "abertura";
		$dadosSolicitante = $conn->query("SELECT email_usuario, password_usuario, nome_usuario FROM usuario WHERE status_usuario = '1' AND email_usuario = '{$usuario_logado}'")->fetch();
		$email_usuario = $dadosSolicitante['email_usuario'];
		$nome_usuario = $dadosSolicitante['nome_usuario'];
		$pass_usuario = base64_decode($dadosSolicitante['password_usuario']);
		try {
			$dados_solicitacao = array(':usuario_id_usuario' => $id_usuario);
			$sql = "INSERT INTO solicitacao (usuario_id_usuario) VALUES (:usuario_id_usuario)";
			$result = $conn->prepare($sql);
			$solicita = $result->execute($dados_solicitacao);
			if($solicita){
				$id_solicitacao = $conn->lastInsertId('solicitacao');

				try {
					foreach($doc as $k => $v){
						$id_documento = $v;

						/*Atualiza o campo "solicitado" do item para 1*/
						$up = $conn->query("UPDATE documento SET solicitado = 1 WHERE id_documento = {$id_documento}");
						$conn->query("INSERT INTO solicitacao_has_documento (solicitacao_id_solicitacao, usuario_id_usuario, documento_id_documento) VALUES ({$id_solicitacao}, {$id_usuario}, {$id_documento})");

					}
					echo mensagem("Solicitação registrada com sucesso!<br>Aguarde o contato do setor responsável para retirar o item solicitado.", "success", "minhas-solicitacoes", "3000");
					emailNovaSolicitacao($nome_usuario, $email_usuario, $pass_usuario, $conn, $id_solicitacao);

				} catch (Exception $eSolicitacao) {
					$msg = "Erro ao inserir um registro na tabela SOLICITACAO.<br>Detalhes: ".$eSolicitacao->getMessage();
					echo mensagem($msg, "danger");
				}

			}else{
				return false;
			}

		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}
}

$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
	if(isset($_GET['tabela']) && isset($_GET['id'])){
		$btn_back = '<button type="button" class="btn btn-warning" onclick="btn_back();">Voltar</button>';
		$tabela = $_GET['tabela'];
		$id = $_GET['id'];

		if($tabela == 'documento'){
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
				  d.id_documento = '{$id}'";
			$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<form action="" method="post">
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
									<th>Status</th>
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

						if($row['status_documento'] == '0'){
							$status = '<img src="img/indisponivel.png" width="25" alt="Indisponível"/>';
							$btn_solicitar = '';
						}else{
							if($row['solicitado'] == '1'){
								$status = '<img src="img/solicitado.png" width="25" alt="Indisponível"/>';
								$btn_solicitar = '';
							}else{
								$status = '<img src="img/disponivel.png" width="25" alt="Disponível"/>';
								$btn_solicitar = '<button type="submit" name="solicitar" onclick="carrega(\'carregando\');" class="btn btn-success float-right">Solicitar</button></form>';
							}
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


							echo '<tr>';
									echo '
									<input type="hidden" value="'.$row['id_documento'].'" name="doc[]"/>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
									<td>'.$processo.'</td>
									<td class="text-center">'.$status.'</td>
								</tr>
							';
						}

						echo '
							</tbody>
						</table></div>';

					echo $btn_solicitar;
					echo '</form>';
					echo $btn_back;
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
			}

		}else if($tabela == 'localizacao_has_documento'){
			require_once("bd/conexao.php");
			$sql1 = "SELECT * FROM `localizacao_has_documento` WHERE localizacao_id_localizacao = '{$id}'";

			try {

				$docs = $conn->query($sql1);
				$qnt = $docs->rowCount();

					if($qnt > 0){
						echo '
					<form action="" method="post">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th class="text-center">
									<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
									<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
								</th>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
								<th>Status</th>
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
				  d.id_documento = '{$id}'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
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

						if($row['status_documento'] == '0'){
							$status = '<img src="img/indisponivel.png" width="25" alt="Indisponível"/>';
							$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
							$btn_solicitar = '';
						}else{
							if($row['solicitado'] == '1'){
								$status = '<img src="img/solicitado.png" width="25" alt="Indisponível"/>';
								$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
								$btn_solicitar = '';
							}else{
								$status = '<img src="img/disponivel.png" width="25" alt="Disponível"/>';
								$btn_select = '<td class="text-center" style="width: 5%;"><input type="checkbox" class="doc" name="doc[]" value="'.$row['id_documento'].'"></td>';
								$btn_solicitar = '<button type="submit" name="solicitar" onclick="carrega(\'carregando\');" class="btn btn-success float-right">Solicitar</button></form>';
							}
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

							echo '<tr>';
							echo $btn_select;
								echo '
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
									<td>'.$localizacao.'</td>
									<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
									<td>'.$processo.'</td>
									<td class="text-center">'.$status.'</td>
								</tr>
							';
						}
							}

							} catch (Exception $e) {
								echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
							}
						}/*Fim do Foreach*/

						echo '
							</tbody>
						</table></div>';

					echo $btn_solicitar;
					echo '</form>';
					echo $btn_back;
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				$msg = 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
				echo mensagem($msg, "danger");
			}

		}else if($tabela == 'cliente'){
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
				  caso_cliente_id_cliente = '{$id}'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<form action="" method="post">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th class="text-center">
									<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
									<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
								</th>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
								<th>Status</th>
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

						if($row['status_documento'] == '0'){
							$status = '<img src="img/indisponivel.png" width="25" alt="Indisponível"/>';
							$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
							$btn_solicitar = '';
						}else{
							if($row['solicitado'] == '1'){
								$status = '<img src="img/solicitado.png" width="25" alt="Indisponível"/>';
								$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
								$btn_solicitar = '';
							}else{
								$status = '<img src="img/disponivel.png" width="25" alt="Disponível"/>';
								$btn_select = '<td class="text-center" style="width: 5%;"><input type="checkbox" class="doc" name="doc[]" value="'.$row['id_documento'].'"></td>';
								$btn_solicitar = '<button type="submit" name="solicitar" onclick="carrega(\'carregando\');" class="btn btn-success float-right">Solicitar</button></form>';
							}
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

							echo '<tr>';
							echo $btn_select;
								echo '
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
									<td>'.$processo.'</td>
									<td class="text-center">'.$status.'</td>
								</tr>
							';
						}

						echo '
							</tbody>
						</table></div>';

					echo $btn_solicitar;
					echo '</form>';
					echo $btn_back;
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				$msg = 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
				echo mensagem($msg, "danger");
			}

		}else if($tabela == 'caso'){
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
				  d.caso_id_caso = '{$id}'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";
			try {
				$documentos = $conn->query($sql);
				$qnt_documentos = $documentos->rowCount();


				if($qnt_documentos > 0){
					echo '
					<form action="" method="post">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-sm">
						<thead>
							<tr>
								<th class="text-center">
									<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
									<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
								</th>
								<th>Código</th>
								<th>Descrição</th>
								<th>Localização</th>
								<th>Cliente</th>
								<th>Caso</th>
								<th>Processo</th>
								<th>Status</th>
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

						if($row['status_documento'] == '0'){
							$status = '<img src="img/indisponivel.png" width="25" alt="Indisponível"/>';
							$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
							$btn_solicitar = '';
						}else{
							if($row['solicitado'] == '1'){
								$status = '<img src="img/solicitado.png" width="25" alt="Indisponível"/>';
								$btn_select = '<td  class="text-center" style="width: 5%;"><input type="checkbox" disabled></td>';
								$btn_solicitar = '';
							}else{
								$status = '<img src="img/disponivel.png" width="25" alt="Disponível"/>';
								$btn_select = '<td class="text-center" style="width: 5%;"><input type="checkbox" class="doc" name="doc[]" value="'.$row['id_documento'].'"></td>';
								$btn_solicitar = '<button type="submit" name="solicitar" onclick="carrega(\'carregando\');" class="btn btn-success float-right">Solicitar</button></form>';
							}
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

							echo '<tr>';
							echo $btn_select;
								echo '
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
									<td><a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a></td>
									<td>'.$processo.'</td>
									<td class="text-center">'.$status.'</td>
								</tr>
							';
						}

						echo '
							</tbody>
						</table></div>';

					echo $btn_solicitar;
					echo '</form>';
					echo $btn_back;
				}else{
					echo $msg_vazio;
				}

			} catch (Exception $e) {
				$msg = 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
				echo mensagem($msg, "danger");
			}

		}
		echo "<br><br>";

	}
?>

</div><!-- Div com todos as buscas -->
</div><br><br>