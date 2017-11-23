<div id="buscaCarregada">
<?php
	$tipo_usuario = filter_input(INPUT_POST, 'tipo');
	$tabela = strip_tags(trim(filter_input(INPUT_POST, 'tabela')));
	$termo = strip_tags(trim(filter_input(INPUT_POST, 'termo')));
	$termo = str_replace("'", "\'", $termo);

	switch ($tabela) {
		case 'cliente':
			listaCliete($termo);
		break;
		case 'documento':
			listaDocumento($termo, $tipo_usuario);
		break;
		case 'geral':
			listaGeral($termo, $tipo_usuario);
		break;
	}

	function listaDocumento($termo, $tipo = null){
		$termoAviso = str_replace("\'", "'", $termo);
		require_once("bd/conexao.php");
		if($termo == ''){
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
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";

			$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
			$inforTop = '<th class="text-center hidden-print" colspan="8"><h5>Resultado da pesquisa</h5></td>';
		}else{
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
				  d.descricao_documento LIKE '%{$termo}%' OR d.cod_documento LIKE '%{$termo}%' OR d.obs_documento LIKE '%{$termo}%' OR d.processo_documento LIKE '%{$termo}%' OR c.nome_cliente LIKE '%{$termo}%'  OR cs.descricao_caso LIKE '%{$termo}%'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";

			$msg_vazio = '<div class="alert alert-info text-center">O termo <strong>'.$termoAviso.'</strong> não retornou nenhum resultado.</div>';
			$inforTop = '<th class="text-center hidden-print" colspan="8"><h5>Resultado para o termo: <strong>'.$termoAviso.'</strong></h5></td>';
		}

		try {
			$documentos = $conn->query($sql);
			$qnt_documentos = $documentos->rowCount();


			if($qnt_documentos > 0){
				echo '
				'.(($tipo == '3' OR $tipo == '4') ? '<form action="" method="post">' : '').'
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-sm">
					<thead>
						<tr>
							'.$inforTop.'
						</tr>
						<tr>
							'.(($tipo == '3' OR $tipo == '4') ? '
							<th class="text-center">
								<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
								<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
							</th>' : '').'
							<th>Código</th>
							<th>Descrição</th>
							<th>Localização</th>
							<th>Cliente</th>
							<th>Caso</th>
							<th>Processo</th>
							'.(($tipo == '3' OR $tipo == '4') ? '<th>Status</th>' : '').'
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
						$localizacao = 'Indefinida';
						$id_localizacao = 0;
					}

					if(($tipo == '3' OR $tipo == '4')){
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
								$btn_solicitar = '<button type="submit" name="solicitar" class="btn btn-success float-right">Solicitar</button></form>';
							}
						}
					}else{
						$status = '';
						$btn_select = '';
						$btn_solicitar = '';
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
						<tr>';
						if(($tipo == '3' OR $tipo == '4')){
							echo $btn_select;
							echo '
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a>
							</td>
							<td>'.$localizacao.'</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a>
							</td>
							<td>'.$processo.'</td>
							<td class="text-center">'.$status.'</td>';
						}else{
							echo '
							<td>
								<a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a>
							</td>
							<td>'.$processo.'</td>';
						}

						echo '
						</tr>
					';
				}

				echo '
					</tbody>
				</table></div>
					'.$btn_solicitar.'
				<br>
				<br>
				';
			}else{
				echo $msg_vazio;
			}

		} catch (Exception $e) {
			echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
		}
	}


	function listaCliete($termo){
		$termoAviso = str_replace("\'", "'", $termo);
		require_once("bd/conexao.php");
		if($termo == ''){
			$sql = "
				SELECT
				  *
				FROM
				  cliente
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC
			";
			$msg_vazio = '<div class="alert alert-info text-center"><strong>Nenhum registro encontrado.</strong></div>';
			$inforTop = '<th class="text-center hidden-print" colspan="8"><h5>Resultado da pesquisa</h5></td>';
		}else{
			$sql = "
				SELECT
				  *
				FROM
				  cliente
				WHERE
				  nome_cliente LIKE '%{$termo}%' OR cod_cliente LIKE '%{$termo}%'
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC
			";

			$msg_vazio = '<div class="alert alert-info text-center">O termo <strong>'.$termoAviso.'</strong> não retornou nenhum resultado.</div>';
			$inforTop = '<th class="text-center hidden-print" colspan="8"><h5>Resultado para o termo: <strong>'.$termoAviso.'</strong></h5></td>';
		}

		try {
			$clientes = $conn->query($sql);
			$qnt_clientes= $clientes->rowCount();

			if($qnt_clientes > 0){
				echo '
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-sm">
					<thead>
						<tr>
							'.$inforTop.'
						</tr>
						<tr>
							<th>Código</th>
							<th>Nome ou Razão Social</th>
							<th>Documento(s) Vinculado(s)</th>
						</tr>
					</thead>

					<tbody>
				';
				foreach ($clientes as $row) {
					$id_cliente = $row['id_cliente'];
					echo '
						<tr>
							<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["cod_cliente"].'</a></td>
							<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>
							<td>
								';
								try {
									$query1 = "
									SELECT
									  *
									FROM
									  documento d
									WHERE
									  d.caso_cliente_id_cliente = {$id_cliente}
									";
									$documentos = $conn->query($query1);
									$qnt_documentos = $documentos->rowCount();
									if($qnt_documentos > 0){
										if($qnt_documentos == 1){
											$info = 'Existe 1 documento vinculado';
											$link = '<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$info.'</a>';
											echo $link;
										}else{
											$info = 'Existem '.$qnt_documentos.' documentos vinculados';
											$link = '<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$info.'</a>';
											echo $link;
										}

									}else{
										$info = 'Nenhum documento vinculado';
										echo $info;
									}

								} catch (Exception $e1) {
									$msg1 = "Erro ao listar os documentos do cliente!<br>".$e1->getMessage();
									echo mensagem($msg1, "danger");
								}

								echo '
							</td>
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
			echo 'Erro ao listar os clientes cadastrados!<br>Detalhes: '.$e->getMessage();
		}
	}

	function listaGeral($termo, $tipo){
		$termoAviso = str_replace("\'", "'", $termo);
		require_once("bd/conexao.php");
		if($termo == ''){
			echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
				<span aria-hidden="true">&times;</span>
				</button><strong>Informe um termo para realizar a pesquisa</strong></div>';
		}else if(strlen($termo) < 2){
			echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
				<span aria-hidden="true">&times;</span>
				</button><strong>informe no mínimo 2 caracteres para a pesquisa</strong></div>';
		}else{
			$palavra = explode(" ", $termo);
			$qnt_palavras = count($palavra);
			$sql = "
				SELECT DISTINCT
				  id_doc
				FROM
				  vw_dados
				WHERE";
				if($qnt_palavras > 1){
					$sql .= " (dados LIKE '%".$palavra[0]."%')";
					for ($i=1; $i < $qnt_palavras; $i++) {
						$sql .= " AND (dados LIKE '%".$palavra[$i]."%')";
					}
				}else{
					$sql .= " dados LIKE '%".$palavra[0]."%'";
				}

			$msg_vazio = '
				<div class="alert alert-info alert-dismissible fade show text-center" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
				<span aria-hidden="true">&times;</span>
				</button>
					Sua busca por <strong>"'.$termoAviso.'"</strong> não retornou nenhum resultado.
				</div>
			';

			$inforTop = '<th class="text-center hidden-print" colspan="8"><h5>Resultado para o termo: <strong>'.$termoAviso.'</strong></h5></td>';

			try {
				$dados = $conn->query($sql);
				$qnt_dados= $dados->rowCount();
				// $sql_doc = 'SELECT * FROM documento d INNER JOIN cliente c ON d.caso_cliente_id_cliente = c.id_cliente WHERE';
				if($qnt_dados > 0){
					foreach ($dados as $dado) {
						$id_documento[] = $dado['id_doc'];
					}
					$sql_doc = "
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
					WHERE d.id_documento IN (".implode(', ', array_map('intval', $id_documento)).")
					ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC";

					$documentos = $conn->query($sql_doc);

					echo '
				'.(($tipo == '3' OR $tipo == '4') ? '<form action="" method="post">' : '').'
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-sm">
					<thead>
						<tr>
							'.$inforTop.'
						</tr>
						<tr>
							'.(($tipo == '3' OR $tipo == '4') ? '
							<th class="text-center">
								<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
								<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
							</th>' : '').'
							<th>Código</th>
							<th>Descrição</th>
							<th>Localização</th>
							<th>Cliente</th>
							<th>Caso</th>
							<th>Processo</th>
							'.(($tipo == '3' OR $tipo == '4') ? '<th>Status</th>' : '').'
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
						$localizacao = 'Indefinida';
						$id_localizacao = 0;
					}

					if(($tipo == '3' OR $tipo == '4')){
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
								$btn_solicitar = '<button type="submit" name="solicitar" class="btn btn-success float-right">Solicitar</button></form>';
							}
						}
					}else{
						$status = '';
						$btn_select = '';
						$btn_solicitar = '';
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
						<tr>';
						if(($tipo == '3' OR $tipo == '4')){
							echo $btn_select;
							echo '
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$row['cod_documento'].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a>
							</td>
							<td>'.$localizacao.'</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a>
							</td>
							<td>'.$processo.'</td>
							<td class="text-center">'.$status.'</td>';
						}else{
							echo '
							<td>
								<a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=localizacao_has_documento&id='.$id_localizacao.'">'.$localizacao.'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a>
							</td>
							<td>
								<a class="listItem" href="?pg=buscar&tabela=caso&id='.$id_caso.'">'.$caso.'</a>
							</td>
							<td>'.$processo.'</td>';
						}

						echo '
						</tr>
					';
				}

				echo '
					</tbody>
				</table></div>
					'.$btn_solicitar.'
				<br>
				<br>
				';

				}else{
					echo $msg_vazio;
				}


			} catch (Exception $e) {
				echo  mensagem("Erro ao listar os clientes cadastrados!<br>Detalhes: ".$e->getMessage(), "danger");
			}
		}

	}

?>

</div>