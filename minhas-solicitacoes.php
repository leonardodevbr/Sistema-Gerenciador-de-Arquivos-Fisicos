<div class="mt-3" id="recebeInformacoes"><div id="alertMsg"></div></div>
<div id="carregaMinhasSolicitacoes">
	<!-- Dados carregados a cada 1 segundo, atraves do arquivo "readMinhasSolicitacoes.php" -->
</div>

<!-- MODAL PARA EXIBIR INFORMAÇÕES DA SOLICITAÇÃO -->
<div class="modal fade" id="infoSolicitacoesModal" tabindex="-1" role="dialog" aria-labelledby="infoSolicitacoes" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoSolicitacoesModal">Solicitação</h5>
        <button type="button" class="close hidden-print" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
      	<table id="tableInfoSolicitacoes" class="table table-bordered table-striped">
      	</table>
      </div>
      <div class="modal-footer hidden-print">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA CANCELAR A SOLICITAÇÃO -->
<div class="modal fade" id="cancelaSolicitacaoModal" tabindex="-1" role="dialog" aria-labelledby="cancelaSolicitacao" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelaSolicitacaoModal">Deseja cancelar esta solicitação?</h5>
        <button type="button" class="close hidden-print" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
		<input type="hidden" id="id_solicitacao_cancelar">
      	<button type="button" class="btn btn-primary mx-3 px-5" data-dismiss="modal">Não</button>
        <button type="button" class="btn btn-secondary mx-3 px-5" onclick="cancelaSolicitacao();" data-dismiss="modal">Sim</button>
      </div>
    </div>
  </div>
</div>