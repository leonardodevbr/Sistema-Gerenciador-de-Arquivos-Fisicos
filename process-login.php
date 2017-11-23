<?php
  $user = strip_tags(trim(filter_input(INPUT_POST, 'email')));
  $pass = base64_encode(($_POST['senha']));
  require("bd/conexao.php");
  include("redefinir_senha.php");
  $sqlBusca = "SELECT * FROM usuario WHERE username_usuario = '{$user}' OR email_usuario = '{$user}'";

  try {
    $busca = $conn->query($sqlBusca);
    $userCount = $busca->rowCount();

    if($userCount > 0){
      $sqlLogin = "SELECT * FROM usuario WHERE (username_usuario = '{$user}' OR email_usuario = '{$user}') AND password_usuario = '{$pass}'";
      $login = $conn->query($sqlLogin);
      $countLogin = $login->rowCount();

      if($countLogin == 1){
        foreach ($login as $row);
        $status_usuario = $row['status_usuario'];
        $atualizar_senha = $row['atualizar_senha'];
        $nome_user = $row['nome_usuario'];
        $id = $row['id_usuario'];

        if($status_usuario == 0){
          $return['resultado'] = 'bloqueado';
          $msg = "Este usuário está com o acesso bloqueado!<br>Procure o setor de TI e informe o ocorrido.";
        }else if($atualizar_senha == 1){
          $return['resultado'] = 'atualizar_senha';
          $return['id'] = $id;
          $msg = 'Atualize sua senha!';
          $return['user'] = $nome_user;
        }else{
          session_start();

          $dataUser = $conn->query("SELECT * FROM usuario WHERE id_usuario = '{$id}'")->fetch();
          $tipo = $dataUser['tipo_usuario'];
          $email_usuario = $dataUser['email_usuario'];
          $nome_usuario = $dataUser['nome_usuario'];

          $_SESSION['tipo_usuario'] = $tipo;
          $_SESSION['nome_usuario'] = $nome_usuario;
          $_SESSION['usuario_logado'] = $email_usuario;
          $_SESSION['id_usuario'] = $id;
          $conn->query("UPDATE usuario SET alerta_usuario = 0, status_usuario = 1 WHERE username_usuario = '{$user}' OR email_usuario = '{$user}'");
          $return['resultado'] = 'ok';
          $msg = 'Realizando Login...';
        }

      }else{

        $countAlerta = $conn->query("SELECT alerta_usuario FROM usuario WHERE username_usuario = '{$user}' OR email_usuario = '{$user}'")->fetch();
        if($countAlerta[0] == 4){
          $conn->query("UPDATE usuario SET status_usuario = 0 WHERE username_usuario = '{$user}' OR email_usuario = '{$user}'");
          $msg = "Seu acesso ao sistema foi bloqueado. Procure o setor de TI.";
          $return['resultado'] = $msg;
        }else{
          $cont = 1 + $countAlerta[0];
          try {
            $conn->query("UPDATE usuario SET alerta_usuario = {$cont} WHERE username_usuario = '{$user}' OR email_usuario = '{$user}'");
            $tent = 5 - $cont;
            if($tent == 1){
               $msg = "Senha incorreta!<br>Você tem apenas mais uma tentativa.";
               $return['resultado'] = $msg;
             }else{
               $msg = "Senha incorreta!<br>Você tem mais ".$tent." tentativas.";
               $return['resultado'] = $msg;
             }
          } catch (Exception $alerta) {
            $msg = "Erro ao atualizar o campo alerta da tabela usuario<br>Detalhes: ".$alerta->getMessage();
            $return['resultado'] = $msg;
          }
        }
      }

    }else{
      $msg = "Nenhum registro para '<b>".$user."</b>'";
      $return['resultado'] = $msg;
    }

  } catch (Exception $e) {
    $msg = "Erro ao verificar dados no Banco<br>Detalhes: ".$e->getMessage();
    $return['resultado'] = $msg;
  }
  $return['msg'] = $msg;
  echo json_encode($return);
?>