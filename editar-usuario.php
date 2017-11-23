<br>
<div class="table-responsive">
<table class="table table-striped table-bordered table-sm">
	<?php
	require_once("bd/conexao.php");

		try {
			$usuarios = $conn->query("SELECT * FROM usuario ORDER BY nome_usuario ASC");
			$qnt = $usuarios->rowCount() - 1;

			echo '
			<thead>';
			if($qnt > 0){
				echo '
					<tr>
						<td colspan="5" class="text-center">Número de usuários escontrados: '.$qnt.'</td>
					</tr>
				';
			}
			echo '
				<tr>
					<th class="text-center">Nome do Usuário</th>
					<th class="text-center">Tipo</th>
					<th class="text-center">Status</th>
					<th colspan="2"  class="text-center">Ação</th>
				</tr>
			</thead>
			<tbody>
			';

			if($qnt > 0){
				foreach ($usuarios as $row) {
					$nome = $row['nome_usuario'];
					$tipo_user = $row['tipo_usuario'];
					$status_user = $row['status_usuario'];
					$id_user = $row['id_usuario'];
					switch ($status_user) {
						case '2':
							$status = 'Desabilitado';
						break;
						case '1':
							$status = 'Ativo';
						break;
						case '0':
							$status = 'Inativo';
						break;
					}
					switch ($tipo_user) {
						case '1':
							$tipo = 'Tecnologia';
						break;
						case '2':
							$tipo = 'Secretaria';
						break;
						case '3':
							$tipo = 'Advogado';
						break;
						case '4':
							$tipo = 'Administrativo';
						break;
					}
					if($id_usuario != $id_user){
						echo '
							<tr>
								<td>'.$nome.'</td>
								<td>'.$tipo.'</td>
								<td>'.$status.'</td>
								<td style="width:5%" class="text-center">
									<a style="width:100%" class="btn btn-info btn-sm" href="?pg=form-editar-usuario&id='.$id_user.'">Editar</a>
								</td>
								'.(($status_user == 0 || $status_user == 2) ? '
								<td style="width:5%" class="text-center">
									<a style="width:100%" class="btn btn-warning btn-sm" href="?pg=editar-usuario&status=1&id='.$id_user.'">Ativar</a>
									</td>' : '
								<td style="width:5%" class="text-center">
									<a style="width:100%" class="btn btn-danger btn-sm" href="?pg=editar-usuario&status=0&id='.$id_user.'">Desativar</a>
								</td>').'

							</tr>
						';
					}
				}
			}else{
				echo '
					<tr>
						<td colspan="4">Nenhum usuario cadastrado</td>
					</tr>
				';
			}

		} catch (Exception $e) {
			echo 'Error: '.$e->getMessage();
		}
		if(isset($_GET['status'])){
			$id = $_GET['id'];
			$status = $_GET['status'];
			if($tipo_usuario == 1){
				$upd = $conn->query("UPDATE usuario SET status_usuario = {$status} WHERE id_usuario = $id");
				if($upd){
					if($status == '1'){
						$msg = "Usuário ativado com sucesso!";
					}else{
						$msg = "Usuário desativado com sucesso!";
					}
					echo mensagem($msg, "info", "editar-usuario", "1000");
				}
			}
		}
	?>
	</tbody>
</table>
</div>