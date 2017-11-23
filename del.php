<?php

require("bd/conexao.php");
$sql = '';

$query1 = $conn->query("SELECT * FROM solicitacao WHERE usuario_id_usuario = 19");
foreach ($query1 as $q1) {
	$idSol = $q1['id_solicitacao'];
	$sql .= "DELETE FROM solicitacao WHERE id_solicitacao = ".$idSol.";<br>";
}

$query2 = $conn->query("SELECT * FROM emprestimo WHERE usuario_id_usuario = 19");
foreach ($query2 as $q2) {
	$idEmp = $q2['id_emprestimo'];
	$sql .= "DELETE FROM emprestimo WHERE id_emprestimo = ".$idEmp.";<br>";
}

$query3 = $conn->query("SELECT * FROM solicitacao_has_documento WHERE usuario_id_usuario = 19");
foreach ($query3 as $q3) {
	$idSolHasDoc = $q3['solicitacao_id_solicitacao'];
	$sql .= "DELETE FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = ".$idSolHasDoc.";<br>";
}

echo $sql."<br><br>";

?>