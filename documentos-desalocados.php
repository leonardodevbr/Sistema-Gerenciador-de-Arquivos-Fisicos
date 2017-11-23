<?php
require_once("bd/conexao.php");
$id_cliente = $_POST['id_cliente'];
$id_caso = $_POST['id_caso'];
$tipo = $_POST['tipo'];

$andCaso = ($id_caso != 0 ? " AND (d.caso_id_caso = {$id_caso}) " : ' ');

if($tipo != "CAIXA" && $tipo != "PASTA"){
	$tipo = "CAIXA";
}

	try {

		$sql = "
		SELECT
		  *
		FROM
		  documento d
		INNER JOIN
		  cliente c
		ON
		  d.caso_cliente_id_cliente = c.id_cliente
		WHERE
		  c.id_cliente = {$id_cliente} AND (d.alocado IS NULL OR d.alocado = 0)".$andCaso."ORDER BY SUBSTRING(cod_cliente, 1, 1) ASC,
		  (0 + SUBSTRING(cod_cliente, 2)) ASC";

		$documentos = $conn->query($sql);
		$qnt = $documentos->rowCount();
		$dRow = $documentos->fetchAll();

		if($qnt > 0){
			echo '
			<div class="row mt-3">
				<div class="col-lg-offset-3 col-lg-12">
					<div class="input-group">
						<span class="input-group-addon col">
						</span>
						<span class="input-group-btn">
							<button onclick="exec();" id="btnExecutarGerarLocalizacao" class="btn btn-sm btn-primary" name="gerar" type="button">Executar</button>
						</span>
						<span class="input-group-addon col">
						</span>
					</div>
				</div>
			</div>
			<div class="text-center table-responsive mt-3">
			<table class="table table-sm table-striped table-bordered">
				<thead style="color:#333;">
					<tr>
						<td colspan="3">Número de documentos encontrados: '.$qnt.'</td>
					</tr>
					<tr>
						<th class="text-center">
							<input type="button" title="Todos" class="btn btn-sm btn-info pointer" style="height:15px; width:15px;border: none;" onclick="marcardesmarcar();">
							<input type="button" title="Limpar" class="btn btn-sm btn-danger pointer" style="height:15px; width:15px;border: none;" onclick="desmarcar();">
						</th>
						<th class="text-left">Código</th>
						<th class="text-left">Descrição</th>
					</tr>
				</thead>
				<tbody>
			';
			for ($i=0; $i < $qnt; $i++) {
				echo '
					<tr>
						<td style="text-align:center;">
							<input type="checkbox" class="doc" id="'.($i+1).'" name="doc[]" value="'.$dRow[$i]['id_documento'].'" onclick="marcarUp(this.id);">
						</td>
						<td style="text-align:left;"><label for="'.($i+1).'">'.$dRow[$i]['cod_documento'].'</label></td>
						<td style="text-align:left;"><label for="'.($i+1).'">'.$dRow[$i]['descricao_documento'].'</label></td>
					</tr>
				';
			}

			echo '
				</tbody>
			</table>
			</div>
			';
		}else{
			$msg = 'Nenhum documento a ser alocado!';
			echo mensagem($msg, "warning");
		}

	} catch (Exception $e2) {
		echo "Mensagem 2: ".$e2->getMessage();
	}
// $data['1'] = $_POST['id_cliente'];
// $data['2'] = $_POST['id_caso'];
// $data['3'] = $_POST['tipo'];

// var_dump($data);
?>