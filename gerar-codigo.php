<h3>Gerar Código</h3>
<form action="" method="post">
	<div class="form-group">
		<label>Selecionar a tabela</label>
		<select id="geraCodigoAdmin" class="form-control" name="tabela">
			<option value="0">Selecione</option>
			<option value="documento">Documentos</option>
			<option value="cliente">Clientes</option>
		</select>
	</div>
	<div id="dOpcao">
	</div>
	<input type="submit" class="btn btn-success" onclick="list();" name="acao" value="Gerar">
</form>


<?php
	if(isset($_POST['acao']) && $_POST['acao'] == 'Gerar'){
		require_once("bd/conexao.php");
		$tabela = $_POST['tabela'];

		switch ($tabela) {
			case 'cliente':
				$codigo = strtoupper(strip_tags(trim($_POST['codigo'])));
				if(!empty($codigo)){
					try {

						$limpa = $conn->query("UPDATE {$tabela} SET cod_{$tabela} = NULL WHERE nome_{$tabela} LIKE '$codigo%'");
						$result = $conn->query("SELECT * FROM {$tabela} WHERE nome_{$tabela} LIKE '$codigo%' AND cod_{$tabela} IS NULL ORDER BY id_{$tabela} ASC");
						$qnt = $result->rowCount();

						echo mensagem("Total de registros encontrados: ".$qnt, "info");

						if($qnt < 1){
							echo mensagem("Nenhum código foi gerado!", "warning", "gerar-codigo", "1000");
						}else{
							foreach ($result as $row) {
								$id[] = $row['id_'.$tabela];
							}

							for ($i=0; $i < $qnt; $i++) {
								$n = $i + 1;
								$conn->query("INSERT INTO caso (descricao_caso, cliente_id_cliente, num_caso) VALUES ('GERAL', {$id[$i]}, 1)");
								$dados = array(':cod' => $codigo.$n, ':id' => $id[$i]);

								$sql = 'UPDATE '.$tabela.' SET cod_'.$tabela.' = :cod WHERE id_'.$tabela.' = :id';

								$update = $conn->prepare($sql);
								$update->execute($dados);

							}

							echo mensagem("Códigos gerados com sucesso!", "success", "gerar-codigo", "500");

						}
					} catch (Exception $e1) {
						echo "Erro ao tentar gerar os códigos<br>Detalhes: ".$e1->getMessage();
					}
				}else{
					echo mensagem("Informe uma letra!", "warning");
				}
			break;
			case 'documento':
				$id_cliente = filter_input(INPUT_POST, 'cliente');
				try {
					/*Verificar quantos documento existem vinculado a este ID*/
					$limpa = $conn->query("UPDATE documento SET cod_documento = NULL WHERE caso_cliente_id_cliente = $id_cliente");
					$result = $conn->query("SELECT * FROM documento WHERE caso_cliente_id_cliente = $id_cliente");
					$qnt = $result->rowCount();

					foreach ($result as $row) {
						$id[] = $row['id_documento'];
						$caso_id_caso[] = $row['caso_id_caso'];
					}

					/*Recuperar as informações do cliente vinculado a este ID no banco*/

					$info = $conn->query("SELECT * FROM cliente WHERE id_cliente = $id_cliente");
					foreach ($info as $iRow) {
						$cod_cliente = $iRow['cod_cliente'];
					}

					/*Gera um array de códigos para os documentos*/

					for ($i=0; $i < $qnt; $i++) {
						$id_caso = $caso_id_caso[$i];

						$list_caso = $conn->query("SELECT * FROM caso WHERE id_caso = $id_caso");

						foreach ($list_caso as $caso) {
							$num_caso = $caso['num_caso'];
						}

						$cod = $cod_cliente.'.'.$num_caso.'.'.(1+$i);

						$dados = array(':cod' => $cod, ':id' => $id[$i]);

						$sql = 'UPDATE '.$tabela.' SET cod_'.$tabela.' = :cod WHERE id_'.$tabela.' = :id';

						$update = $conn->prepare($sql);
						$update->execute($dados);
					}

					echo mensagem("Códigos gerados com sucesso!", "success");

				} catch (Exception $e) {
					echo $e->getMessage();
				}
			break;

		}
	}
?>