<input type="hidden" id="tipoLocalizacao" value="<?php echo ($_GET['tipo'] ? strtoupper($_GET['tipo']) : 'CAIXA');?>" name="tipo">
<div class="row">
	<div id="colSelectCliente" class="col-12 mt-3">
		<select id="selectClienteGerarLocalizacao" name="cliente" class="form-control">
			<?php
				require_once("bd/conexao.php");
				try {
					$sqlCliente = "SELECT * FROM cliente ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC, (0 + SUBSTRING(cod_cliente, 2)) ASC";

					$clientes = $conn->query($sqlCliente);
					$qntClientes = $clientes->rowCount();

					if($qntClientes > 0){

						foreach ($clientes as $cRow) {
							$id_cliente = $cRow['id_cliente'];
							$nome_cliente = $cRow['nome_cliente'];
							$cod_cliente = $cRow['cod_cliente'];
							$itemSelecionado = filter_input(INPUT_POST, 'cliente');
							if($itemSelecionado == $id_cliente){
								echo '<option selected value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
							}else{
								echo '<option value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
							}
						}

					}else{
						echo '<option>Nenhum cliente cadastrado</option>';
					}

				} catch (Exception $erroCliente) {
					echo $erroCliente->getMessage();
				}
			?>
		</select>
	</div>
</div>
<div id="divErros" class="hidden-xl-down"></div>
<div class="row">
	<div id="colSelectLocalizacao" class="col-12 mt-3">
		<select id="localizacaoGerarLocalicazao" name="localizacao" class="form-control">
		</select>
	</div>
</div>
<div class="row mt-3">
	<div class="col-12">
		<div class="input-group">
			<select name="prateleira" id="prateleiraGerarLocalizacao" class="form-control">
				<?php
					try {
						if($_GET['tipo'] == 'caixa'){
							$sqlPreteleira = "SELECT * FROM prateleira WHERE localizacoes_prateleira < 7 ORDER BY id_prateleira ASC";
							$prateleiras = $conn->query($sqlPreteleira);
							$qntPrateleiras = $prateleiras->rowCount();

							if($qntPrateleiras > 0){

								foreach ($prateleiras as $pRow) {
									$id_prateleira = $pRow['id_prateleira'];
									$num_prateleira = $pRow['num_prateleira'];
									$localizacoes_prateleira = $pRow['localizacoes_prateleira'];
									$itemPratSelecionado = filter_input(INPUT_POST, 'prateleira');
									if($itemPratSelecionado == $id_prateleira){
										echo '<option selected value="'.$id_prateleira.'">Prateleira '.$num_prateleira.'</option>';
									}else{
										echo '<option value="'.$id_prateleira.'">Prateleira '.$num_prateleira.'</option>';
									}
								}

							}else{
								echo '<option>Nenhum prateleira cadastrada</option>';
							}
						}else{
							echo '<option value="70">Prateleira 70</option>';
						}

					} catch (Exception $ePra) {
						echo 'prateleira - '.$ePra->getMessage();
					}
				?>
			</select>

			<span class="input-group-btn">
				<button id="btnBuscarGeralLocalizacao" class="btn btn-secondary" name="acao" type="button">Buscar</button>
			</span>
		</div>
	</div>
</div>
<div id="carregaDocumentosDesalocados"></div>