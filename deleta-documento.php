<?php
	require_once("bd/conexao.php");
	$id = $_POST['id_doc'];

	try {
		/*Guarda o ID da localização atual do documento*/
		$loc = $conn->query("SELECT localizacao_id_localizacao FROM localizacao_has_documento WHERE documento_id_documento = {$id}");

		if($loc->rowCount() > 0){
			$id_loc->fetch();

			/*Deleta o documento selecionado de sua localização atual*/
			$delLoc = $conn->query("DELETE FROM localizacao_has_documento WHERE documento_id_documento = {$id}");

			/*Verifica se ainda existem documentos na localização*/
			if($conn->query("SELECT * FROM localizacao_has_documento WHERE localizacao_id_localizacao = {$id_loc[0]}")->rowCount() > 0){
				/*Se existem documentos, remove a flag "etiquetada" da localização*/
				$conn->query("UPDATE localizacao SET etiquetada = 0 WHERE id_localizacao = {$id_loc[0]}");
			}else{
				/*Se não existem mais documentos, deleta a localização.*/
				/*Deleta a antiga etiqueta*/
				$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = {$id_loc[0]}");

				/*Deleta a localização selecionada da prateleira atual*/
				$id_prateleira = $conn->query("SELECT prateleira_id_prateleira FROM localizacao WHERE id_localizacao = {$id_loc[0]}")->fetch();

				$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira - 1) WHERE id_prateleira = {$id_prateleira[0]}");
				/*Deleta a localização*/

				$conn->query("DELETE FROM localizacao WHERE id_localizacao = {$id_loc[0]}");

			}
		}

		/*Deleta o documento selecionado*/
		$delDoc = $conn->query("DELETE FROM documento WHERE id_documento = $id");

		if($delDoc){
			echo 'ok';
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>