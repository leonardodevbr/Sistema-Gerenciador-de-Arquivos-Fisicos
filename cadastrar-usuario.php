<br>
<h2 class="text-center">Cadastro de Usuário</h2>
<br>
<form action="?pg=salvar-cadastrar&registro=usuario" method="post">
	<div class="form-group">
		<label for="tipoUsuario">Setor</label>
		<select class="form-control" name="tipo_usuario">
			<option value="3">Advogado</option>
			<option value="2">Secretaria</option>
			<option value="1">Administração</option>
		</select>
	</div>
	<div class="form-group">
		<label for="nome">Nome:</label>
		<input required type="text" class="form-control" name="nome_usuario" id="nome"/>
	</div>
	<div class="form-group">
		<label for="username">Usuário</label>
		<input required type="text" class="form-control" name="username_usuario" id="username"/>
	</div>
	<div class="form-group">
		<label for="email">E-mail</label>
		<input required type="email" class="form-control" name="email_usuario" id="email"/>
	</div>
	<div class="form-group">
		<label for="password">Senha</label>
		<label class="float-right" for="up_pass">
			<small>
				<input id="up_pass" type="checkbox" value="1" name="atualizar_senha" checked>
				Redefinir no primeiro login.
			</small>
		</label>

		<input required type="password" class="form-control" name="password_usuario" id="password"/>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" name="btn_salvar" value="Salvar" />
	</div>
	<br>

</form>
