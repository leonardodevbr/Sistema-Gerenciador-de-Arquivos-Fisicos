<?php
if (isset($_GET['id'])) {

	require_once("bd/conexao.php");

	$id = $_GET['id'];
		try {

			$documento = $conn->query("SELECT * FROM documento WHERE id_documento = {$id}")->fetch();
?>
<br>
<form action="?pg=salvar-editar&registro=documento&id_documento=<?php echo $documento['id_documento']; ?>" method="post">
	<div class="form-group">
		<input type="text" class="form-control text-center" disabled value="<?php echo $documento['cod_documento']; ?>"/>
	</div>
	<div class="form-group">
		<label for="descricao">Descriçao do Documento:</label>
		<input required type="text" class="form-control" value="<?php echo $documento['descricao_documento']; ?>" name="descricao_documento" id="descricao"/>
	</div>
	<div class="form-group">
		<label for="processo">Processo Vinculado:</label>
		<input type="text" class="form-control" value="<?php echo $documento['processo_documento']; ?>" name="processo_documento" id="processo" />
	</div>
	<div class="form-group">
		<label for="obs">Observação / Indexação</label>
		<input type="text" class="form-control" value="<?php echo $documento['obs_documento']; ?>" name="obs_documento" id="obs" />
	</div>
	<div class="form-group">
		<label for="num_pasta">Número da Pasta Digital:</label>
		<input type="text" class="form-control" value="<?php echo $documento['num_pasta_documento']; ?>" name="num_pasta_documento" id="num_pasta" />
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
		<button type="button" class="btn btn-warning" onclick="location.href='?pg=editar-documento'">Voltar</button>
	</div>
	<br>

</form>
<?php

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}else{
		header("location: index.php?pg=editar-documento");
	}
?>


