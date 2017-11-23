<div class="hidden-sm-down my-3">
	<h3 class="text-center">Cadastro de Documento</h3>
</div>

<div class="hidden-md-up my-3">
	<h5 class="text-center">Cadastro de Documento</h5>
</div>

<form action="?pg=salvar-cadastrar&registro=documento" method="post">
	<div class="form-group">
		<label for="clienteDoc">Vincular Cliente:</label>
		<select id="clienteDoc" name="cliente_id_cliente" class="form-control">
		<?php
			require("bd/conexao.php");

			$cliente = $conn->query("
				SELECT
				  *
				FROM
				  cliente
				ORDER BY
				  SUBSTRING(cod_cliente, 1, 1) ASC,
				  (0 + SUBSTRING(cod_cliente, 2)) ASC");

			if($cliente->rowCount() > 0){
				foreach ($cliente as $row) {
					if(isset($_GET['c_id']) AND !empty($_GET['c_id'])){
						if($_GET['c_id'] == $row['id_cliente']){
							echo '<option selected value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
						}else{
							echo '<option value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
						}
					}else{
						echo '<option value="'.$row['id_cliente'].'">'.$row['cod_cliente'].' - '.$row['nome_cliente'].'</option>';
					}
				}
			}else{
				echo '<option>Nenhum cliente cadastrado</option>';
			}

		?>
		</select>
	</div>
	<div class="form-group">
		<label for="casoDoc">Selecione um caso</label>
		<select id="casoDoc" name="caso_id_caso" class="form-control">
		</select>
	</div>
	<div class="form-group">
		<label for="descricao">Descriçao do Documento:</label>
		<div class="input-group">
			<span id="nextCod" class="input-group-addon"></span>
			<input id="codDocumento" type="hidden" name="cod_documento">
			<input required autofocus type="text" class="form-control" name="descricao_documento" id="descricaoDoc"/>
		</div>
	</div>
	<div class="form-group">
		<label for="processo">Processo Vinculado:</label>
		<input type="text" class="form-control" name="processo_documento" id="processo" />
	</div>
	<div class="form-group">
		<label for="obs">Observação / Indexação</label>
		<input type="text" class="form-control" name="obs_documento" id="obs" />
	</div>
	<div class="form-group">
		<label for="num_pasta">Número da Pasta Digital:</label>
		<input type="text" class="form-control" value="0" name="num_pasta_documento" id="num_pasta" />
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
	</div>
</form>

<section id="ultimoDocumentoCadastrado">
<?php
	if (isset($_GET['last_doc'])) {
		include("ultimo-documento-inserido.php");
	}
?>
</section>