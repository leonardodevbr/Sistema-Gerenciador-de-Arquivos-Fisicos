<br>
<form method="post" action="?pg=editar-localizacao">
	<div class="row">
		<div class="col-lg">
			<label for="descricao">Cliente Associado</label>
			<div class="input-group">
				<select id="clienteLocalizacaoEdit" name="cliente" class="form-control">
					<?php
						require_once("bd/conexao.php");
						$sql = "SELECT * FROM cliente ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC, (0 + SUBSTRING(cod_cliente, 2)) ASC";

						try {

							$clientes = $conn->query($sql);
							$qnt = $clientes->rowCount();

							if($qnt > 0){
								echo '<option value="0">TODOS</option>';
								foreach ($clientes as $cRow) {
									$id_cliente = $cRow['id_cliente'];
									$nome_cliente = $cRow['nome_cliente'];
									$cod_cliente = $cRow['cod_cliente'];
									$itemSelecionado = filter_input(INPUT_GET, 'id_cliente');
									if($itemSelecionado == $id_cliente){
										echo '<option selected value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
									}else{
										echo '<option value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
									}
								}
							}else{
								echo '<option>Nenhum cliente cadastrado</option>';
							}

						} catch (Exception $e1) {
							echo $e1->getMessage();
						}
					?>
				</select>
			</div>
		</div>
	</div>
</form>
<br>
<div id="retorno"></div>
<div id="readLocalizacoes"></div>