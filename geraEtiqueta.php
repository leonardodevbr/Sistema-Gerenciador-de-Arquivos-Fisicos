<div id="etiquetasCarregadas">
<?php
	require_once("bd/conexao.php");
	$id_local = $_POST['localizacao'];
	$id_cliente = $_POST['cliente'];

	try {
		$cliente = $conn->query("SELECT * FROM cliente WHERE id_cliente = '{$id_cliente}'")->fetch();
		$nome_cliente_etiqueta = $cliente['nome_cliente'];
		$cod_cliente_etiqueta = $cliente['cod_cliente'];

	} catch (Exception $e4) {
		echo $e4->getMessage();
	}

	try {

		$list = $conn->query("SELECT * FROM localizacao_has_documento lhd INNER JOIN documento d ON lhd.documento_id_documento = d.id_documento INNER JOIN localizacao l ON l.id_localizacao = '{$id_local}' WHERE localizacao_id_localizacao = '{$id_local}'");
		$tam = $list->rowCount();

		foreach ($list as $local) {
			$cod_doc[] = $local['cod_documento'];
			$tipo = $local['tipo_localizacao'];
		}

		$temp = $cod_cliente_etiqueta.' - '.$nome_cliente_etiqueta;
		if(strlen($temp) > 38){
			$codNome = limitarTexto($temp, 38);
		}else{
			$codNome  = $temp;
		}
		echo '<form action="" method="post">
		<input type="hidden" value="'.$id_cliente.'" name="id_cliente"/>
		<input type="hidden" value="'.$id_local.'" name="id_local"/>
		<input type="hidden" value="'.$tipo.'" name="tipo"/>
		<div class="row">
			<div class="col-lg">
				<div class="input-group">
					<input class="btn btn-info col-12" type="submit" name="salva-etiqueta" value="Salvar">
				</div>
			</div>
		</div>
		';

		ob_start(); // Ativa o buffer de saida do PHP

		echo '
		<div style="padding:25px 10px 38px 10px;">
		';

		if($tam >= 1 && $tam <= 5){

				echo '
		<table cellspacing="0" width="325" height="250" class="table table-sm etiqueta">
			<style type="text/css">.etiqueta td{border-left:1px solid #000; border-right:1px solid #000; border-top:1px solid #000;}</style>
			<thead>
				<tr>
					<td style="padding:18px 15px" align="center" ><h5>'.$codNome.'</h5></td>
				</tr>
			</thead>

			<tbody>
				<tr><td align="center">'.(isset($cod_doc[0]) ? $cod_doc[0] : "-").'</td></tr>
				<tr><td align="center">'.(isset($cod_doc[1]) ? $cod_doc[1] : "-").'</td></tr>
				<tr><td align="center">'.(isset($cod_doc[2]) ? $cod_doc[2] : "-").'</td></tr>
				<tr><td align="center">'.(isset($cod_doc[3]) ? $cod_doc[3] : "-").'</td></tr>
				<tr><td align="center">'.(isset($cod_doc[4]) ? $cod_doc[4] : "-").'</td></tr>
				</tbody>
			<tfoot>
				<tr><td align="center" style="padding:10px; border-bottom:1px solid #000;"><h3>'.$local['tipo_localizacao'].' '.$local['num_localizacao'].'</h3></td></tr>
			</tfoot>
		</table>';

			}else if($tam >= 6 && $tam <= 10){
				echo '
		<table cellspacing="0" width="325" height="250" class="table table-sm etiqueta">
			<style type="text/css">.etiqueta td{border-left:1px solid #000; border-right:1px solid #000; border-top:1px solid #000;}</style>
			<thead>
				<tr>
					<td colspan="2" style="padding:18px 15px" align="center" ><h5>'.$codNome.'</h5></td>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td align="center">'.(isset($cod_doc[0]) ? $cod_doc[0] : "-").'</td>
					<td align="center">'.(isset($cod_doc[5]) ? $cod_doc[5] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[1]) ? $cod_doc[1] : "-").'</td>
					<td align="center">'.(isset($cod_doc[6]) ? $cod_doc[6] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[2]) ? $cod_doc[2] : "-").'</td>
					<td align="center">'.(isset($cod_doc[7]) ? $cod_doc[7] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[3]) ? $cod_doc[3] : "-").'</td>
					<td align="center">'.(isset($cod_doc[8]) ? $cod_doc[8] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[4]) ? $cod_doc[4] : "-").'</td>
					<td align="center">'.(isset($cod_doc[9]) ? $cod_doc[9] : "-").'</td>
				</tr>
				</tbody>
			<tfoot>
				<tr><td colspan="2" align="center" style="padding:10px; border-bottom:1px solid #000;"><h3>'.$local['tipo_localizacao'].' '.$local['num_localizacao'].'</h3></td></tr>
			</tfoot>
		</table>';

			}else if($tam >= 11 && $tam <= 15){
				echo '
		<table cellspacing="0" width="325" height="250" class="table table-sm etiqueta">
			<style type="text/css">.etiqueta td{border-left:1px solid #000; border-right:1px solid #000; border-top:1px solid #000;}</style>
			<thead>
				<tr>
					<td colspan="3" style="padding:18px 15px" align="center" ><h5>'.$codNome.'</h5></td>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td align="center">'.(isset($cod_doc[0]) ? $cod_doc[0] : "-").'</td>
					<td align="center">'.(isset($cod_doc[5]) ? $cod_doc[5] : "-").'</td>
					<td align="center">'.(isset($cod_doc[10]) ? $cod_doc[10] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[1]) ? $cod_doc[1] : "-").'</td>
					<td align="center">'.(isset($cod_doc[6]) ? $cod_doc[6] : "-").'</td>
					<td align="center">'.(isset($cod_doc[11]) ? $cod_doc[11] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[2]) ? $cod_doc[2] : "-").'</td>
					<td align="center">'.(isset($cod_doc[7]) ? $cod_doc[7] : "-").'</td>
					<td align="center">'.(isset($cod_doc[12]) ? $cod_doc[12] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[3]) ? $cod_doc[3] : "-").'</td>
					<td align="center">'.(isset($cod_doc[8]) ? $cod_doc[8] : "-").'</td>
					<td align="center">'.(isset($cod_doc[13]) ? $cod_doc[13] : "-").'</td>
				</tr>
				<tr>
					<td align="center">'.(isset($cod_doc[4]) ? $cod_doc[4] : "-").'</td>
					<td align="center">'.(isset($cod_doc[9]) ? $cod_doc[9] : "-").'</td>
					<td align="center">'.(isset($cod_doc[14]) ? $cod_doc[14] : "-").'</td>
				</tr>
				</tbody>
			<tfoot>
				<tr><td colspan="3" align="center" style="padding:10px; border-bottom:1px solid #000;"><h3>'.$local['tipo_localizacao'].' '.$local['num_localizacao'].'</h3></td></tr>
			</tfoot>
		</table>';

			}else{

				echo '
		<table cellspacing="0" width="325" height="250" class="table table-sm etiqueta">
			<style type="text/css">.etiqueta td{border-left:1px solid #000; border-right:1px solid #000; border-top:1px solid #000;}</style>
			<thead>
				<tr>
					<td style="padding:18px 15px" align="center" ><h5>'.$codNome.'</h5></td>
				</tr>
			</thead>

			<tbody>
				<tr><td align="center">.</td></tr>
				<tr><td align="center">.</td></tr>
				<tr><td align="center">'.$cod_doc[0].' até '.$cod_doc[$tam -1 ].'</td></tr>
				<tr><td align="center">.</td></tr>
				<tr><td align="center">.</td></tr>
				</tbody>
			<tfoot>
				<tr><td align="center" style="padding:10px; border-bottom:1px solid #000;"><h3>'.$local['tipo_localizacao'].' '.$local['num_localizacao'].'</h3></td></tr>
			</tfoot>
		</table>';
		}

		echo '</div>';
	    /* Captação de dados */
		$buffer = ob_get_contents(); // Obtém os dados do buffer interno
		$filename =  "temp/etiqueta.html"; // Nome do arquivo HTML
		file_put_contents($filename, $buffer); // Grava os dados do buffer interno no arquivo HTML

		echo '
			</div>
			</form>';

	} catch (Exception $e) {
		echo 'Erro: '.$e->getMessage();
	}

?>
</div>