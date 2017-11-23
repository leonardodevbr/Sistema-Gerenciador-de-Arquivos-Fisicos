<br>
<h2 class="text-center">Cadastro de Prateleira</h2>
<br>
<form action="?pg=salvar-cadastrar&registro=prateleira" method="post">

	<div class="form-group">
		<label for="num_prateleira">Descrição:</label>
		<input autofocus required type="text" class="form-control" name="num_prateleira" id="num_prateleira"/>
	</div>

	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
	</div>
	<br>

</form>

<?php
require_once("bd/conexao.php");
try {

	$sql = "SELECT * FROM prateleira ORDER BY id_prateleira ASC";
	$result = $conn->query($sql);
	$qnt = $result->rowCount();

	if($qnt > 0){
		echo '
		<table class="table table-striped table-bordered table-sm">
			<thead>
				<tr>
					<th>Número da prateleira</th>
					<th>Caixas ou Pastas Alocadas</th>
				</tr>
			</thead>
			<tbody>';
		foreach ($result as $row) {
			$id_prateleira = $row['id_prateleira'];
			$num_prateleira = $row['num_prateleira'];
			$localizacoes_prateleira = $row['localizacoes_prateleira'];
			echo '
				<tr>
					<td>'.$num_prateleira.'</td>
					<td>'.$localizacoes_prateleira.'</td>
				</tr>
			';
		}
		echo '
			</tbody>
		</table>';

	}else{
		$msg = "Nenhuma prateleira cadastrada!";
		echo mensagem($msg, "warning");
	}

} catch (Exception $e) {
	echo $e->getMessage();
}

?>