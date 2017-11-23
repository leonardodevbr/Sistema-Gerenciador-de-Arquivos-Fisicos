<?php
require_once("bd/conexao.php");
$id_cliente = $_POST['id_cliente'];
$caso_selecionado = $_POST['id_caso'];
$div = '
<div id="colSelectCaso" class="col-12 col-md-6 mt-3">
	<select id="selectCasoGerarLocalizacao" name="caso" class="form-control">';
			try {
				$sql = "SELECT 	cs.id_caso, cs.descricao_caso, cs.num_caso FROM caso cs WHERE cs.cliente_id_cliente = {$id_cliente}";

				$casosCliente = $conn->query($sql);
				$qnt = $casosCliente->rowCount();

				if($qnt > 0){

					foreach ($casosCliente as $csRow) {
						$id_caso = $csRow['id_caso'];
						$num_caso = $csRow['num_caso'];
						$descricao_caso = $csRow['descricao_caso'];
						if($caso_selecionado == $id_caso){
							$div .= '<option selected value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
						}else{
							$div .= '<option value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
						}
					}

				}else{
					$div .= '<option>Nenhum cliente cadastrado</option>';
				}

			} catch (Exception $e1) {
				$data['erro'] = $e1->getMessage();
			}
	$div .= '
	</select>
</div>';
$data['colSelectCaso'] = $div;
$data['qntCasos'] = $qnt;

echo json_encode($data);
?>