<form action="" method="post">
  <div class="modal fade" id="modal_redefinir_senha" tabindex="-1" role="dialog" aria-labelledby="Redefinir Senha" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Redefinição de senha necessária.</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="form-group">
          <input id="id_user_redefinir_senha" type="hidden" name="id_usuario"/>
          <input id="nome_user_redefinir_senha" class="form-control" type="text" disabled name="usuario"/>
        </div>
        <div class="form-group">
          <input required id="old_pass" class="form-control" type="password" placeholder="Senha Atual" name="old_pass"/>
        </div>
        <div id="div_new_pass" class="form-group">
          <input required id="new_pass" class="form-control" type="password" placeholder="Nova Senha" name="new_pass"/>
        </div>
        <div id="div_c_new_pass" class="form-group">
          <input required id="c_new_pass" class="form-control" type="password" placeholder="Confirmar Nova Senha" name="c_new_pass"/>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button id="btn_salvar" disabled type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div id="login-container" class="justify-content-center pt-md-5">
  <div class="row justify-content-center p-0">
    <div class="col-lg-6" id="mensagemRetorno"></div>
  </div>
  <div id="form" class="pt-md-5">
    <div class="row p-0 text-center">
      <div id="img_user_login" class="col-12">
        <img id="img_default" src="img/user_default.png" width="80" class="img rounded" alt="User Default">
      </div>
    </div>
    <div class="row justify-content-center p-0">
      <div class="col-12 col-md-6">
        <div class="form-group">
          <label for="user" class="col-form-label small">Usuário</label>
          <input autocomplete="off" name="email" required autofocus type="text" class="form-control" id="user" placeholder="Usuário">
            <div id="feedback-user" class="form-control-feedback text-center small mt-1 p-0 hidden-xs-up">
              Este campo deve conter no mínimo 4 caracteres
            </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center p-0">
      <div class="col-12 col-md-6">
        <div class="form-group">
          <label for="pass" class="col-form-label small">Mesma senha do seu e-mail</label>
          <input name="senha" required type="password" class="form-control" id="pass" placeholder="Senha">
            <div id="feedback-pass" class="form-control-feedback text-center small mt-1 p-0 hidden-xs-up">
              Este campo deve conter no mínimo 5 caracteres
            </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center p-0 text-center">
      <div class="col-12 col-md-6">
        <div class="form-group">
          <button id="btn_login" type="button" name="login" class="btn btn-secondary btn-block text-muted">Entrar</button>
          <div id="feedback-login" class="hidden-xs-up"></div>
          <div id="msgRedefinir"><?php include_once("redefinir_senha.php"); ?></div>
          <div class="col text-info mt-2 p-0 ">
            <button type="button" class="btn btn btn-link" data-toggle="modal" data-target="#modalSolicitacaoRedefinirSenha"><div class="small">Redefinir minha senha</div></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>