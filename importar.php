<form enctype="multipart/form-data" action="" method="POST">
	<div class="form-group">
		<label for="arquivo">Selecionar um arquivo</label>
		<input type="file" name="userfile" class="form-control-file" id="arquivo">
	</div>
	<div class="form-group"
		<label for="arquivo">Importar para a tabela: </label>
		<select name="tabela" class="form-control">
			<option value="documento">Documento</option>
			<option value="cliente">Cliente</option>
		</select>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-success" name="acao" value="Enviar" />
	</div>

<?php

require_once("bd/conexao.php");

if(isset($_POST['acao'])){

	if(!empty($_FILES['userfile']['name'])){
		$arquivo = $_FILES['userfile']['name'];
		$tabela = $_POST['tabela'];
		$ext = strrchr($arquivo, '.');
		$nome = 'arquivo'.$ext;
		$uploaddir = 'uploads/';
		$uploaddir = 'uploads/';
		$uploadfile = $uploaddir . basename($nome);
		move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);


	$ponteiro = fopen ("uploads/arquivo".$ext, "r");

	$sql = '';
	$tmp = '';
	$t = '';
	$cod_temp = 0;
	$rows = 20;
	while (!feof ($ponteiro)) {
		$linha = fgets($ponteiro, 4096);
		$dados = explode(";", $linha);
		if(feof($ponteiro))break;
		$encoding = 'UTF-8';
		$nome_cliente = utf8_encode(str_replace("'", "\'", trim($dados[0])));

		if ($tabela == 'documento') {
			$nome = mb_convert_case($nome_cliente, MB_CASE_UPPER, $encoding);
			$descricao = utf8_encode(str_replace("'", "\'", trim($dados[1])));
			$processo = utf8_encode(str_replace("'", "\'", trim($dados[2])));
			$id_cliente = $conn->query("SELECT id_cliente FROM cliente WHERE nome_cliente = '{$nome_cliente}'")->fetch();
			$id_caso = $conn->query("SELECT id_caso FROM caso WHERE cliente_id_cliente = '$id_cliente[0]}' ORDER BY id_caso ASC")->fetch();
			$caso = $id_caso[0];
			$id = $id_cliente[0];
			$sql .= "INSERT INTO documento (descricao_documento, processo_documento, caso_cliente_id_cliente, caso_id_caso) VALUES ('{$descricao}', '{$processo}', {$id}, {$caso});\n";

		}else{

			if($nome_cliente != $tmp){
				$cod = substr(tirarAcentos($nome_cliente),0,1);
				if($t != $cod){
					$cod_temp = 1;
				}else{
					$cod_temp++;
				}
				$cod_cliente = $cod.$cod_temp;
				$nome = mb_convert_case($nome_cliente, MB_CASE_UPPER, $encoding);
				$sql .= "INSERT INTO cliente (nome_cliente, cod_cliente) VALUES ('{$nome}', '{$cod_cliente}');\n";
				$sql .= "INSERT INTO caso (descricao_caso, cliente_id_cliente, num_caso) VALUES ('GERAL', LAST_INSERT_ID(), '0');\n";
			}

			$tmp = $nome_cliente;
			$t = $cod;
		}
	}

	fclose($ponteiro);

	$view = "
DROP VIEW
  vw_dados;\n
CREATE VIEW vw_dados AS
SELECT
  CONCAT_WS(
    ';',
    cod_cliente,
    cod_documento,
    nome_cliente,
    descricao_documento,
    processo_documento,
    obs_documento,
    descricao_caso
  ) AS dados,
  id_documento AS id_doc,
  id_cliente AS id_cli
FROM
  documento d
INNER JOIN
  cliente c
ON
  d.caso_cliente_id_cliente = c.id_cliente
INNER JOIN
  caso cs
ON
  cs.cliente_id_cliente = c.id_cliente
ORDER BY
  c.nome_cliente;";

	echo '
		<div class="form-group">
			<label for="exampleTextarea">Clique para selecionar a SQL</label>
			<textarea id="sqlImport" class="form-control" id="exampleTextarea" rows="'.$rows.'">'.$sql.$view.'</textarea>
		</div>';

	}else{
		echo mensagem("Nenhum arquivo selecionado!", "warning");
	}
}

?>



</form>