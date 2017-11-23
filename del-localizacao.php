<?php
	$id_loc = $_POST['id'];
	require_once("bd/conexao.php");
	/*Guarda o ID dos documentos nesta localização */
	$get_id_doc = $conn->query("SELECT documento_id_documento FROM localizacao_has_documento WHERE localizacao_id_localizacao = $id_loc");
	$qnt_id_doc = $get_id_doc->rowCount();
	if($qnt_id_doc > 0){

		/*Deletas os registros da tabela localizacao_has_documento*/
		$conn->query("DELETE FROM localizacao_has_documento WHERE localizacao_id_localizacao = $id_loc");
		/*Deletas a etiqueta antiga desta localização*/
		$conn->query("DELETE FROM etiqueta WHERE localizacao_id_localizacao = $id_loc");
		/*Deleta a localização selecionada da prateleira atual*/
		$id_prateleira = $conn->query("SELECT prateleira_id_prateleira FROM localizacao WHERE id_localizacao = $id_loc")->fetch();
		$conn->query("UPDATE prateleira SET localizacoes_prateleira = (localizacoes_prateleira - 1) WHERE id_prateleira = {$id_prateleira[0]}");
		/*Desaloca os documentos que estavam nesta localização*/
		foreach ($get_id_doc as $doc) {
			$id_doc = $doc['documento_id_documento'];
			$sql = "UPDATE documento SET alocado = NULL WHERE id_documento = {$id_doc}";
			$conn->query($sql);
		}
		$conn->query("DELETE FROM localizacao WHERE id_localizacao = $id_loc");
		echo 'ok';
	}
?>