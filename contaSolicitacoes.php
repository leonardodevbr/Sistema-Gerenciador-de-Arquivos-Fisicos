<?php
require_once("bd/conexao.php");

	try {
		$select = $conn->query("SELECT * FROM solicitacao ORDER BY id_solicitacao DESC");
		$qnt = $select->rowCount();
		$_SESSION['qnt'] = $qnt;
		
	} catch (Exception $e) {
		echo 0;
	}



?>