<h5 class="modal-title" id="VerSolicitacao">Solicitação '.$id_solicitacao.' - '.$nome_usuario.'</h5>';
<div class="table-responsive">
  	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Código</th>
				<th>Descrição</th>
				<th>Localização</th>
				<th>Observação</th>
			</tr>
		</thead>
		<tbody>
			'.$table_item_solicitado.'
		</tbody>
  	</table>
  </div>';

<div class="modal fade" id="verSolicitacao-'.$id_solicitacao.'" tabindex="-1" role="dialog" aria-labelledby="VerSolicitacao" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="VerSolicitacao">Solicitação '.$id_solicitacao.' - '.$nome_usuario.'</h5>
        <button type="button" class="close hidden-print" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive d-block">
      	<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Código</th>
					<th>Descrição</th>
					<th>Localização</th>
					<th>Observação</th>
				</tr>
			</thead>
			<tbody>
				'.$table_item_solicitado.'
			</tbody>
      	</table>
      </div>
      <div class="modal-footer hidden-print">
      	'.$btn_acao.'
        <button onclick="imprimirModal();" type="button" class="w-100 btn btn-sm btn-warning">Imprimir</button>
        <button type="button" class="w-50 btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>