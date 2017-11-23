<?php
	require_once("bd/conexao.php");
	session_start();
	$id_usuario = $_SESSION['id_usuario'];
	$id_solicitacao = $_POST['id_solicitacao'];
	$tipoRecibo = $_POST['tipo'];

	try {
		if($tipoRecibo == "emprestimo"){
			if(emailReciboEmprestimo($id_solicitacao, $conn, $id_usuario)){
				return true;
			}
		}else{
			if(emailReciboDevolucao($id_solicitacao, $conn, $id_usuario)){
				return true;
			}
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>