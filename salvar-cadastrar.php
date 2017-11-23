<?php
	$id_usuario_logado = $id_usuario;

	if(isset($_POST['btn_salvar'])){
		$registro = $_GET['registro'];
		require("bd/conexao.php");
		switch ($registro) {
			case 'cliente':
				/*Bloco1: Cadastra um novo cliente*/
				$nome_cliente = $_POST['nome_cliente'];
				$cod_temp = substr($nome_cliente,0,1);///Pega a primeira letra do nome do cliente
				$encoding = 'UTF-8';

				$cod_cliente = $cod_temp;


				$dados_cliente = array(
					':nome_cliente' => mb_convert_case($nome_cliente, MB_CASE_UPPER, $encoding),
					':cod_cliente' => $cod_cliente,
					':id_usuario_logado' => $id_usuario_logado
				);

				try {
					$query = "INSERT INTO cliente
					(nome_cliente, cod_cliente, id_usuario_logado)
					VALUES
					(:nome_cliente, :cod_cliente, :id_usuario_logado)";

					$result = $conn->prepare($query);
					$result->execute($dados_cliente);
					$ultimoId = $conn->lastInsertId('cliente');

					/*Bloco2: Gera o código do cliente*/
					try {

						$consultaCod = $conn->query("SELECT * FROM cliente WHERE cod_cliente LIKE '$cod_cliente%'");
						$cont = $consultaCod->rowCount();
						$codigo_final = $cod_cliente.$cont;
						$conn->query("UPDATE cliente SET cod_cliente = '$codigo_final' WHERE id_cliente = $ultimoId");
						$conn->query("INSERT INTO caso (descricao_caso, cliente_id_cliente, id_usuario_logado) VALUES ('GERAL', {$ultimoId}, {$id_usuario_logado})");

					echo mensagem("Cliente cadastrado com sucesso!", "success", "cadastrar-cliente", "2000");

					} catch (Exception $e1) {
						echo "Erro ao criar o código do cliente!<br>Mensagem: ".$e1->getMessage();
					}
					/*Fim do Bloco2*/


				} catch (Exception $e) {
					echo "ERROR: ".$e->getMessage();
				}/*Fim do Bloco1*/
			break;
			case 'caso':
				/*Bloco1: Cadastra um novo caso*/
				$descricao_caso = $_POST['descricao_caso'];
				$cliente_id_cliente = $_POST['cliente_id_cliente'];

				$dados_caso = array(
					':descricao_caso' => strtoupper(utf8_decode($descricao_caso)),
					':cliente_id_cliente' => $cliente_id_cliente,
					':id_usuario_logado' => $id_usuario_logado
				);

				try {
					$query = "INSERT INTO caso
					(descricao_caso, cliente_id_cliente, id_usuario_logado)
					VALUES
					(:descricao_caso, :cliente_id_cliente, :id_usuario_logado)";

					$result = $conn->prepare($query);
					$result->execute($dados_caso);

					echo mensagem("Caso cadastrado com sucesso!", "success", "cadastrar-caso", "1000");


				} catch (Exception $e) {
					echo "ERROR: ".$e->getMessage();
				}/*Fim do Bloco1*/
			break;
			case 'documento':

			/*Bloco1: Cadastra um novo Documento*/
					$cliente_id_cliente = $_REQUEST['cliente_id_cliente'];
					$cod_documento = $_REQUEST['cod_documento'];
					$descricao_documento = $_REQUEST['descricao_documento'];
					$obs_documento = $_REQUEST['obs_documento'];
					$processo_documento = $_REQUEST['processo_documento'];
					$num_pasta_documento = $_REQUEST['num_pasta_documento'];
					$caso_id_caso = $_REQUEST['caso_id_caso'];

					$dados_documento = array(
						':caso_cliente_id_cliente' => $cliente_id_cliente,
						':descricao_documento' => $descricao_documento,
						':cod_documento' => $cod_documento,
						':obs_documento' => $obs_documento,
						':num_pasta_documento' => $num_pasta_documento,
						':caso_id_caso' => $caso_id_caso,
						':processo_documento' => $processo_documento,
						':id_usuario_logado' => $id_usuario_logado
					);

					try {
						$query = "INSERT INTO documento (caso_cliente_id_cliente, descricao_documento, obs_documento, num_pasta_documento, cod_documento, caso_id_caso, processo_documento, id_usuario_logado) VALUES (:caso_cliente_id_cliente, :descricao_documento, :obs_documento, :num_pasta_documento, :cod_documento, :caso_id_caso, :processo_documento, :id_usuario_logado)";

						$result = $conn->prepare($query);
						$result->execute($dados_documento);
						$ultimoId = $conn->lastInsertId('documento');

						// /*Bloco2: Gera o código do documento*/
						// try {

						// 	$getCodCliente = $conn->query("
						// 		SELECT
						// 		  *
						// 		FROM
						// 		  documento d
						// 		INNER JOIN
						// 		  cliente c
						// 		ON
						// 		  d.caso_cliente_id_cliente = c.id_cliente
						// 		INNER JOIN
						// 		  caso co
						// 		ON
						// 		  d.caso_id_caso = co.id_caso
						// 		WHERE
						// 		  caso_cliente_id_cliente = {$cliente_id_cliente} and co.id_caso = {$caso_id_caso}");

						// 	$cont = $getCodCliente->rowCount();

						// 		foreach ($getCodCliente as $rowCodCliente) {
						// 			$cod_cliente = $rowCodCliente['cod_cliente'];
						// 			$num_caso = $rowCodCliente['num_caso'];
						// 		}

						// 	$codigo_final = $cod_cliente.'.'.$num_caso.'.'.$cont;
						// 	$conn->query("UPDATE documento SET cod_documento = '$codigo_final' WHERE id_documento = $ultimoId");

						// echo mensagem("Documento cadastrado com sucesso!", "success", "cadastrar-documento&c_id=".$cliente_id_cliente."&cs_id=".$caso_id_caso, "500");
						echo mensagem("Documento cadastrado com sucesso!", "success", "cadastrar-documento&c_id=".$cliente_id_cliente."&cs_id=".$caso_id_caso."&last_doc=".$ultimoId, "250");
						// echo btn("pg=cadastrar-documento&c_id=".$cliente_id_cliente."&cs_id=".$caso_id_caso, "Voltar", "btn-warning btn-sm");

						// } catch (Exception $e1) {
						// 	$msg = "Erro ao criar o código do documento!<br>".$e1->getMessage();
						// 	echo mensagem($msg, "danger", "cadastrar-documento", "10000");
						// }
						// /*Fim do Bloco2*/

					} catch (Exception $e) {
						$msg = "Erro ao tentar inserir um registro na tabela documento<br>".$e->getMessage();
						echo mensagem($msg, "danger");
					}
			/*Fim do Bloco1*/
			break;
			case 'usuario':
				/*Bloco1: Cadastra um novo usuário*/
				$atualizar_senha = (int)filter_input(INPUT_POST, 'atualizar_senha');
				$tipo_usuario = filter_input(INPUT_POST, 'tipo_usuario');
				$nome_usuario = filter_input(INPUT_POST, 'nome_usuario');
				$username_usuario = filter_input(INPUT_POST, 'username_usuario');
				$email_usuario = filter_input(INPUT_POST, 'email_usuario');
				$password_usuario = filter_input(INPUT_POST, 'password_usuario');

				$dados_usuario = array(
					':tipo_usuario' => $tipo_usuario,
					':nome_usuario' => $nome_usuario,
					':username_usuario' => $username_usuario,
					':email_usuario' => $email_usuario,
					':password_usuario' => base64_encode($password_usuario),
					':id_usuario_logado' => $id_usuario_logado,
					':atualizar_senha' => $atualizar_senha
				);

				try {
					$query = "INSERT INTO usuario
					(tipo_usuario, username_usuario, email_usuario, nome_usuario, password_usuario, id_usuario_logado, atualizar_senha)
					VALUES
					(:tipo_usuario, :username_usuario, :email_usuario, :nome_usuario, :password_usuario, :id_usuario_logado, :atualizar_senha)";

					$result = $conn->prepare($query);
					$result->execute($dados_usuario);

					echo mensagem("Usuário cadastrado com sucesso!", "success", "cadastrar-usuario", "2000");

				} catch (Exception $e) {
					echo "ERROR: ".$e->getMessage();
				}/*Fim do Bloco1*/
			break;
			case 'prateleira':
				/*Bloco1: Cadastra uma prateleira*/
				$num_prateleira = $_POST['num_prateleira'];

				$dados_prateleira = array(
					':num_prateleira' => $num_prateleira,
					':id_usuario_logado' => $id_usuario_logado
				);

				try {
					$query = "INSERT INTO prateleira
					(num_prateleira, id_usuario_logado)
					VALUES
					(:num_prateleira, :id_usuario_logado)";

					$result = $conn->prepare($query);
					$result->execute($dados_prateleira);

					echo mensagem("Prateleira cadastrada com sucesso!", "success", "cadastrar-prateleira", "200");


				} catch (Exception $e) {
					echo "ERROR: ".$e->getMessage();
				}/*Fim do Bloco1*/
			break;
			default:
				include_once("home.php");
			}

	}else{
		include_once("home.php");
	}

?>
