<?php
if (isset($_GET['id'])) {

	require_once("bd/conexao.php");

	$id = $_GET['id'];
		try {

			$usuario = $conn->query("SELECT * FROM usuario WHERE id_usuario = {$id}")->fetch();
			$tipo = $usuario['tipo_usuario'];
			$nome = $usuario['nome_usuario'];
			$username = $usuario['username_usuario'];
			$email = $usuario['email_usuario'];
?>
<br>
<form action="?pg=salvar-editar&registro=usuario&id_usuario=<?php echo $usuario['id_usuario']; ?>" method="post">
	<div class="form-group">
		<label for="tipoUsuario">Setor</label>
		<select class="form-control" name="tipo_usuario">
			<option <?php echo ($tipo == '1' ? ' selected ' : ''); ?> value="1">Tecnologia</option>
			<option <?php echo ($tipo == '3' ? ' selected ' : ''); ?> value="3">Advogados</option>
			<option <?php echo ($tipo == '2' ? ' selected ' : ''); ?> value="2">Secretaria</option>
			<option <?php echo ($tipo == '4' ? ' selected ' : ''); ?> value="4">Administrativo</option>
		</select>
	</div>
	<div class="form-group">
		<label for="nome">Nome:</label>
		<input required type="text" value="<?php echo $nome; ?>" class="form-control" name="nome_usuario" id="nome"/>
	</div>
	<div class="form-group">
		<label for="username">Usuário</label>
		<input required type="text" value="<?php echo $username; ?>" class="form-control" name="username_usuario" id="username"/>
	</div>
	<div class="form-group">
		<label for="email">E-mail</label>
		<input required type="email" value="<?php echo $email; ?>" class="form-control" name="email_usuario" id="email"/>
	</div>
	<div class="form-group">
		<label for="password">Senha</label>
		<label class="float-right" for="up_pass">
			<small>
				<input id="up_pass" type="checkbox" value="1" name="atualizar_senha" checked>
				Redefinir no primeiro login.
			</small>
		</label>
		<input type="password" class="form-control" name="password_usuario" id="password"/>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Atualizar" />
	</div>
	<br>

</form>
<?php

		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}else{
		header("location: index.php?pg=editar-usuario");
	}
?>


