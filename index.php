<?php
session_start();
$usuario_logado = 'Visitante';
$id_usuario = 0;
$tipo_usuario = '0';
$menu_mobile = '';


if ((!isset ($_SESSION['tipo_usuario']) == true) and (!isset ($_SESSION['usuario_logado']) == true)) {
    $menu_mobile = '';
    $usuario_logado = 'Visitante';
    $tipo_usuario = '0';
    unset($_SESSION['id_usuario']);
    unset($_SESSION['tipo_usuario']);
    unset($_SESSION['usuario_logado']);
    session_destroy();
} else {
    $id_usuario = $_SESSION['id_usuario'];
    $nome_usuario = $_SESSION['nome_usuario'];
    $usuario_logado = $_SESSION['usuario_logado'];
    $tipo_usuario = $_SESSION['tipo_usuario'];
    $menu_mobile = '<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>';

    if (isset($_GET['sair'])) {
        $menu_mobile = '';
        $id_usuario = 0;
        $usuario_logado = 'Visitante';
        $tipo_usuario = '0';
        unset($_SESSION['id_usuario']);
        unset($_SESSION['tipo_usuario']);
        unset($_SESSION['usuario_logado']);
        session_destroy();
        $nome_usuario = 'Sistema de Arquivamento';
    }

}

$btn_sair = '<li class="nav-item"><a class="nav-link text-color-default" href="?sair">Sair</a></li>';
$btn_buscar = '<li class="nav-item"><a  id="buscar" class="nav-link text-color-default" href="?pg=buscar">Buscar</a></li>';
$btn_minhas_solicitacoes = '<li class="nav-item"><a id="minhasSolicitacoes" class="nav-link text-color-default" href="?pg=minhas-solicitacoes">Minhas Solicitações</a></li>';
$btn_imprimir_etiqueta = '<li class="nav-item"><a  id="imprimir" class="nav-link text-color-default" href="?pg=imprimir-etiqueta">Imprimir Etiqueta</a></li>';
$btn_limpar = '<li class="nav-item"><a  id="limpar" class="nav-link text-color-default" href="?pg=limpa-banco">Limpar</a></li>';
$btn_importar = '<li class="nav-item"><a  id="importar" class="nav-link text-color-default" href="?pg=importar">Importar</a></li>';
$btn_cad_cliente = '<a class="dropdown-item text-color-default" href="?pg=cadastrar-cliente">Cliente</a>';
$btn_cad_documento = '<a class="dropdown-item text-color-default" href="?pg=cadastrar-documento">Documento</a>';
$btn_cad_usuario = '<a class="dropdown-item text-color-default" href="?pg=cadastrar-usuario">Usuário</a>';
$btn_cad_prateleira = '<a class="dropdown-item text-color-default" href="?pg=cadastrar-prateleira">Prateleira</a>';
$btn_edit_cliente = '<a class="dropdown-item text-color-default" href="?pg=editar-cliente">Cliente</a>';
$btn_edit_documento = '<a class="dropdown-item text-color-default" href="?pg=editar-documento">Documento</a>';
$btn_edit_usuario = '<a class="dropdown-item text-color-default" href="?pg=editar-usuario">Usuário</a>';
$btn_edit_localizacao = '<a class="dropdown-item text-color-default" href="?pg=editar-localizacao">Localização</a>';
$btn_gerar_codigo = '<a id="codigo" class="dropdown-item text-color-default" href="?pg=gerar-codigo">Código</a>';
$btn_gerar_caixa = '<a id="caixa" class="dropdown-item text-color-default" href="?pg=gerar-localizacao&tipo=caixa">Caixa</a>';
$btn_gerar_pasta = '<a id="caixa" class="dropdown-item text-color-default" href="?pg=gerar-localizacao&tipo=pasta">Pasta</a>';
$btn_gerar_etiqueta = '<a id="etiqueta" class="dropdown-item text-color-default" href="?pg=gerar-etiqueta">Etiqueta</a>';

if ($tipo_usuario == '1') {
    $btn_cadastrar = '
      <li class="nav-item dropdown">
        <a id="cadastrar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cadastrar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_cad_cliente .
        $btn_cad_documento .
        $btn_cad_usuario .
        $btn_cad_prateleira .
        '</div>
      </li>';
    $btn_editar = '
      <li class="nav-item dropdown">
        <a id="editar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Editar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_edit_cliente .
        $btn_edit_documento .
        $btn_edit_usuario .
        $btn_edit_localizacao .
        '</div>
      </li>';
    $btn_gerar = '
      <li class="nav-item dropdown">
        <a id="gerar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gerar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_gerar_codigo .
        $btn_gerar_caixa .
        $btn_gerar_pasta .
        $btn_gerar_etiqueta .
        '</div>
      </li>';
    $menu = $btn_cadastrar . $btn_editar . $btn_gerar . $btn_buscar . $btn_imprimir_etiqueta . $btn_limpar . $btn_importar . $btn_sair;
} else if ($tipo_usuario == '2') {
    $btn_cadastrar = '
      <li class="nav-item dropdown">
        <a id="cadastrar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cadastrar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_cad_cliente .
        $btn_cad_documento .
        '</div>
      </li>';
    $btn_editar = '
      <li class="nav-item dropdown">
        <a id="editar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Editar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_edit_cliente .
        $btn_edit_documento .
        $btn_edit_localizacao .
        '</div>
      </li>';
    $btn_gerar = '
      <li class="nav-item dropdown">
        <a id="gerar" class="nav-link dropdown-toggle text-color-default" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gerar</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">' .
        $btn_gerar_caixa .
        $btn_gerar_pasta .
        $btn_gerar_etiqueta .
        '</div>
      </li>';
    $menu = $btn_cadastrar . $btn_editar . $btn_gerar . $btn_buscar . $btn_imprimir_etiqueta . $btn_sair;
} else if ($tipo_usuario == '3' or $tipo_usuario == '4') {
    $menu = $btn_buscar . $btn_minhas_solicitacoes . $btn_sair;
} else {
    $menu = '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <style type="text/css">
        * {
            font-family: 'Titillium Web', sans-serif;
            font-size: 13pt;
        }

        .btn {
            cursor: pointer;
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            border-radius: 0 !important;
        }

        .pointer {
            cursor: pointer;
        }

        .listItem {
            color: #696969;
            text-decoration: none;
        }

        .listItem:hover {
            color: #111;
            text-decoration: none;
        }

        .highlight {
            color: red;
        }

        .text-color-default {
            color: #e5b177 !important;
        }

        a.text-color-default:focus, a.text-color-default:hover {
            color: #A78654 !important;
            background: none;
        }

        .progress {
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            height: 3px;
        }

        .progress-bar {
            background: #A77654 !important;
        }

    </style>
    <title>Sistema de Arquivo</title>
</head>

<body>
<nav style="background: #00263D;" class="text-color-default navbar navbar-toggleable-md sticky-top navbar-inverse hidden-print">
    <?php echo $menu_mobile; ?>
    <a class="navbar-brand" href="?pg=home">
        <img src="img/logo.png" width="30" alt="Logo do Escritório"/>
        <span class="ml-2 text-color-default"><?php echo(isset($nome_usuario) ? $nome_usuario : 'Sistema de Arquivamento'); ?></span>
    </a>

    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav mr-auto">
            <?php echo $menu; ?>
        </ul>
    </div>
</nav>
<input class="hidden-print" type="hidden" id="id_usuario_logado" value="<?php echo $id_usuario; ?>">
<div id="progress" class="progress invisible hidden-print"></div>
<div id="carregaRetornoGerarLoc"></div>
<div class="container">
    <?php

    if (isset($_POST['btn_busca_geral'])) {
        echo '<br>';
        include("listar.php");
    }

    if (isset($_REQUEST['pg'])) {
        $pg = $_REQUEST['pg'];
    } else {
        $pg = '';
    }

    if ($tipo_usuario != '0') {

        switch ($pg) {
            case 'cadastrar-cliente':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("cadastrar-cliente.php");
                }
                break;
            case 'cadastrar-documento':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("cadastrar-documento.php");
                }
                break;
            case 'cadastrar-usuario':
                if ($tipo_usuario == '2' or ($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("cadastrar-usuario.php");
                }
                break;
            case 'cadastrar-prateleira':
                if ($tipo_usuario == '2' or ($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("cadastrar-prateleira.php");
                }
                break;
            case 'editar-cliente':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("editar-cliente.php");
                }
                break;
            case 'editar-documento':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("editar-documento.php");
                }
                break;
            case 'editar-usuario':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("editar-usuario.php");
                }
                break;
            case 'editar-localizacao':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("editar-localizacao.php");
                }
                break;
            case 'form-editar-cliente':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("form-editar-cliente.php");
                }
                break;
            case 'form-editar-documento':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("form-editar-documento.php");
                }
                break;
            case 'form-editar-localizacao':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("form-editar-localizacao.php");
                }
                break;
            case 'form-editar-usuario':
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("form-editar-usuario.php");
                }
                break;
            case "salvar-cadastrar":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include("salvar-cadastrar.php");
                }
                break;
            case "salvar-editar":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include("salvar-editar.php");
                }
                break;
            case "gerar-codigo":
                if ($tipo_usuario == '2' or ($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("gerar-codigo.php");
                }
                break;
            case "gerar-localizacao":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include("gerar-localizacao.php");
                }
                break;
            case "gerar-etiqueta":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include("gerar-etiqueta.php");
                }
                break;
            case "imprimir-etiqueta":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include("imprimir-etiqueta.php");
                }
                break;
            case "limpa-banco":
                if ($tipo_usuario == '2' or ($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("limpa-banco.php");
                }
                break;
            case "importar":
                if ($tipo_usuario == '2' or ($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("home.php");
                } else {
                    include_once("importar.php");
                }
                break;
            case "minhas-solicitacoes":
                if (($tipo_usuario == '3' or $tipo_usuario == '4')) {
                    include_once("minhas-solicitacoes.php");
                } else {
                    include_once("home.php");
                }
                break;
            case 'buscar':
                if ($tipo_usuario == '3' or $tipo_usuario == '4') {
                    include_once("solicitar.php");
                } else {
                    include_once("buscar.php");
                }
                break;
            default:
                include_once("home.php");
                break;
        }
    } else {
        include_once("home.php");
    }


    ?></div>


<?php include_once("js/scripts.php"); //~Incluindo os scripts da página ?>
<br>
<!-- Confirm modal -->
<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="true" id="modalConfirm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title small">Confirmação</span>
            </div>
            <div class="modal-body">
                <h6>Deseja prosseguir com esta ação?</h6>
                <div id="modalConfirmMsg"></div>
            </div>
            <div class="modal-footer">
                <button id="btnSim" type="button" class="btn btn-secondary">Sim</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Redefinir Senha -->
<!-- Modal -->
<div class="modal fade" id="modalSolicitacaoRedefinirSenha" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="redefinirSenha">Solicitar Redefinição de Senha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    Selecione o seu nome:
                    <div id="divEmailParaRedefinicao" class="form-group">
                        <select class="form-control" name="emailParaRedefinicao" id="emailParaRedefinicao">
                            <?php
                            try {
                                require_once("bd/conexao.php");
                                echo "<option value='0'></option>";
                                $usuarios = $conn->query("SELECT id_usuario, nome_usuario FROM usuario WHERE status_usuario != '2' AND tipo_usuario != '1' ORDER BY nome_usuario ASC");
                                foreach ($usuarios as $users) {
                                    echo "<option value='" . $users['id_usuario'] . "'>" . $users['nome_usuario'] . "</option>";
                                }
                            } catch (Exception $e) {
                                echo "<option>Erro ao listar usuários</option>";
                            }

                            ?>
                        </select>
                        <!-- <div class="form-control-feedback small text-center">Você precisa selecionar uma opção.</div> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="spanLoading" class="w-100 text-warning d-none">Aguarde um momento...</span>
                <button type="button" id="btnSolicitaRedefinirSenha" onclick="enviaSolicitacaoRedefinicaoSenha();"
                        class="btn btn-success">Enviar
                </button>
            </div>
        </div>
    </div>
</div>
<div style="background: #00263D; padding: 3px;" class="fixed-bottom text-center hidden-print">
    <img src="img/logo.png" width="25" alt="Logo do Escritório"/>
</div>
</form>
<div id="modalSolicitacao"></div>
</body>
</html>