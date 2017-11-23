<?php
	require_once("bd/conexao.php");
	if(isset($_POST['id_cliente'])){
	$page = $_POST['page'];
	$id_cliente = $_POST['id_cliente'];
		try {
			switch ($page) {
				case 'gerar-etiqueta':
				echo '<div id="localizacaoEdit" >';
				$sql = "SELECT * FROM localizacao l INNER JOIN cliente c ON l.cliente_id_cliente = c.id_cliente INNER JOIN prateleira p ON l.prateleira_id_prateleira = p.id_prateleira WHERE l.etiquetada = 0 AND l.cliente_id_cliente = {$id_cliente} ORDER BY l.tipo_localizacao ASC, l.num_localizacao ASC, p.num_prateleira ASC";
				$locais = $conn->query($sql);
				$qnt1 = $locais->rowCount();
				if($qnt1 > 0){
					$select_locais = '
					<form method="post">
						<input type="hidden" value="'.$id_cliente.'" name="cliente"/>
						<div class="input-group">
							<select id="selectLocalizacaoEtiqueta" class="form-control" name="localizacao">';
								foreach ($locais as $row){
									$descricao = $row['tipo_localizacao']." ".$row['num_localizacao']." - P ".$row['num_prateleira'];
									$cliente = $row['nome_cliente'];
									$id_localizacao	= $row['id_localizacao'];
									$select_locais .= '<option value="'.$id_localizacao.'">'.$descricao.'</option>';
								}
							$select_locais .= '
							</select>
							<span class="input-group-btn">
								<button class="btn btn-info" onclick="geraEtiqueta('.$id_cliente.');" name="gerar" type="button">Gerar</button>
							</span>
						</div>
					</form>';
					echo "<br>".$select_locais;
				}else{
					$msg = 'Nenhuma localização encontrada!';
					echo mensagem_close($msg, "danger");
					echo '<script>$("#'.$id_cliente.'-cli").remove();</script>';
					echo '<script>readLocalizacoes("#readLocalizacoes", "gerar-etiqueta");</script>';
				}
				echo '</div>';
				break;
				case 'editar-localizacao':
				echo '<div id="localizacaoEdit">';
				if($id_cliente != 0){
					$sql = "SELECT * FROM localizacao l INNER JOIN cliente c ON l.cliente_id_cliente = c.id_cliente INNER JOIN prateleira p ON l.prateleira_id_prateleira = p.id_prateleira INNER JOIN caso cs ON l.caso_id_caso = cs.id_caso WHERE l.cliente_id_cliente = {$id_cliente} ORDER BY cs.num_caso ASC";
				}else{
					$sql = "SELECT * FROM localizacao l INNER JOIN cliente c ON l.cliente_id_cliente = c.id_cliente INNER JOIN prateleira p ON l.prateleira_id_prateleira = p.id_prateleira INNER JOIN caso cs ON l.caso_id_caso = cs.id_caso ORDER BY cs.num_caso ASC";
				}
				$locais = $conn->query($sql);
				$qnt1 = $locais->rowCount();
					if($qnt1 > 0){
						$table_locais = '
						<br>
						<div class="table-responsive">
						<table id="locEdit" class="table table-bordered table-sm table-striped">
							<thead>
								<tr>
									<td colspan="5" class="text-center">Total de registros: '.$qnt1.'</td>
								</tr>
								<tr>
									<th class="text-center">Descrição da localização</th>
									<th class="text-center">Cliente Vinculado</th>
									<th class="text-center">Descrição do Caso</th>
									<th class="text-center" colspan="2">Ação</th>
								</tr>
							</thead>
							<tbody>';
								foreach ($locais as $row) {
									$id_localizacao	= $row['id_localizacao'];
									$descricao = $row['tipo_localizacao']." ".$row['num_localizacao']." - P ".$row['num_prateleira'];
									$cliente = $row['nome_cliente'];
									$num_caso = $row['num_caso'];
									$caso = $row['descricao_caso'];
									$table_locais .= '
									<tr id="tr-'.$id_localizacao.'">
										<td>'.$descricao.'</td>
										<td>'.$cliente.'</td>
										<td>'.$num_caso.' - '.$caso.'</td>
										<td style="width:5%" class="text-center">
											<a style="width:100%" class="btn btn-info btn-sm" href="?pg=form-editar-localizacao&id='.$id_localizacao.'">Editar</a>
										</td>
										<td style="width:5%" class="text-center">
											<button style="width:100%" type="button" class="btn btn-danger btn-sm" onclick="delLocalizacao('.$id_localizacao.');">Excluir</button>
										</td>
									</tr>
								';
								}
								$table_locais .= '
									</tbody>
								</table>
								</div>
							';
						echo $table_locais;
					}else{
						$msg = 'Nenhuma localização encontrada!';
						echo mensagem_close($msg, "danger");
					}
				echo '</div>';
				break;
				case 'gerar-localizacao':
					// echo '<option>Deixa de ser Fulero!</option>';
					$id_caso = $_POST['id_caso'];
					$tipo = $_POST['tipo'];

					try {//Cria uma lista com as localizações existentes
						$sqlLocalizacao = "SELECT * FROM localizacao l INNER JOIN prateleira p ON l.prateleira_id_prateleira = p.id_prateleira WHERE l.cliente_id_cliente = {$id_cliente} AND tipo_localizacao = '{$tipo}' AND l.caso_id_caso = {$id_caso} ORDER BY l.tipo_localizacao ASC, l.num_localizacao ASC, p.num_prateleira ASC";

						$locais = $conn->query($sqlLocalizacao);
						$qntLocalizacaoes = $locais->rowCount();
						$nextNumLocal = 1+$qntLocalizacaoes;

						if($qntLocalizacaoes > 0){

							echo '<option id="0" value="'.$nextNumLocal.'">Criar nova '.strtolower($tipo).' (Número '.$nextNumLocal.')</option>';

							foreach ($locais as $lRow) {
								$id_localizacao = $lRow['id_localizacao'];
								$num_localizacao = $lRow['tipo_localizacao']." ".$lRow['num_localizacao'];
								$prateleira = $lRow['num_prateleira'];
								$localSelecionado = filter_input(INPUT_POST, 'localizacao');
								if($localSelecionado == $id_localizacao){
									echo '<option id="'.$id_localizacao.'" value="'.$lRow['num_localizacao'].'">'.$num_localizacao.' - P'.$prateleira.'</option>';
								}else{
									echo '<option id="'.$id_localizacao.'" value="'.$lRow['num_localizacao'].'">'.$num_localizacao.' - P'.$prateleira.'</option>';
								}
							}

						}else{
							echo '<option id="0" value="'.$nextNumLocal.'">Criar nova '.strtolower($tipo).' (Número '.$nextNumLocal.')</option>';
						}

					} catch (Exception $e1) {
						echo "Mensagem 1: ".$e1->getMessage();
					}
				break;
			}

		} catch (Exception $e) {
			$msg = "Erro ao listar as localizações!<br>Detalhes: ".$e->getMessage();
			echo mensagem_close($msg, "danger");
		}
	}else{
		$msg = "Não há nenhuma localização sem etiqueta.";
			echo mensagem_close($msg, "info");
	}

?>
</div>