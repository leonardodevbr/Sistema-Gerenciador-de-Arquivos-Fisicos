<?php
	require_once("bd/conexao.php");
	$id = $conn->query("SELECT * FROM solicitacao ORDER BY id_solicitacao DESC")->fetch();
	echo $id['id_solicitacao'];
?>