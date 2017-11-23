<?php
	if (isset($_GET['id'])) {

	require_once("bd/conexao.php");

	$id = $_GET['id'];
		try {

			$localizacao = $conn->query("SELECT * FROM localizacao loc INNER JOIN prateleira pra ON loc.prateleira_id_prateleira = pra.id_prateleira INNER JOIN caso cs ON loc.caso_id_caso = cs.id_caso WHERE id_localizacao = {$id}")->fetch();
			$id_prateleira_atual = $localizacao['prateleira_id_prateleira'];
			$tipo_loc = $localizacao['tipo_localizacao'];
			$num_loc = $localizacao['num_localizacao'];
			$descricao = $tipo_loc." ".$num_loc;

			$sql1 = "SELECT * FROM `localizacao_has_documento` WHERE localizacao_id_localizacao = '{$id}'";

				try {

					$docs = $conn->query($sql1);
					$qnt = $docs->rowCount();
					$cont = 0;

						if($qnt > 0){
			echo '
			<br><form action="" method="post">

				<div class="row">
					<div class="col-lg">
						<div class="input-group">
							<span class="input-group-addon col-lg"><h5>'.$descricao.'</h5></span>
						</div>
					</div>
				</div>
				<br>
				<input type="hidden" name="prateleira_atual" value="'.$id_prateleira_atual.'">
				<div class="row">
					<div class="col-md-6 col-12 input-group">
						<select name="prateleira" class="form-control">';
						try {
							if($_GET['tipo'] == 'caixa'){
								$sql = "SELECT * FROM prateleira WHERE localizacoes_prateleira < 7 ORDER BY id_prateleira ASC";
							}else{
								$sql = "SELECT * FROM prateleira WHERE localizacoes_prateleira < 14 ORDER BY id_prateleira ASC";
							}

							$prateleiras = $conn->query($sql);
							$qntPrateleiras = $prateleiras->rowCount();

							if($qntPrateleiras > 0){

								foreach ($prateleiras as $pRow) {
									$id_prateleira = $pRow['id_prateleira'];
									$num_prateleira = $pRow['num_prateleira'];
									$localizacoes_prateleira = $pRow['localizacoes_prateleira'];
									if($id_prateleira_atual == $id_prateleira){
										echo '<option selected value="'.$id_prateleira.'">Prateleira '.$num_prateleira.'</option>';
									}else{
										echo '<option value="'.$id_prateleira.'">Prateleira '.$num_prateleira.'</option>';
									}
								}

							}else{
								echo '<option>Nenhum prateleira cadastrada</option>';
							}

						} catch (Exception $ePra) {
							echo 'prateleira - '.$ePra->getMessage();
						}
					echo '</select>
					</div>
					<div class="col-md-6 col-12 input-group">
						<span class="input-group-addon">'.$tipo_loc.'</span>
						<input type="number" class="form-control" value="'.$num_loc.'" name="num_localizacao">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-lg">
						<div class="input-group table-responsive">
							<table class="table table-striped table-bordered table-sm">
								<thead>
									<tr>
										<th>Código</th>
										<th>Descrição</th>
										<th>Cliente Vinculado</th>
										<th>Descrição do Caso</th>
										<th>Processo Vinculado</th>
										<th>Ação</th>
									</tr>
								</thead>

								<tbody>
							';
							foreach ($docs as $doc) {
								$cont++;
								$id_Doc = $doc['documento_id_documento'];
								$sql = "SELECT * FROM documento d INNER JOIN cliente c ON d.caso_cliente_id_cliente = c.id_cliente INNER JOIN caso cs ON d.caso_id_caso = cs.id_caso WHERE id_documento = '{$id_Doc}'";

								try {
									$documentos = $conn->query($sql);
									$qnt_documentos = $documentos->rowCount();

									foreach ($documentos as $row) {
									$id_documento = $row['id_documento'];
									$id_cliente = $row['id_cliente'];
									if($row['alocado'] == '1'){
										$id_local = $conn->query("SELECT * FROM localizacao_has_documento WHERE documento_id_documento = {$id_documento}")->fetch();
											$id_localizacao = $id_local[0];
										$nome_localizacao = $conn->query("SELECT * FROM localizacao WHERE id_localizacao = {$id_localizacao}")->fetch();
											$localizacao = $nome_localizacao[5]." ".$nome_localizacao[1];

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

									if(strlen($row["descricao_caso"]) > 30){
										$caso = "<small>".$row["descricao_caso"]."</small>";
									}else{
										$caso = $row["descricao_caso"];
									}

									echo '
										<tr>
								<td>
									<a class="listItem" href="?pg=editar-documento&filter_doc='.$row['id_documento'].'">'.$row['cod_documento'].'</a></td>
											<td><a class="listItem" href="?pg=buscar&tabela=documento&id='.$row['id_documento'].'">'.$descricao.'</a></td>
											<td><a class="listItem" href="?pg=buscar&tabela=cliente&id='.$id_cliente.'">'.$row["nome_cliente"].'</a></td>';
											if($cont == 1 OR $cont == $qnt){
												echo '
											<td>'.$caso.'</td>
											<td>'.$processo.'</td>
											<td class="text-center">
												<a class="btn btn-warning btn-sm" href="?pg=form-editar-localizacao&id='.$id.'&del=1&id_doc='.$row['id_documento'].'">Remover</a>
											</td>';
											}else{
												echo '
											<td>'.$caso.'</td>
											<td colspan="2">'.$processo.'</td>
											';
											}
											echo '
										</tr>
									';
									}

								} catch (Exception $e) {
									echo 'Erro ao listar os documentos cadastrados!<br>Detalhes: '.$e->getMessage();
								}
							}/*Fim do Foreach*/

						echo '
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<br>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
				<button type="button" class="btn btn-warning" onclick="location.href=\'?pg=editar-localizacao&id_cliente='.$id_cliente.'\'">Voltar</button>
			</div>
			<br>

		</form>
					';
					}else{
						echo mensagem("Nenhuma localização foi encontrada com este ID", "danger");
					}

				} catch (Exception $eLoc) {
					echo $eLoc->getMessage();
				}
			?>
<?php

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}else{
		header("location: index.php?pg=editar-localizacao");
	}

	if(isset($_POST['btn_salvar'])){
		$id_localizacao = $_GET['id'];
		$num_localizacao = $_POST['num_localizacao'];
		$id_prateleira = $_POST['prateleira'];
		$prateleira_atual = $_POST['prateleira_atual'];

		try {

			$up = $conn->query("UPDATE localizacao SET prateleira_id_prateleira = {$id_prateleira}, num_localizacao = {$num_localizacao} WHERE id_localizacao = {$id_localizacao}");
			if($up){
				$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira + 1) WHERE id_prateleira = {$id_prateleira}");
				$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira - 1) WHERE id_prateleira = {$prateleira_atual}");
				echo mensagem("Localização atualizada com sucesso", "success", "form-editar-localizacao&id=".$id_localizacao, "500");
			}else{
				return false;
			}
		} catch (Exception $erroLoc) {
			echo $erroLoc->getMessage()."Erro ao salvar localização";
		}

	}

	if(isset($_GET['del']) && $_GET['del'] == 1){
			$id_doc = $_GET['id_doc'];
			$id = $_GET['id'];

			/*Guarda o ID da localização atual do documento*/
			$get_loc = $conn->query("SELECT localizacao_id_localizacao FROM localizacao_has_documento WHERE documento_id_documento = $id_doc");
			if($get_loc->rowCount() > 0){
				$id_loc = $get_loc->fetch();
				/*Deleta o documento selecionado de sua localização atual*/
				$delLoc = $conn->query("DELETE FROM localizacao_has_documento WHERE documento_id_documento = $id_doc");
				/*Verifica se ainda existem documentos na localização*/
				if($conn->query("SELECT * FROM localizacao_has_documento WHERE localizacao_id_localizacao = $id_loc[0]")->rowCount() > 0){
					/*Se existem documentos, remove a flag "etiquetada" da localização*/
					$conn->query("UPDATE localizacao SET etiquetada = 0 WHERE id_localizacao = {$id_loc[0]}");
					/*Deleta a antiga etiqueta*/
					$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = {$id_loc[0]}");
				}else{
					/*Se não existem mais documentos, deleta a localização.*/
					/*Deleta a antiga etiqueta*/
					$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = {$id_loc[0]}");
					/*Deleta a localização*/
					$conn->query("DELETE FROM localizacao WHERE id_localizacao = {$id_loc[0]}");
				}
				/*Desaloca o documento selecionado*/
				$conn->query("UPDATE documento SET alocado = NULL WHERE id_documento = $id_doc");
				echo mensagem("Documento removido da localização!", "info", "form-editar-localizacao&id=".$id, "500");
			}else{
				echo mensagem("Id não encontrado!", "danger", "form-editar-localizacao&id=".$id, "1000");
			}

		}

?>