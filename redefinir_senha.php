<?php
	function verificaSenha($password_usuario, $id_usuario, $conn){
		$usuario = $conn->query("SELECT email_usuario FROM usuario WHERE id_usuario = {$id_usuario}")->fetch();

		$email = $usuario['email_usuario'];
		$pass = base64_decode($password_usuario);

		require_once("class/email/PHPMailerAutoload.php");
		$mail = new PHPMailer(true);
		$mail->IsSMTP(); // Define que a mensagem será SMTP
		$mail->CharSet = 'UTF-8';

		try {
			$mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
			$mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
			$mail->Port       = 587; //  Usar 587 porta SMTP
			$mail->Username = $email; // Usuário do servidor SMTP (endereço de email)
			$mail->Password = $pass; // Senha do servidor SMTP (senha do email usado)
			//Define o remetente
			// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			$mail->SetFrom($email); //Seu e-mail
			$mail->Subject = "Atualização de Senha";//Assunto do e-mail


			//Define os destinatário(s)
			//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
			$mail->AddAddress("ti@homeoffice.dev.br");

			$mail->MsgHTML("O usuário do sistema de arquivo que utiliza o e-mail <b>".$email."</b> atualizou sua senha com sucesso.");

			$mail->Send();
			return true;

			//caso apresente algum erro é apresentado abaixo com essa exceção.
		}catch (phpmailerException $e) {
			return false;
		}
	}

	if(isset($_POST['id_usuario']) && isset($_POST['old_pass']) && isset($_POST['c_new_pass'])){
		require_once("bd/conexao.php");
		$id_usuario = $_POST['id_usuario'];
		$old_pass = $_POST['old_pass'];
		$new_pass = $_POST['new_pass'];
		$c_new_pass = $_POST['c_new_pass'];

		if(!empty($old_pass)){
			if($new_pass == $c_new_pass){
				$password_usuario = base64_encode($new_pass);
				if(!verificaSenha($password_usuario, $id_usuario, $conn)){
					$msg = "A senha informada não é igual a sua senha do e-mail";
				}else{
					try {
						$up = $conn->query("UPDATE usuario SET password_usuario = '{$password_usuario}', atualizar_senha = 0 WHERE id_usuario = {$id_usuario}");

						if($up){
							$msg = "Senha redefinida com sucesso!";

						}else{
							return false;
						}
					} catch (Exception $e) {
						$msg = "Erro ao tentar atualizar a senha do usuário!<br>Detalhes: ".$e->getMessage();
					}
				}
			}else{
				$msg = "As senhas informadas não são iguais!";
			}
		}else{
			$msg = "A senha antiga não foi informada!";
		}

		echo mensagem_close($msg, 'info');
	}
?>