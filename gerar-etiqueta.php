<script type="text/javascript">

	$(document).ready(function(){
		$("#buscar").trigger('click');
	});

</script>
<?php	require_once("bd/conexao.php"); $destino = ''; ?>
<br>
	<div class="row">
		<div class="col-lg">
			<div class="input-group">

				<select id="clienteGerarEtiqueta" name="cliente" class="form-control">
					<?php
						$sql = "SELECT * FROM cliente ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC, (0 + SUBSTRING(cod_cliente, 2)) ASC";
						try {

							$clientes = $conn->query($sql);
							$qnt = $clientes->rowCount();

							if($qnt > 0){
								foreach ($clientes as $cRow) {
									$id_cliente = $cRow['id_cliente'];
									$cod_cliente = $cRow['cod_cliente'];
									$nome_cliente = $cRow['nome_cliente'];
									$sqlLoc = "SELECT * FROM localizacao l INNER JOIN cliente c ON l.cliente_id_cliente = c.id_cliente WHERE l.etiquetada = 0 AND l.cliente_id_cliente = {$id_cliente}";
									$contLoc = $conn->query($sqlLoc)->rowCount();
									if($contLoc > 0){
										if(isset($_GET['idCli'])){
											$itemSelecionado = $_GET['idCli'];
										}else{
											$itemSelecionado = 0;
										}
										if($itemSelecionado == $id_cliente){
											echo '<optiona id="'.$id_cliente.'-cli" selected value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
										}else{
											echo '<option id="'.$id_cliente.'-cli" value="'.$id_cliente.'">'.$cod_cliente.' - '.$nome_cliente.'</option>';
										}
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
	<div id="retorno"></div>
	<div id="readLocalizacoes"></div>
<br>

<div class="row text-center">
	<div id="carregaEtiqueta" class="col-lg">
<?php
	if(isset($_POST['salva-etiqueta'])){
		$id_cliente = $_POST['id_cliente'];
		$id_local = $_POST['id_local'];
		$tipo = $_POST['tipo'];
		$filename =  "temp/etiqueta.html";
		$html = file_get_contents($filename);

		try {
			$etiqueta = array(
				':html' => $html,
				':tipo' => $tipo,
				':id_localizacao' => $id_local,
				':id_cliente' => $id_cliente,
				':impressa' => 0
			);
			$sql = "INSERT INTO etiqueta (html_etiqueta, tipo_etiqueta, localizacao_id_localizacao, localizacao_cliente_id_cliente, impressa) VALUES (:html, :tipo, :id_localizacao, :id_cliente, :impressa)";
			$insert = $conn->prepare($sql);
			$marcaEtiquetada = $conn->query("UPDATE localizacao SET etiquetada = 1 WHERE id_localizacao = {$id_local}");

			if($marcaEtiquetada){
				$acao = $insert->execute($etiqueta);

				if($acao){
					$msg = 'Etiqueta salva com sucesso!';
/*Este bloco verifica se ainda existem localizações sem etiquetas para o cliente, caso exista, direciona para página com o cliente selecionado*/
					$verifica = "SELECT * FROM localizacao l INNER JOIN cliente c ON l.cliente_id_cliente = c.id_cliente WHERE l.etiquetada = 0 AND l.cliente_id_cliente = {$id_cliente}";
					$verificaLocalizacoes = $conn->query($verifica);
					$qntV = $verificaLocalizacoes->rowCount();

					if($qntV > 0){
						// echo mensagem($msg, "success", "gerar-etiqueta&idCli=".$id_cliente, "500");
					}else{
						// echo mensagem($msg, "success", "gerar-etiqueta", "500");
					}

				}else{
					$msg = 'Erro ao tentar salvar etiqueta!';
					echo mensagem($msg, "danger", "gerar-etiqueta", "2000");
				}
			}

		} catch (Exception $e01) {
			echo $e01->getMessage();
		}

	}

?>
	</div>
</div>