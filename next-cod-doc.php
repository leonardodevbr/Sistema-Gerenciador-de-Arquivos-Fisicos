<?php
if(isset($_POST['id_caso'])){
	require("bd/conexao.php");
	$cliente_id_cliente = $_POST['id_cliente'];
	$id_caso = $_POST['id_caso'];

	$caso = $conn->query("SELECT id_caso FROM caso WHERE cliente_id_cliente = {$cliente_id_cliente}")->fetch();
	$caso_id_caso = $caso['id_caso'];

	if($id_caso == 0){
		$caso_id_caso = $caso['id_caso'];
	}else{
		$caso_id_caso = $id_caso;
	}

	try {
		$getNextCod = $conn->query("
						SELECT
						  c.cod_cliente, co.num_caso
						FROM
						  documento d
						INNER JOIN
						  cliente c
						ON
						  d.caso_cliente_id_cliente = c.id_cliente
						INNER JOIN
						  caso co
						ON
						  d.caso_id_caso = co.id_caso
						WHERE
						  caso_cliente_id_cliente = {$cliente_id_cliente} and co.id_caso = {$caso_id_caso}");

		$cont = $getNextCod->rowCount();
		$cod_doc = 1 + $cont;

		if($cont > 0){
			foreach ($getNextCod as $row) {
				$cod_cliente = $row['cod_cliente'];
				$num_caso = $row['num_caso'];
			}

			$next_cod = $cod_cliente.'.'.$num_caso.'.'.$cod_doc;
			echo $next_cod;

		}else{
			$novo = $conn->query("
					SELECT
					  c.cod_cliente, co.num_caso
					FROM
					  cliente c
					INNER JOIN
					  caso co
					ON
					  c.id_cliente = co.cliente_id_cliente
					WHERE
					  c.id_cliente = {$cliente_id_cliente} and co.id_caso = {$caso_id_caso}");
			foreach ($novo as $new) {
				$cod_cliente = $new['cod_cliente'];
				$num_caso = $new['num_caso'];
			}

			$next_cod = $cod_cliente.'.'.$num_caso.'.1';
			echo $next_cod;
		}

	} catch (Exception $e) {
		echo "Erro ao gerar o código do documento<br>Detalhes: ".$e->getMessage();
	}
}

?>