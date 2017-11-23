<?php
	require_once("bd/conexao.php");

	$id_cliente = $_POST['id_cliente'];
	$id_caso = $_POST['id_caso'];
	$num_localizacao = $_POST['num_localizacao'];
	$id_prateleira = $_POST['id_prateleira'];
	$tipo = strtoupper($_POST['tipo']);
	$id_localizacao = $_POST['id_localizacao'];
	$docArr = ($_POST['doc'] != ''? $_POST['doc'] : 0);
	$doc = explode(",",$docArr);
	$dadosLocal = array(
		':cliente_id_cliente' => $id_cliente,
		':caso_id_caso' => $id_caso,
		':num_localizacao' => $num_localizacao,
		':prateleira_id_prateleira' => $id_prateleira,
		':tipo' => $tipo
	);

	if($docArr != 0){
		try {
			if($id_localizacao == 0){//Se não existe nenhuma localizaão selecionada.

				$query = "INSERT INTO localizacao (num_localizacao, prateleira_id_prateleira, cliente_id_cliente, caso_id_caso, tipo_localizacao) VALUES (:num_localizacao, :prateleira_id_prateleira, :cliente_id_cliente, :caso_id_caso, :tipo)";
				$novoLocal = $conn->prepare($query);
				$novoLocal->execute($dadosLocal);
				$id_loc = $conn->lastInsertId('localizacao');
				/*Adiciona uma localizaçao na prateleira selecionada*/
				$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira + 1) WHERE id_prateleira = {$id_prateleira}");

				$msg = ucfirst(strtolower($tipo))." criada com sucesso!";
			}else{
				$id_loc = $id_localizacao;
				$novoLocal = true;
				$msg = ucfirst(strtolower($tipo))." atualizada com sucesso!<br> Gere uma nova etiqueta para essa ".ucfirst(strtolower($tipo));
				try {

					$conn->query("UPDATE localizacao SET etiquetada = 0 WHERE id_localizacao = {$id_loc}");
					$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = {$id_loc}");
				} catch (Exception $err) {
					$resposta = 'Erro ao tentar atualizar a localização como "Não Etiquetada" ou ao tentar excluir a etiqueta Existente.<br>Detalhes: '.$err->getMessage();
				}
			}

			if($novoLocal){
				foreach($doc as $k => $v){
					$sql_insert = "INSERT INTO localizacao_has_documento (localizacao_id_localizacao, documento_id_documento) VALUES ('{$id_loc}', '{$v}')";
					$conn->query($sql_insert);
					$sql_update = "UPDATE documento SET alocado = '1' WHERE id_documento = {$v}";
					$conn->query($sql_update);
				}
				$resposta = mensagem($msg, "success");
			}

		} catch (Exception $e3) {
			$resposta = "Mensagem 3: ".$e3->getMessage();
			var_dump($dadosLocal);
		}
	}else{
		$msg = "Selecione algum documento";
		$resposta = mensagem($msg, "danger");
	}
	echo $resposta;
?>