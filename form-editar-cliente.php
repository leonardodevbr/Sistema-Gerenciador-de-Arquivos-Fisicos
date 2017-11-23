<?php
	if (isset($_GET['id'])) {

	require_once("bd/conexao.php");

	$id = $_GET['id'];
		try {

			$cliente = $conn->query("SELECT * FROM cliente WHERE id_cliente = {$id}")->fetch();
			$casos = $conn->query("SELECT * FROM caso WHERE cliente_id_cliente = {$id} ORDER BY num_caso ASC");
			$casos_ft = $conn->query("SELECT num_caso FROM caso WHERE cliente_id_cliente = {$id} ORDER BY num_caso DESC")->fetch();
			$numCaso = $casos_ft['num_caso'];
?>

<div class="modal fade" id="addCaso" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="">
				<div class="modal-header">
					<h5 class="modal-title">Adicionar Caso</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="clienteCaso">Cliente:</label>
						<input type="text" class="form-control" id="clienteCaso" value="<?php echo $cliente['nome_cliente']; ?>" disabled/>
					</div>
					<div class="form-group">
						<label for="numCaso">Número do Caso:</label>
						<input type="number" class="form-control" name="num_caso" id="numCaso" value="<?php echo 1+$numCaso; ?>" />
					</div>
					<div class="form-group">
						<label for="descricao">Descrição:</label>
						<input required type="text" class="form-control" name="descricao_caso" id="caso_descricao"/>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					<button type="submit" name="addCaso" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>
<br>
<form action="?pg=salvar-editar&registro=cliente&id_cliente=<?php echo $cliente['id_cliente']; ?>" method="post">

	<div class="row">

		<div class="col-lg-6">
			<div class="input-group">
				<span class="input-group-addon">Nome</span>
				<input required type="text" class="form-control" name="nome_cliente" id="nome" value="<?php echo $cliente['nome_cliente']; ?>" />
			</div>
		</div>
		<div class="col-lg-5">
			<div class="input-group">
				<span class="input-group-addon">Casos Vinculados</span>
				<select name="caso" class="form-control">
					<?php
						foreach ($casos as $caso) {
							echo '<option value="'.$caso['id_caso'].'">'.$caso['num_caso'].' - '.$caso['descricao_caso'].'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-lg">
			<div class="input-group">
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCaso">+</button>
			</div>
		</div>

	</div>
	<br>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
		<button type="button" class="btn btn-warning" onclick="location.href='?pg=editar-cliente'">Voltar</button>
	</div>
	<br>

</form>
<?php

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}else{
		header("location: index.php?pg=editar-cliente");
	}

	if(isset($_POST['addCaso'])){
		$descricao_caso = filter_input(INPUT_POST, 'descricao_caso');
		$num_caso = $_POST['num_caso'];
		$idCliente = $cliente['id_cliente'];
		try {
			$caso = $conn->query("INSERT INTO caso (descricao_caso, cliente_id_cliente, num_caso) VALUES ('{$descricao_caso}', {$idCliente}, {$num_caso})");

			if($caso){
				echo mensagem("Caso adiciondo com sucesso!", "success", "form-editar-cliente&id=".$idCliente, "1000");
			}else{
				echo mensagem("Erro ao adicionar um novo caso!", "danger");
			}

		} catch (Exception $eAddCaso) {
			echo "Error: ".$eAddCaso->getMessage();
		}
	}
?>