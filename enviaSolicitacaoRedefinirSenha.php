<?php
require_once("bd/conexao.php");
$id = $_POST['id'];

try {
	$usuario = $conn->query("SELECT email_usuario FROM usuario WHERE id_usuario = {$id}")->fetch();
	$email = $usuario['email_usuario'];

	$code = md5("codigo_de_segurança".$email.$id);
	$up = $conn->query("UPDATE usuario SET codigo_redefinicao = '{$code}' WHERE id_usuario = {$id}");
	emailRecuperarSenha($email, $code, $id, $conn);

	$result['status_ret'] = 1;
	$result['msg'] = 'Um link com instruções para a redefinição de senha foi enviado para o e-mail '.$email.'.<br>Verifique a caixa de entrada deste endereço de e-mail.';
} catch (Exception $e) {
	$result['status_ret'] = 0;
	$result['msg'] = 'Erro selecionar o usuário com o ID: '.$id.'.<br>Detalhes: '.$e->getMessage();
}

echo json_encode($result);

?>