<br>
<form action="" method="post">
	<div class="row">
		<div class="col-lg">
			<div class="input-group">

				<select name="cliente" class="form-control">
					<option value="0">TODOS OS CLIENTES</option>
					<?php
					require_once("bd/conexao.php");
						try {
							$sql = "SELECT * FROM cliente ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC, (0 + SUBSTRING(cod_cliente, 2)) ASC";

							$clientes = $conn->query($sql);
							$qnt = $clientes->rowCount();

							if($qnt > 0){

								foreach ($clientes as $cRow) {
									$id_cliente = $cRow['id_cliente'];
									$cod_cliente = $cRow['cod_cliente'];
									$nome_cliente = $cRow['nome_cliente'];
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

						} catch (Exception $e1) {
							echo $e1->getMessage();
						}
					?>
				</select>

				<select name="tipo" class="form-control">
					<option <?php echo (filter_input(INPUT_POST, 'tipo') == '0')? 'selected': ''; ?> value="0">CAIXA E PASTA</option>
					<option <?php echo (filter_input(INPUT_POST, 'tipo') == 'CAIXA')? 'selected': ''; ?> value="CAIXA">CAIXA</option>
					<option <?php echo (filter_input(INPUT_POST, 'tipo') == 'PASTA')? 'selected': ''; ?> value="PASTA">PASTA</option>
				</select>

				<span class="input-group-btn">
					<button id="buscar" class="btn btn-secondary" name="buscar" type="submit">Buscar</button>
				</span>
			</div>
		</div>
	</div>
</form>
<br>
<form action="pdf/gerador.php" method="POST" target="_blank">
<div class="row">
	<div class="col-lg">
		<div class="input-group">
			<span class="input-group-addon pointer">
				<input class="pointer" checked type="radio" value="nao" name="marcar">
			</span>
			<input type="text" disabled class="form-control" value="NÃO MARCAR COMO IMPRESSA">
			<span class="input-group-addon pointer">
				<input class="pointer" type="radio" value="sim" name="marcar">
			</span>
			<input type="text" disabled class="form-control" value="MARCAR COMO IMPRESSA">
		</div>
	</div>
</div>
<?php

ob_start(); // Ativa o buffer de saida do PHP
$marcarImpressa = '';

$where = ' WHERE impressa = 0';

if(isset($_POST['buscar'])){
	$cliente = $_POST['cliente'];
	$tipo = $_POST['tipo'];

	if($cliente != '0' && $tipo == '0'){
		$where = ' WHERE impressa = 0 AND localizacao_cliente_id_cliente = '.$cliente;
	}else if($cliente == '0' && $tipo != '0'){
		$where = ' WHERE impressa = 0 AND tipo_etiqueta = \''.$tipo.'\'';
	}else if($cliente != '0' && $tipo != '0'){
		$where = ' WHERE impressa = 0 AND localizacao_cliente_id_cliente = \''.$cliente.'\' AND tipo_etiqueta = \''.$tipo.'\'';
	}
}

try {

	$sql = "SELECT * FROM etiqueta e INNER JOIN cliente c ON e.localizacao_cliente_id_cliente
 = c.id_cliente{$where} ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC, (0 + SUBSTRING(cod_cliente, 2)) ASC";

	$result = $conn->query($sql);
	$qnt = $result->rowCount();
	$row = $result->fetchAll();
	$html = '';
	$cont = 3;

	if($qnt > 0){
		for ($i=0; $i < $qnt; $i++) {
			if($qnt == 1){
				$html .= $row[0][1].'<columnbreak />';
				$marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
				break;
			}else if($qnt == 2){
				$html .= $row[0][1].'<columnbreak />';
				$html .= $row[1][1];
				$marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
				break;
			}else if($qnt == 3){
				$html .= $row[0][1];
				$html .= $row[1][1];
				$html .= $row[2][1].'<columnbreak />';
				$marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
				break;
			}else if($i == $cont){
				$cont += 4;
				$html .= $row[$i][1].'<columnbreak />';
				$marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
			}else{
				$html .= $row[$i][1];
				$marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
			}
		}

		echo $html;

		$buffer = ob_get_contents(); // Obtém os dados do buffer interno
		$filename =  "pdf/code.html"; // Nome do arquivo HTML
		file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML
	echo '
	<div class="row">
		<div class="col-lg-offset-3 col-lg-12">
			<div class="input-group">
				<span class="input-group-addon col">
				</span>
				<span class="input-group-btn">
					<input class="btn btn-success" type="submit" name="acao" value="Gerar PDF">
				</span>
				<span class="input-group-addon col">
				</span>
			</div>
		</div>
	</div>
	<br>
	<br>
	';

	}



} catch (Exception $e) {
	$msg = 'Erro ao carregar as tabelas!<div>Detalhes: '.$e->getMessage();
	echo mensagem($msg, "warning");
}

echo '
<textarea style="display:none;" name="sql">'.$marcarImpressa.'</textarea>
';
?>

</form>