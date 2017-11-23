<br>
<h2 class="text-center">Cadastro de Caso</h2>
<br>
<form action="?pg=salvar-cadastrar&registro=caso" method="post">

	<div class="form-group">
		<label for="descricao">Descrição:</label>
		<input required type="text" class="form-control" name="descricao_caso" id="descricao"/>
	</div>
	<div class="form-group">
		<label for="caso">Vincular Cliente:</label>
		<select class="form-control" id="cliente" name="cliente_id_cliente">
			<?php
				try {
					require_once("bd/conexao.php");

					$sql = "SELECT * FROM cliente";
					$clientes = $conn->query($sql);

					foreach ($clientes as $cliente) {
						echo '<option value="'.$cliente['id_cliente'].'">'.$cliente['nome_cliente'].'</option>';
					}

				} catch (Exception $e) {
					echo mensagem("Erro ao listar os casos!", "danger");
				}
			?>
		</select>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
	</div>
	<br>

</form>
