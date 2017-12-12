<div id="carregaSolicitacoes">
<!-- Vem do banco - arquivo readSolicitacoes.php -->
</div>

<!-- Os dados do modal vêm a partir da função carregaModal localizada em js/scripts.php:39 -->
<div class="modal fade" id="verSolicitacao" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div id="printInfo">
      <style type="text/css">
.table {
width: 100%;
max-width: 100%;
margin-bottom: 1rem; }
.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #eceeef; }
.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #eceeef; }
.table tbody + tbody {
  border-top: 2px solid #eceeef; }
.table .table {
  background-color: #fff; }

.table-sm th,
.table-sm td {
padding: 0.3rem; }

.table-bordered {
border: 1px solid #eceeef; }
.table-bordered th,
.table-bordered td {
  border: 1px solid #eceeef; }
.table-bordered thead th,
.table-bordered thead td {
  border-bottom-width: 2px; }

.table-striped tbody tr:nth-of-type(odd) {
background-color: rgba(0, 0, 0, 0.05); }

.table-hover tbody tr:hover {
background-color: rgba(0, 0, 0, 0.075); }

.table-active,
.table-active > th,
.table-active > td {
background-color: rgba(0, 0, 0, 0.075); }

.table-hover .table-active:hover {
background-color: rgba(0, 0, 0, 0.075); }
.table-hover .table-active:hover > td,
.table-hover .table-active:hover > th {
  background-color: rgba(0, 0, 0, 0.075); }

.table-success,
.table-success > th,
.table-success > td {
background-color: #dff0d8; }

.table-hover .table-success:hover {
background-color: #d0e9c6; }
.table-hover .table-success:hover > td,
.table-hover .table-success:hover > th {
  background-color: #d0e9c6; }

.table-info,
.table-info > th,
.table-info > td {
background-color: #d9edf7; }

.table-hover .table-info:hover {
background-color: #c4e3f3; }
.table-hover .table-info:hover > td,
.table-hover .table-info:hover > th {
  background-color: #c4e3f3; }

.table-warning,
.table-warning > th,
.table-warning > td {
background-color: #fcf8e3; }

.table-hover .table-warning:hover {
background-color: #faf2cc; }
.table-hover .table-warning:hover > td,
.table-hover .table-warning:hover > th {
  background-color: #faf2cc; }

.table-danger,
.table-danger > th,
.table-danger > td {
background-color: #f2dede; }

.table-hover .table-danger:hover {
background-color: #ebcccc; }
.table-hover .table-danger:hover > td,
.table-hover .table-danger:hover > th {
  background-color: #ebcccc; }

.thead-inverse th {
color: #fff;
background-color: #292b2c; }

.thead-default th {
color: #464a4c;
background-color: #eceeef; }

.table-inverse {
color: #fff;
background-color: #292b2c; }
.table-inverse th,
.table-inverse td,
.table-inverse thead th {
  border-color: #fff; }
.table-inverse.table-bordered {
  border: 0; }

.table-responsive {
display: block;
width: 100%;
overflow-x: auto;
-ms-overflow-style: -ms-autohiding-scrollbar; }
.table-responsive.table-bordered {
  border: 0; }
.text-center {
  text-align: center !important; }
      </style>
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" id="titleVerSolicitacao"></h5>
      </div>
      <div class="text-center">Informações dos documentos solicitados</div>
        <table class="table table-bordered table-striped">
          <div class="modal-body py-0">
            <thead>
              <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Localização</th>
              </tr>
            </thead>
            <tbody id="bodyVerSolicitacao">
            </tbody>
          </div>
        </table>
      </div>
      <div class="modal-footer hidden-print">
        <div class="w-100" id="btnAcaoSolicitacao"></div>
      	<button onclick="imprimirModal();" type="button" class="w-100 btn btn-sm btn-warning">Imprimir</button>
        <button type="button" class="w-50 btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>