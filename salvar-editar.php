<?php
	if(isset($_POST['btn_salvar'])){
		$registro = $_GET['registro'];
		require("bd/conexao.php");
		switch ($registro) {
			case 'cliente':
				$id_cliente = $_GET['id_cliente'];
				$nome_cliente = $_POST['nome_cliente'];

				try {
					$up = $conn->query("UPDATE cliente SET nome_cliente = '{$nome_cliente}' WHERE id_cliente = {$id_cliente}");

					if($up){
						echo mensagem("Dados do cliente atualizados com sucesso!", "success", "editar-cliente", "2000");
					}else{
						echo mensagem("Erro ao tentar atualizar os dados do cliente", "danger");
					}
				} catch (Exception $eCliente) {
					echo "Erro na operação na tabela 'cliente'<br>Detalhes: ".$eCliente->getMessage();
				}

			break;

			case 'documento':
				$id_documento = $_GET['id_documento'];
				$descricao_documento = $_POST['descricao_documento'];
				$obs_documento = $_POST['obs_documento'];
				$processo_documento = $_POST['processo_documento'];
				$num_pasta_documento = $_POST['num_pasta_documento'];

				try {
					$up = $conn->query("UPDATE documento SET descricao_documento = '{$descricao_documento}', obs_documento = '{$obs_documento}', processo_documento = '{$processo_documento}', num_pasta_documento = '{$num_pasta_documento}' WHERE id_documento = {$id_documento}");

					if($up){
						echo mensagem("Dados do documento atualizados com sucesso!", "success", "form-editar-documento&id=".$id_documento, "500");
					}else{
						echo mensagem("Erro ao tentar atualizar os dados do documento", "danger");
					}
				} catch (Exception $eDoc) {
					echo "Erro na operação na tabela 'documento'<br>Detalhes: ".$eDoc->getMessage();
				}

			break;
			case 'usuario':
				$id_user = $_GET['id_usuario'];
				$atualizar_senha = (int)filter_input(INPUT_POST, 'atualizar_senha');
				$tipo_user = filter_input(INPUT_POST, 'tipo_usuario');
				$nome_user = filter_input(INPUT_POST, 'nome_usuario');
				$username_user = filter_input(INPUT_POST, 'username_usuario');
				$email_user = filter_input(INPUT_POST, 'email_usuario');
				$password_user =base64_encode(filter_input(INPUT_POST, 'password_usuario'));

				if(!empty($password_user)){
					$sql = "UPDATE usuario SET alerta_usuario = 0, status_usuario = 1, tipo_usuario = '{$tipo_user}', nome_usuario = '{$nome_user}', username_usuario = '{$username_user}', email_usuario = '{$email_user}', password_usuario = '{$password_user}', atualizar_senha = '{$atualizar_senha}' WHERE id_usuario = {$id_user}";
				}else{
					$sql = "UPDATE usuario SET tipo_usuario = '{$tipo_user}', nome_usuario = '{$nome_user}', username_usuario = '{$username_user}', email_usuario = '{$email_user}', atualizar_senha = '{$atualizar_senha}' WHERE id_usuario = {$id_user}";
				}

				try {

					$result = $conn->query($sql);

					echo mensagem("Usuário editado com sucesso!", "success", "editar-usuario", "2000");

				} catch (Exception $e) {
					echo "ERROR: ".$e->getMessage();
				}
			break;

		}

	}else{
		header("Location: index.php?ph=home");
	}

?>