<?php
function emailCancelaSolicitacao($id_solicitacao, $conn){
  date_default_timezone_set('America/Sao_Paulo');
  $dadosUsuario = $conn->query("SELECT nome_usuario, email_usuario, password_usuario FROM usuario WHERE id_usuario = (SELECT usuario_id_usuario FROM solicitacao WHERE (id_solicitacao = {$id_solicitacao} AND status_solicitacao = 3))")->fetch();
  $nome_usuario = $dadosUsuario['nome_usuario'];
  $email_usuario = $dadosUsuario['email_usuario'];
  $password_usuario = $dadosUsuario['password_usuario'];
  $numero_solicitacao = $id_solicitacao;

  $dadosResponsaveis = $conn->query("SELECT email_usuario, nome_usuario FROM usuario WHERE (tipo_usuario = '1' OR tipo_usuario = '2') AND recebeEmail = 1");

  foreach ($dadosResponsaveis as $resp) {
    $emailsResponsaveis[] = $resp["email_usuario"];
    $nomeResponsaveis[] = $resp["nome_usuario"];
  }

  $dataAtual = date('d/m/Y', time());
  $horaAtual = date('H:i', time());

  $assunto = "Solicitação Cancelada";
  $mensagem = "<b><h3>".$assunto."</h3></b>";


  $mensagem .= "<br>Solicitante: ".$nome_usuario;
  $mensagem .= "<br><p>A solicitação ".$numero_solicitacao." foi cancelada pelo usuário</p>";
  $mensagem .= "<br>Detalhes:";
  $mensagem .= "<br>Data: ".$dataAtual;
  $mensagem .= "<br>Hora: ".$horaAtual;
  $mensagem .= "<br><br><small>Em caso de dúvidas, entre em contato com o setor de T.I através do endereço <i>ti@homeoffice.dev.br</i></small>";

  // echo $mensagem;

  require_once("class/email/PHPMailerAutoload.php");
  $mail = new PHPMailer(true);
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->CharSet = 'UTF-8';

  try {
    $mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Port       = 587; //  Usar 587 porta SMTP
    $mail->Username = $email_usuario; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = base64_decode($password_usuario); // Senha do servidor SMTP (senha do email usado)

    //Define o remetente
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->SetFrom($email_usuario, $nome_usuario); //Seu e-mail
    $mail->AddReplyTo($email_usuario, $nome_usuario); //Seu e-mail
    $mail->Subject = $assunto;//Assunto do e-mail


    //Define os destinatário(s)
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    for($i = 0; $i < count($emailsResponsaveis); $i++){
      $mail->AddAddress($emailsResponsaveis[$i], $nomeResponsaveis[$i]);
    }

    //Campos abaixo são opcionais
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
    //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo


    //Define o corpo do email
    $mail->MsgHTML($mensagem);

    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
    //$mail->MsgHTML(file_get_contents('arquivo.html'));

    $mail->Send();
    // echo "Mensagem enviada com sucesso</p>\n";
    // echo $mensagem;
    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }catch (phpmailerException $e) {
    echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
  }
}

function emailNovaSolicitacao($nome_usuario, $email_usuario, $pass_usuario, $conn, $numero_solicitacao){
  date_default_timezone_set('America/Sao_Paulo');
  $dadosResponsaveis = $conn->query("SELECT email_usuario, nome_usuario FROM usuario WHERE (tipo_usuario = '1' OR tipo_usuario = '2') AND recebeEmail = 1");

  foreach ($dadosResponsaveis as $resp) {
    $emailsResponsaveis[] = $resp["email_usuario"];
    $nomeResponsaveis[] = $resp["nome_usuario"];
  }

  $status = "Solicitado";
  $assunto = 'Solicitação de Empréstimo';

  $dataAtual = date('d/m/Y', time());
  $horaAtual = date('H:i', time());

  $mensagem = "<b><h3>".$assunto."</h3></b>";


  $mensagem .= "<br>Solicitante: ".$nome_usuario;
  $mensagem .= "<br><p>Um novo pedido de empréstimo foi registrado no sistema. <br>Entre em contato com o solicitante para atender a solicitação.</p>";
  $mensagem .= "<br>Detalhes:";
  $mensagem .= "<br>Nº da Solicitação: ".$numero_solicitacao;
  $mensagem .= "<br>Data: ".$dataAtual;
  $mensagem .= "<br>Hora: ".$horaAtual."hs";
  $mensagem .= "<br>Status: ".$status;
  $mensagem .= "<br><br><small>Em caso de dúvidas, entre em contato com o setor de T.I através do endereço <i>ti@homeoffice.dev.br</i></small>";

  // echo $mensagem;

  require_once("class/email/PHPMailerAutoload.php");
  $mail = new PHPMailer(true);
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->CharSet = 'UTF-8';

  try {
    $mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Port       = 587; //  Usar 587 porta SMTP
    $mail->Username = $email_usuario; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = $pass_usuario; // Senha do servidor SMTP (senha do email usado)

    //Define o remetente
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->SetFrom($email_usuario, $nome_usuario); //Seu e-mail
    $mail->AddReplyTo($email_usuario, $nome_usuario); //Seu e-mail
    $mail->Subject = $assunto;//Assunto do e-mail


    //Define os destinatário(s)
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    for($i = 0; $i < count($emailsResponsaveis); $i++){
      $mail->AddAddress($emailsResponsaveis[$i], $nomeResponsaveis[$i]);
    }

    //Campos abaixo são opcionais
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
    //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo


    //Define o corpo do email
    $mail->MsgHTML($mensagem);

    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
    //$mail->MsgHTML(file_get_contents('arquivo.html'));

    $mail->Send();
    // echo "Mensagem enviada com sucesso</p>\n";

    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }catch (phpmailerException $e) {
    echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
  }

}

function emailReciboEmprestimo($id_solicitacao, $conn, $id_usuario_logado){
  $notificado = $conn->query("SELECT notificado FROM solicitacao WHERE id_solicitacao = {$id_solicitacao}")->fetch();
  if($notificado['notificado'] == 0){
    $conn->query("UPDATE solicitacao SET notificado = 1 WHERE id_solicitacao = {$id_solicitacao}");
    date_default_timezone_set('America/Sao_Paulo');
    $dadosUsuario = $conn->query("SELECT nome_usuario, email_usuario, password_usuario FROM usuario WHERE id_usuario = (SELECT usuario_id_usuario FROM solicitacao WHERE id_solicitacao = {$id_solicitacao})")->fetch();
    $password_usuario = $dadosUsuario['password_usuario'];
    $nome_usuario = $dadosUsuario['nome_usuario'];
    $email_usuario = $dadosUsuario['email_usuario'];
    $numero_solicitacao = $id_solicitacao;
    $item_solicitado = '';

    $dadosResponsaveis = $conn->query("SELECT email_usuario, nome_usuario FROM usuario WHERE (tipo_usuario = '1' OR tipo_usuario = '2') AND recebeEmail = 1");
    $usuarioLogado = $conn->query("SELECT email_usuario, nome_usuario, password_usuario FROM usuario WHERE id_usuario = {$id_usuario_logado}")->fetch();

    foreach ($dadosResponsaveis as $resp) {
      $emailsResponsaveis[] = $resp["email_usuario"];
      $nomeResponsaveis[] = $resp["nome_usuario"];
    }

    $dataAtual = date('d/m/Y', time());
    $horaAtual = date('H:i', time());

    $assunto = "Recibo de Empréstimo";

    $mensagem = "<b><h3>".$assunto."</h3></b>";
    $mensagem .= "<br>Solicitante: ".$nome_usuario;
    $mensagem .= "<br><p>A solicitação de Nº ".$numero_solicitacao." foi atendida em ".$dataAtual.", às ".$horaAtual."hs. <br>Este e-mail confirma que o solicitante detém a posse dos seguintes documentos:</p><ul>";

    $solicitacao = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

    foreach ($solicitacao as $sol) {
      $id_documento = $sol['documento_id_documento'];
      $documento = $conn->query("SELECT cod_documento FROM documento WHERE id_documento = {$id_documento}")->fetch();
      $item_solicitado .= '<li>'.$documento['cod_documento'].'</li>';
    }

    $mensagem .=  $item_solicitado."</ul><br><small>Em caso de dúvidas, entre em contato com o setor de T.I através do endereço <i>ti@homeoffice.dev.br</i></small>";

    // echo $mensagem;

    require_once("class/email/PHPMailerAutoload.php");
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->CharSet = 'UTF-8';

    try {
      $mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
      $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
      $mail->Port       = 587; //  Usar 587 porta SMTP
      $mail->Username = $usuarioLogado['email_usuario']; // Usuário do servidor SMTP (endereço de email)
      $mail->Password = base64_decode($usuarioLogado['password_usuario']); // Senha do servidor SMTP (senha do email usado)

      //Define o remetente
      // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->SetFrom($usuarioLogado['email_usuario'], $usuarioLogado['nome_usuario']); //Seu e-mail
      $mail->AddReplyTo($usuarioLogado['email_usuario'], $usuarioLogado['nome_usuario']); //Seu e-mail
      $mail->Subject = $assunto;//Assunto do e-mail


      //Define os destinatário(s)
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->AddAddress($email_usuario, $nome_usuario);
      for($i = 0; $i < count($emailsResponsaveis); $i++){
        $mail->AddAddress($emailsResponsaveis[$i], $nomeResponsaveis[$i]);
      }

      //Campos abaixo são opcionais
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
      //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
      //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo


      //Define o corpo do email
      $mail->MsgHTML($mensagem);

      ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
      //$mail->MsgHTML(file_get_contents('arquivo.html'));

      $mail->Send();
      // echo "Mensagem enviada com sucesso</p>\n";
      // echo $mensagem;
      //caso apresente algum erro é apresentado abaixo com essa exceção.
      }catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
    }
  }else{
    return false;
  }
}

function emailReciboDevolucao($id_solicitacao, $conn, $id_usuario_logado){
 $notificado = $conn->query("SELECT notificado FROM solicitacao WHERE id_solicitacao = {$id_solicitacao}")->fetch();
  if($notificado['notificado'] == 1){
    $conn->query("UPDATE solicitacao SET notificado = 2 WHERE id_solicitacao = {$id_solicitacao}");
    date_default_timezone_set('America/Sao_Paulo');
    $dadosUsuario = $conn->query("SELECT nome_usuario, email_usuario, password_usuario FROM usuario WHERE id_usuario = (SELECT usuario_id_usuario FROM solicitacao WHERE id_solicitacao = {$id_solicitacao})")->fetch();
    $nome_usuario = $dadosUsuario['nome_usuario'];
    $password_usuario = $dadosUsuario['password_usuario'];
    $email_usuario = $dadosUsuario['email_usuario'];
    $numero_solicitacao = $id_solicitacao;
    $item_solicitado = '';

    $dadosResponsaveis = $conn->query("SELECT email_usuario, nome_usuario FROM usuario WHERE (tipo_usuario = '1' OR tipo_usuario = '2') AND recebeEmail = 1");
    $usuarioLogado = $conn->query("SELECT email_usuario, nome_usuario, password_usuario FROM usuario WHERE id_usuario = {$id_usuario_logado}")->fetch();

    foreach ($dadosResponsaveis as $resp) {
      $emailsResponsaveis[] = $resp["email_usuario"];
      $nomeResponsaveis[] = $resp["nome_usuario"];
    }

    $dataAtual = date('d/m/Y', time());
    $horaAtual = date('H:i', time());

    $assunto = "Recibo de Devolução";

    $mensagem = "<b><h3>".$assunto."</h3></b>";
    $mensagem .= "<br>Solicitante: ".$nome_usuario;
    $mensagem .= "<br><p>A solicitação de Nº ".$numero_solicitacao." foi finalizada em ".$dataAtual.", às ".$horaAtual."hs. <br>Este e-mail confirma que o solicitante devolveu para o arquivo os seguintes documentos:</p><ul>";

    $solicitacao = $conn->query("SELECT * FROM solicitacao_has_documento WHERE solicitacao_id_solicitacao = {$id_solicitacao}");

    foreach ($solicitacao as $sol) {
      $id_documento = $sol['documento_id_documento'];
      $documento = $conn->query("SELECT cod_documento FROM documento WHERE id_documento = {$id_documento}")->fetch();
      $item_solicitado .= '<li>'.$documento['cod_documento'].'</li>';
    }

    $mensagem .=  $item_solicitado."</ul><br><small>Em caso de dúvidas, entre em contato com o setor de T.I através do endereço <i>ti@homeoffice.dev.br</i></small>";

    // echo $mensagem;

    require_once("class/email/PHPMailerAutoload.php");
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->CharSet = 'UTF-8';

    try {
      $mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
      $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
      $mail->Port       = 587; //  Usar 587 porta SMTP
      $mail->Username = $usuarioLogado['email_usuario']; // Usuário do servidor SMTP (endereço de email)
      $mail->Password = base64_decode($usuarioLogado['password_usuario']); // Senha do servidor SMTP (senha do email usado)

      //Define o remetente
      // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->SetFrom($usuarioLogado['email_usuario'], $usuarioLogado['nome_usuario']); //Seu e-mail
      $mail->AddReplyTo($usuarioLogado['email_usuario'], $usuarioLogado['nome_usuario']); //Seu e-mail
      $mail->Subject = $assunto;//Assunto do e-mail


      //Define os destinatário(s)
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->AddAddress($email_usuario, $nome_usuario);
      for($i = 0; $i < count($emailsResponsaveis); $i++){
        $mail->AddAddress($emailsResponsaveis[$i], $nomeResponsaveis[$i]);
      }

      //Campos abaixo são opcionais
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
      //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
      //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo


      //Define o corpo do email
      $mail->MsgHTML($mensagem);

      ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
      //$mail->MsgHTML(file_get_contents('arquivo.html'));

      $mail->Send();
      // echo "Mensagem enviada com sucesso</p>\n";
      // echo $mensagem;
      //caso apresente algum erro é apresentado abaixo com essa exceção.
      }catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
    }
  }else{
    return false;
  }
}



function emailRecuperarSenha($email, $code, $id, $conn){
    require_once("config.php");//Arquivo chamado para utilizar as constantes
    date_default_timezone_set('America/Sao_Paulo');

    $dataAtual = date('d/m/Y', time());
    $horaAtual = date('H:i', time());

    $assunto = "Redefinição de Senha";
    $linkRedefinicao = "<a href='http://arquivo.homeoffice.dev.br/redefinindo-senha.php?code=".$code."'>neste link</a>";
    $mensagem = "<b><h3>Alguém solicitou uma redefinição de senha para o usuário vinculado a este e-mail</h3></b>";
    $mensagem .= "Solicitado em : ".$dataAtual.", às ".$horaAtual."hs.<br><br>";
    $mensagem .= "Clique ".$linkRedefinicao." e informe os dados abaixo na tela de login do sistema: <br><br>";
    $mensagem .= "<br>E-mail: ".$email;
    $mensagem .= "<br>".SENHA_DEFAULT_REC;

    // echo $mensagem;
    
    require_once("class/email/PHPMailerAutoload.php");
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->CharSet = 'UTF-8';
    $usuarioDeEnvio = "Suporte - Sistema de Arquivo";
    $emailDeEnvio = EMAIL_ADMIN_EMAIL;//Adicionar o valor para essa constante no arquivo "config.php"
    $senhaDeEnvio = PASS_ADMIN_EMAIL;//Adicionar o valor para essa constante no arquivo "config.php"

    try {
      $mail->Host = 'smtp.office365.com'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
      $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
      $mail->Port       = 587; //  Usar 587 porta SMTP
      $mail->Username = $emailDeEnvio; // Usuário do servidor SMTP (endereço de email)
      $mail->Password = $senhaDeEnvio; // Senha do servidor SMTP (senha do email usado)

      //Define o remetente
      // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->SetFrom($emailDeEnvio, $usuarioDeEnvio); //Seu e-mail
      $mail->AddReplyTo($emailDeEnvio, $usuarioDeEnvio); //Seu e-mail
      $mail->Subject = $assunto;//Assunto do e-mail


      //Define os destinatário(s)
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      $mail->AddAddress($email);

      //Campos abaixo são opcionais
      //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
      //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
      //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
      //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo


      //Define o corpo do email
      $mail->MsgHTML($mensagem);

      ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
      //$mail->MsgHTML(file_get_contents('arquivo.html'));

      $mail->Send();
      // echo "Mensagem enviada com sucesso</p>\n";
      // echo $mensagem;
      //caso apresente algum erro é apresentado abaixo com essa exceção.
      }catch (phpmailerException $e) {
      echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
    }
}


function modal_redefinir_senha($msg, $id, $usuario){
  $modal = '
  <form action="" method="post">
    <div class="modal fade" id="modal_redefinir_senha" tabindex="-1" role="dialog" aria-labelledby="Redefinir Senha" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Redefinir Senha</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div class="form-group">
            <input type="hidden" value="'.$id.'" name="id_usuario"/>
            <input class="form-control" type="text" disabled value="'.$usuario.'" name="usuario"/>
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
            <span id="spanLoadingSalvar" class="w-100 text-warning d-none">Aguarde um momento...</span>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button id="btn_salvar" disabled type="submit" class="btn btn-info">Salvar</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  ';
  echo $modal;
}

function unlinkRecursive($dir, $deleteRootToo){
  if(!$dh = @opendir($dir)){
    return;
  }

  while (false !== ($obj = readdir($dh))){
    if($obj == '.' || $obj == '..'){
      continue;
    }

    if (!@unlink($dir . '/' . $obj)){
      unlinkRecursive($dir.'/'.$obj, true);
    }
  }

  closedir($dh);

  if ($deleteRootToo){
    @rmdir($dir);
  }

  return;
}

function limitarTexto($texto, $limite){
    $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' '));
    return $texto;
}

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
}

function mensagem($msg, $type, $page = null, $time = null){
$mensagem = '
  <div class="row-12 text-center">
    <div class="alert alert-'.$type.'" role="alert">'.$msg.'</div>
  </div>';
  echo "<br>".$mensagem;
  if($time){
    echo '<script>window.setTimeout("location.href=\'?pg='.$page.'\';", '.$time.');</script>';
  }
}

function mensagem_close($msg, $type, $time = null, $show_close = true){
$mensagem = '
<div class="row-12 text-center">
  <div class="alert text-center alert-'.$type.' alert-dismissible fade show" role="alert">
    '.($show_close ? '
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
      ' : '').'
    <span>'.$msg.'</span>
  </div>
</div>';
  echo "<br>".$mensagem;
  if($time){
    echo '
    <script>
      setTimeout(function(){
          $(".alert").alert("close");
      }, '.$time.');
    </script>';
  }
}

function btn($link, $text, $class, $type='button', $id=null){
  $btn = '<button type="'.$type.'" class="btn mt-3 '.$class.'" onclick="location.href=\'?'.$link.'\';">'.$text.'</button>';
  echo $btn;
}
?>