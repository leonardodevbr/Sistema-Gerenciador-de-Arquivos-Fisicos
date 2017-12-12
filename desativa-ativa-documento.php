<?php
require_once("bd/conexao.php");

$op = $_POST['op'];
$doc = $_POST['doc'];

$res = ($op == '1' ? 'ativo' : 'inativo');

try {
	/*Atualiza o campo "ativo" do item para 0*/
	$conn->query("UPDATE documento SET ativo = {$op} WHERE id_documento = {$doc}");

	$msg = "Documento marcado como ".$res;
	$result['data'] = 'ok';

} catch (Exception $err) {
	$msg = "Erro ao marcar o item como ".$res.".<br>Detalhes: ".$err->getMessage();
}

$result['msg'] = $msg;

echo json_encode($result);

?>