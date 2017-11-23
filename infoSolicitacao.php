<thead>
	<tr>
		<th class="text-center text-muted">Documentos Solicitados</th>
	</tr>
</thead>
<tbody>
<?php
$id_solicitacao = $_POST['id_solicitacao'];
require_once("bd/conexao.php");

	try {
		$items = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

		if($items->rowCount() == 0){
			$msg = "Nenhum item encontrado!";
			echo mensagem_close($msg, "danger");
		}else{
			foreach ($items as $row) {
				$id_documento = $row['documento_id_documento'];
				$item = $conn->query("SELECT descricao_documento, nome_cliente FROM documento d INNER JOIN cliente c ON d.caso_cliente_id_cliente = c.id_cliente WHERE id_documento = {$id_documento}")->fetch();
				$documento = $item['descricao_documento'];
				$cliente = $item['nome_cliente'];
				echo '<tr><td>'.$documento.' - '.$cliente.'</td></tr>';
			}
		}

	} catch (Exception $e1) {
		$msg = "Erro ao listar as solicitações existentes!<br>Detalhes: ".$e1->getMessage();
		echo '<tr><td>'.$msg.'</td></tr>';
	}
?>
<tbody>