<script src="js/jquery-3.2.1.js"></script>
<script src="js/jquery.highlight-5.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>

function enviaSolicitacaoRedefinicaoSenha(){
    var id_usuario = $("#emailParaRedefinicao").val();

    if(id_usuario != '0'){
        $("#spanLoading").removeClass('d-none');
        $("#btnSolicitaRedefinirSenha").text("Enviando");
        $("#btnSolicitaRedefinirSenha").toggleClass('btn-success').toggleClass('btn-info');
        $.post(
            "enviaSolicitacaoRedefinirSenha.php",
            "id="+id_usuario,
            function(result){
                switch (result.status_ret) {
                    case 1:
                        tipo_msg = 'success';
                    break;
                    case 0:
                        tipo_msg = 'danger';
                    break;
                }
                $("#modalSolicitacaoRedefinirSenha").modal('hide');
                $("#spanLoading").addClass('d-none');
                $("#btnSolicitaRedefinirSenha").text("Enviar");
                $("#btnSolicitaRedefinirSenha").toggleClass('btn-info').toggleClass('btn-success');
                $("#emailParaRedefinicao").val($('option:contains("")').val());
                mensagem_close(result.msg, tipo_msg, '#mensagemRetorno');
            },
        'json');
    }
}

function carrega($id){
    $("#"+$id).append("<div class='text-center'><small>Carregando...</small><br><img src='img/carregando.gif' alt='Carregando...'/><br></div>");
}

function marcardesmarcar(){
  $(".doc").each(
         function(){
           if ($(this).prop( "checked")){
                $(this).prop("checked", false);
           }else{
                $(this).prop("checked", true);
           }
         }
    );
}

function desmarcar(){
  $(".doc").each(
         function(){
           $(this).prop("checked", false);
         }
    );
}

function geraEtiqueta(id_cliente){
    var id_local = $("#selectLocalizacaoEtiqueta").val();
    // alert('ID LOCAL: '+id_local+' - ID CLIENTE: '+id_cliente);
    $.post("geraEtiqueta", "localizacao="+id_local+"&cliente="+id_cliente, function(etiquetas){
        $("#etiquetasCarregadas").remove();
        $("#carregaEtiqueta").append(etiquetas);
    });
}

function mensagem_close(msg, type, id_local, time = null){
var mensagem = '<div id="alertMsg" class="alert text-center alert-'+type+' alert-dismissible fade show mt-3" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span>'+msg+'</span></div>';
    $("#alertMsg").remove();
    $(id_local).removeClass("hidden-xs-up");
    $(id_local).append(mensagem);

    if(time){
        setTimeout(function(){
            $(".alert").alert('close');
        }, time);
    }
}

function imprimirModal(){
    window.print();
}

function existeDoc(id){
    var doc =  $("#"+id).val();
    if(doc != ''){

        $.ajax({
        type: 'post',
        url: 'existeDoc.php',
            data: {
                documento: doc,
            },
            success: function(texto) {
                $("#"+id).append(texto);
            }
        });
    }else{
        $("#erroDoc").remove();
    }
}

function active(id){
    $(id).addClass("active");
}

function next_cod_doc(id_cso = null){
    // console.clear();
    var id_cliente = $('#clienteDoc').val();
    var id_caso;

    if(id_cso == null){
        id_caso = $("#casoDoc").val();
    }else{
        id_caso = id_cso;
    }

    $.post(
        "next-cod-doc.php",
        "id_cliente="+id_cliente+"&id_caso="+id_caso,
        function(data){
            $("#tmpCodDoc").remove();
            $("#nextCod").append("<b id='tmpCodDoc'>"+data+"</b>");
            $("#codDocumento").val(data);
        });
    $("#descricao").focus();
}

function empresta(id_solicitacao, id_usuario){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $("#carregaSolicitacoes").empty();
        $("#carregando").empty();
        carrega("carregando");
        $.post("empresta.php", "id_solicitacao="+id_solicitacao+"&id_usuario="+id_usuario, function(data){
            if (data == 'ok'){
                console.log('Empréstimo realizado com sucesso!');
                $('#modalConfirm').modal('toggle');
                $.post("recibo.php", "tipo=emprestimo&id_solicitacao="+id_solicitacao);
                setTimeout(function(){
                    $("#carregaSolicitacoes").load("readSolicitacoes.php");
                    $("#carregando").empty();
                }, 1000);
            }else{
                console.log(data);
                alert(data);
                $("#carregaSolicitacoes").load("readSolicitacoes.php");
            }
        });
    });
}

function devolve(id_solicitacao, id_usuario){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $("#carregaSolicitacoes").empty();
        $("#carregando").empty();
        carrega("carregando");
        $.post("devolve.php", "id_solicitacao="+id_solicitacao+"&id_usuario="+id_usuario, function(data){
            if (data == 'ok'){
                console.log('Devolução realizada com sucesso!');
                $('#modalConfirm').modal('toggle');
                $.post("recibo.php", "tipo=devolve&id_solicitacao="+id_solicitacao);
                setTimeout(function(){
                    $("#carregaSolicitacoes").load("readSolicitacoes.php");
                    $("#carregando").empty();
                }, 1000);
            }else{
                console.log(data);
                alert(data);
                $("#carregaSolicitacoes").load("readSolicitacoes.php");
            }
        });
    });
}

function remove(id_solicitacao, id_usuario){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $.post("devolve.php", "id_solicitacao="+id_solicitacao+"&id_usuario="+id_usuario, function(data){
            if (data == 'ok'){
                console.log('Solicitação removida da lista!');
                $('#modalConfirm').modal('toggle');
                setTimeout(function(){
                    $("#carregaSolicitacoes").load("readSolicitacoes.php");
                }, 1000);
            }else{
                console.log(data);
                alert(data);
                $("#carregaSolicitacoes").load("readSolicitacoes.php");
            }
        });
    });
}

function reabrir(id_solicitacao, id_usuario){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $.post("empresta.php", "id_solicitacao="+id_solicitacao+"&id_usuario="+id_usuario, function(data){
            if (data == 'ok'){
                console.log('Solicitação raberta com sucesso!');
                $('#modalConfirm').modal('toggle');
                setTimeout(function(){
                    $("#carregaSolicitacoes").load("readSolicitacoes.php");
                }, 1000);
            }else{
                console.log(data);
                alert(data);
                $("#carregaSolicitacoes").load("readSolicitacoes.php");
            }
        });
    });

}

function infoSolicitacao(id_solicitacao){
    $.post("infoSolicitacao.php", "id_solicitacao="+id_solicitacao, function(info){
        $("#tableInfoSolicitacoes").empty();
        $("#tableInfoSolicitacoes").append(info);
        $("#infoSolicitacoesModal").modal("show");
    });
}

function btnCancelaSolicitacao(id_solicitacao){
    $("#id_solicitacao_cancelar").attr("value", id_solicitacao);
    $("#cancelaSolicitacaoModal").modal("show");
}

function cancelaSolicitacao(){
    carrega("alertMsg");
    var id_solicitacao = $("#id_solicitacao_cancelar").val();
    $.post("cancelaSolicitacao.php", "id_solicitacao="+id_solicitacao, function(del){
        mensagem_close(del, "info", "#recebeInformacoes", 1000);
    });
    setTimeout(function(){
        var id_usuario = $("#id_usuario_logado").val();
        $("#carregaMinhasSolicitacoes").load("readMinhasSolicitacoes.php?id_usuario="+id_usuario);
    }, 6000)
}


function delLocalizacao(id){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $.post("del-localizacao.php", "id="+id, function(data){
            if(data){
                $('#modalConfirm').modal('toggle');
                readLocalizacoes("#readLocalizacoes", "editar-localizacao");
                mensagem_close("Localização excluída com sucesso!<br><strong>Os documentos foram desalocados.</strong>", "success", "#retorno", 1000);
            }
        });
    });
}

function delDoc(id){
    $('#modalConfirm').modal('toggle');

    $("#btnSim").click(function () {
        $.post("deleta-documento.php", "id_doc="+id, function(data){
            if(data == 'ok'){
                $('#modalConfirm').modal('toggle');
                mensagem_close("Documento excluído com sucesso!", "success", "#retorno");
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }else{
                alert(data);
            }
            // $('#modalConfirm').modal('toggle');
            // $("#retorno").append(data);
        });
    });
}

function explodeURL(){
    var url = location.search.slice(1);
    var partes = url.split('&');
    var data = {};
    partes.forEach(function (parte) {
        var chaveValor = parte.split('=');
        var chave = chaveValor[0];
        var valor = chaveValor[1];
        data[chave] = valor;
    });
    return data;
}

function urlAtual(){
    var url = location.search.slice(1);
    return url;
}

function listaCaso(id_cliente, adicionar){
    $(".casoDoc").remove();
    var url = explodeURL();
    var cs_id;
    if(url['cs_id']){
        cs_id = url['cs_id'];
        // console.log(cs_id);
    }else{
        cs_id = 0;
        // console.log(cs_id);
    }

    $.post("js/listaCaso.php", "id="+id_cliente+"&cs_id="+cs_id, function(data) {
        $(adicionar).append(data);
    });
}

function readLocalizacoes(adicionar, page){
    $("#divErros").empty();
    $("#localizacaoEdit").remove();
    var url = explodeURL();
    var id_cliente;
    if($("#clienteLocalizacaoEdit").val()){
        id_cliente = $("#clienteLocalizacaoEdit").val();
        var dataSend = "id_cliente="+id_cliente+"&page="+page;
    }else if($("#clienteGerarEtiqueta").val()){
        id_cliente = $("#clienteGerarEtiqueta").val();
        var dataSend = "id_cliente="+id_cliente+"&page="+page;
    }else if($("#selectClienteGerarLocalizacao").val()){
        id_cliente = $("#selectClienteGerarLocalizacao").val();
        var tipo = url['tipo'];
        var id_caso = ($("#selectCasoGerarLocalizacao").val() ? $("#selectCasoGerarLocalizacao").val() : id_cliente);
        var dataSend = "id_cliente="+id_cliente+"&id_caso="+id_caso+"&tipo="+tipo+"&page="+page;
        $(adicionar).empty();
    }else if(url['id_cliente']){
        id_cliente = url['id_cliente'];
        var dataSend = "id_cliente="+id_cliente+"&page="+page;
    }

    $.post("readLocalizacoes.php", dataSend, function(data) {
        $(adicionar).append(data);
        $("#divErros").append(data);
    });
}

function marcaTermo(termo){
    // $('#'+input).bind('keyup change', function(ev) {
    //     // pull in the new value
        // var searchTerm = $('#'+input).val();

        // remove any old highlighted terms
        $('table').removeHighlight();

        // disable highlighting if empty
        if (termo) {
            // highlight the new term
            var termoArr = termo.split(' ');
            for(var i = 0; i < termoArr.length; i++){
                $('table').highlight(termoArr[i]);
            }
        }
    // });
}

function getPageListar(tabela, tipo_usuario, termo){
    // alert(tabela+" - "+tipo_usuario+" - "+termo);
    $.post(
        "listar.php",
        "tabela="+tabela+"&tipo="+tipo_usuario+"&termo="+termo,
        function(resultado){
            $("#buscaCarregada").remove();
            $("#limpaPesquisa").remove();
            $("#carregaBusca").append(resultado);
            marcaTermo(termo);
        });
}

function btn_back(){
    history.back();
}

function eq(e, q){
    if(e == q){
        return true;
    }else{
        return false;
    }
}

function chama_modal(id_modal, id_user, nome_user){
    $("#"+id_modal).modal('show');
    $("#nome_user_redefinir_senha").val(nome_user);
    $("#id_user_redefinir_senha").val(id_user);
}

function login(){
    var email = $("#user").val();
    var senha = $("#pass").val();
    $.post(
        "process-login.php",
        "email="+email+"&senha="+senha,
        function(retorno){
            var resultado = retorno.resultado;
            var msg;
            switch (resultado) {
               case 'ok':
                    msg = retorno.msg;
                    mensagem_close(msg, "success", "#feedback-login");
                    progressBar("progress", 15, "home");
               break;
               case 'atualizar_senha':
                    var id_user = retorno.id;
                    var nome_user = retorno.user;
                    chama_modal("modal_redefinir_senha", id_user, nome_user);
               break;
               case 'bloqueado':
                    msg = retorno.msg;
                    mensagem_close(msg, "danger", "#feedback-login");
               break;
               default:
                    msg = retorno.msg;
                    mensagem_close(msg, "danger", "#feedback-login");
                    console.log(resultado);
               break;
            }
        }, 'json');
}

function progressBar(id, time=10, pg=null){
    var id = "#"+id;
    $(id).removeClass('invisible');
    var i = 0;
    var inte = setInterval(function(){
        i++;
        if(i <= 100){
            $(id).html('<div class="progress-bar" style="width:'+i+'%" role="progressbar"></div>');
        }else{
            $(id).addClass('invisible');
            clearInterval(inte);
            if(pg != null){
                location.href="?ph="+pg;
            }
        }
    } ,time);
}

function img_login(change = true, img = null){
    if(change){
        $("#img_default").addClass("hidden-xs-up");
        $("#img_user").removeClass("hidden-xs-up");
    }else{
        $("#img_default").removeClass("hidden-xs-up");
        $("#img_user").addClass("hidden-xs-up");
    }
}

function gerarLocalizacao(id_cliente, num_localizacao, id_caso, id_prateleira, id_localizacao, doc, tipo = 'CAIXA'){
    $("#carregaRetornoGerarLoc").empty();
    $.post(
        "geraLocalizacao.php",
        "id_cliente="+id_cliente+"&num_localizacao="+num_localizacao+"&id_caso="+id_caso+"&id_prateleira="+id_prateleira+"&id_localizacao="+id_localizacao+"&tipo="+tipo+"&doc="+doc,
        function(resposta){
            $("#carregaRetornoGerarLoc").append(resposta);
            $("#prateleiraGerarLocalizacao").attr("disabled", false);
        });
}

function exec(){
    var id_cliente = $("#selectClienteGerarLocalizacao").val();
    var num_localizacao = $("#localizacaoGerarLocalicazao").val();
    var id_caso = $("#selectCasoGerarLocalizacao").val();
    var id_prateleira = $("#prateleiraGerarLocalizacao").val();
    var id_localizacao = $("#localizacaoGerarLocalicazao option:selected").attr("id");
    var tipo = $("#tipoLocalizacao").val();
    var doc = new Array();
    $("input[name='doc[]']:checked").each(function(){
        doc.push($(this).val());
    });
    gerarLocalizacao(id_cliente, num_localizacao, id_caso, id_prateleira, id_localizacao, doc, tipo);
    setTimeout(function(){
        $("#carregaRetornoGerarLoc").empty();
        $.post(
            "documentos-desalocados.php",
            "id_cliente="+id_cliente+"&id_caso="+id_caso+"&tipo="+tipo,
        function(docs){
            $("#carregaDocumentosDesalocados").empty();
            $("#carregaDocumentosDesalocados").append(docs);
            readLocalizacoes("#localizacaoGerarLocalicazao", "gerar-localizacao");
        });
    }, 3000);
}

function casosGerarLocalizacao(id_cliente, id_caso = 0){
    $("#colSelectCaso").remove();
    var row = $("#colSelectCliente").parent();
    $.post("busca-casos.php", "id_cliente="+id_cliente+"&id_caso="+id_caso, function(resultCasos){
        var qntCasos = resultCasos.qntCasos;
        var colCasos = resultCasos.colSelectCaso;
        // if(qntCasos > 1){
            $("#colSelectCliente").addClass("col-md-6");
            row.append(colCasos);
            readLocalizacoes("#localizacaoGerarLocalicazao", "gerar-localizacao");
            $('#selectCasoGerarLocalizacao').on('change', function(){
                readLocalizacoes("#localizacaoGerarLocalicazao", "gerar-localizacao");
            });
        // }else{
        //     $("#colSelectCliente").removeClass("col-md-6");
        //     $("#colSelectCaso").remove();
        // }
    },'json');
}

function marcarUp(idMarcado){
    // console.log(idMarcado);
    if($("#"+idMarcado).prop("checked")){
        for(var i = idMarcado; i >= 1; i--){
            $("#"+i).prop("checked", true);
        }
    }
}

$('#addCaso').on('shown.bs.modal', function() {
  $('#caso_descricao').focus();
})

$("#btn_login").click(function(){
    login();
});

$("#termo").on('keydown', function(event) {
    if(event.keyCode === 13) {
        getPageListar($('input:radio[name=tabela]:checked').val(),<?php echo $tipo_usuario; ?>,$(this).val());
    }
});

$("#user").keyup(function() {
    var user = $(this).val();
    if(user.length == 0){
        $(this).parent().removeClass('has-warning');
        $(this).parent().removeClass('has-danger');
        $(this).parent().removeClass('has-success');
        $('#feedback-user').addClass('hidden-xs-up');
    }else if(user.length >= 4){
        $(this).parent().removeClass('has-warning');
        $(this).parent().addClass('has-success');
        $(this).addClass('form-control-success');
        $('#feedback-user').addClass('hidden-xs-up');
    }else{
        $(this).parent().removeClass('has-success');
        $(this).parent().addClass('has-warning');
        $(this).addClass('form-control-warning');
        $('#feedback-user').removeClass('hidden-xs-up');
    }
});

$('#pass').keyup(function(ev){
    var user = $("#user").val();
    var pass = $(this).val();
    if(ev.keyCode === 13){
        if(user.length >= 4 && pass.length >= 5){
                login();
            }else{
                $(this).parent().removeClass('has-success');
                $(this).parent().addClass('has-danger');
                $(this).addClass('form-control-danger');
                $('#feedback-pass').removeClass('hidden-xs-up');
            }
    }else{
        if(pass.length == 0){
            $(this).parent().removeClass('has-warning');
            $(this).parent().removeClass('has-danger');
            $(this).parent().removeClass('has-success');
            $('#feedback-pass').addClass('hidden-xs-up');
        }else if(user.length >= 4 && pass.length >= 5){
            $(this).parent().removeClass('has-warning');
            $(this).parent().removeClass('has-danger');
            $(this).parent().addClass('has-success');
            $(this).addClass('form-control-success');
            $('#feedback-pass').addClass('hidden-xs-up');
        }else{
            $(this).parent().removeClass('has-success');
            $(this).parent().removeClass('has-danger');
            $(this).parent().addClass('has-warning');
            $(this).addClass('form-control-warning');
            $('#feedback-pass').removeClass('hidden-xs-up');
        }
    }
});

$('#modal_redefinir_senha').on('shown.bs.modal', function() {
  $('#old_pass').focus();
    $('#c_new_pass').keyup(function() {
        var new_pass = $("#new_pass").val();
        if($("#c_new_pass").val() != new_pass){
            $("#div_c_new_pass").removeClass('has-success');
            $("#c_new_pass").removeClass('form-control-success');
            $("#div_c_new_pass").addClass('has-danger');
            $("#c_new_pass").addClass('form-control-danger');
            $("#btn_salvar").attr("disabled", true);
            $("#btn_salvar").click(function() {
                $("#spanLoadingSalvar").removeClass('d-none');
                $("#btn_salvar").text("Alterando");
                $("#btn_salvar").toggleClass('btn-info').toggleClass('btn-success');
            });
        }else{
            $("#div_c_new_pass").removeClass('has-danger');
            $("#c_new_pass").removeClass('form-control-danger');
            $("#div_c_new_pass").addClass('has-success');
            $("#c_new_pass").addClass('form-control-success');
            $("#btn_salvar").attr("disabled", false);
        }
    });
})

$('#modal_redefinir_senha').on('close.bs.modal', function() {
    alert("Fechou!");
    $("#spanLoadingSalvar").addClass('d-none');
    $("#btn_salvar").text("Salvar");
    $("#btn_salvar").toggleClass('btn-success').toggleClass('btn-info');
});

$('#geraCodigoAdmin').change(function(){
    var opcao = $(this).val();
        // alert(opcao);
        if (opcao == 'cliente') {
            $("#cliente").remove();
            $("#dOpcao").append('<div id="inicial" class="form-group"><label>Inicial do cliente</label><input type="text" id="inicialTxt" class="form-control" name="codigo"/></div>');
            $("#inicialTxt").focus();
        }else if(opcao == 'documento'){
            $("#inicial").remove();
            $.get("js/listaCliente.php?m=1", function( data ) {
                $("#dOpcao").append(data);
            });
        }else{
            $("#cliente").remove();
            $("#inicial").remove();
        }
});

$("#btn_buscar").click(function(){
    getPageListar($('input:radio[name=tabela]:checked').val(),<?php echo $tipo_usuario; ?>,$('input:text[name=termo]').val());
});

$("#filter_cli").change(function(){
    var cliente_id = $(this).val();
    location.href = "?pg=editar-documento&filter_cli="+cliente_id;
});

$("#filter_caso").change(function(){
    var cliente_id = $("#filter_cli").val();
    var caso_id = $(this).val();
    location.href = "?pg=editar-documento&filter_cli="+cliente_id+"&filter_caso="+caso_id;
});

$("#todos_docs").click(function(){
    marcardesmarcar();
});

$("#limpar_docs").click(function(){
    desmarcar();
});

$("#btnBuscarGeralLocalizacao").click(function(){
    var id_cliente = $("#selectClienteGerarLocalizacao").val();
    var select_caso = $("#selectCasoGerarLocalizacao").val();
    var id_caso = (select_caso == null ? id_cliente : select_caso);
    var tipo = $("#tipoLocalizacao").val();
    casosGerarLocalizacao(id_cliente, id_caso);
    $.post(
        "documentos-desalocados.php",
        "id_cliente="+id_cliente+"&id_caso="+id_caso+"&tipo="+tipo,
    function(docs){
        $("#carregaDocumentosDesalocados").empty();
        $("#carregaDocumentosDesalocados").append(docs);
    });
});

$('#sqlImport').on('click', function() {
    $('#sqlImport').select();
});

$('#clienteDoc').change(function(){
    var id = $(this).val();
    listaCaso(id, "#casoDoc");
    next_cod_doc();
});

$('#clienteLocalizacaoEdit').change(function(){
    readLocalizacoes("#readLocalizacoes", "editar-localizacao");
});

$('#clienteGerarEtiqueta').change(function(){
    readLocalizacoes("#readLocalizacoes", "gerar-etiqueta");
});

$('#casoDoc').change(function(){
    next_cod_doc();
});

$('#localizacaoGerarLocalicazao').change(function(){
    if($('#localizacaoGerarLocalicazao option:selected').attr("id") == 0){
        $("#prateleiraGerarLocalizacao").attr("disabled", false);
    }else{
        $("#prateleiraGerarLocalizacao").attr("disabled", true);
    }
});

$('#selectClienteGerarLocalizacao').change(function(){
    casosGerarLocalizacao($(this).val());
    // readLocalizacoes("#localizacaoGerarLocalicazao", "gerar-localizacao");
});

$(document).ready(function(){
    var infoURL = explodeURL();

    switch(infoURL['pg']){
        case 'cadastrar-documento':
            next_cod_doc(infoURL['cs_id']);
        break;
        case 'editar-localizacao':
            readLocalizacoes("#readLocalizacoes", "editar-localizacao");
        break;
        case 'gerar-etiqueta':
            readLocalizacoes("#readLocalizacoes", "gerar-etiqueta");
        break;
        case 'gerar-localizacao':
            readLocalizacoes("#localizacaoGerarLocalicazao", "gerar-localizacao");
        break;
        case 'minhas-solicitacoes':
            var id_usuario = $("#id_usuario_logado").val();
            $("#carregaMinhasSolicitacoes").load("readMinhasSolicitacoes.php?id_usuario="+id_usuario);
        break;
        case 'buscar':
        break;
    }

    listaCaso($('#clienteDoc').val(), "#casoDoc");

    // setInterval(function() {
    //     $("#carregaSolicitacoes").load("readSolicitacoes.php");
    // }, 500);
    $("#carregaSolicitacoes").load("readSolicitacoes.php");
});

</script>
