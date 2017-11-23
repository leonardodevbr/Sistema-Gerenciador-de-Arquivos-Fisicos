<div id="cliente">
<label for="cliente">Selecione um cliente</label>
<select name="cliente" class="form-control">
    <?php
    require_once("../bd/conexao.php");
        if($_GET['m'] == '1'){
            $tmp = '';
            try {
                $sql = "SELECT * FROM cliente c INNER JOIN documento d ON c.id_cliente = d.caso_cliente_id_cliente WHERE d.cod_documento IS NULL";

                $clientes = $conn->query($sql);
                $qnt = $clientes->rowCount();

                if($qnt > 0){

                    foreach ($clientes as $cRow) {
                        $id_cliente = $cRow['id_cliente'];
                        $nome_cliente = $cRow['nome_cliente'];
                        $cod_cliente = $cRow['cod_cliente']." - ";
                        $itemSelecionado = filter_input(INPUT_POST, 'cliente');

                        if($nome_cliente != $tmp){
                            $nome = $nome_cliente;
                            echo '<option value="'.$id_cliente.'">'.$cod_cliente.$nome.'</option>';
                        }

                        $tmp = $nome_cliente;

                    }
                }else{
                    echo '<option>Nenhum cliente cadastrado</option>';
                }

            } catch (Exception $e1) {
                echo $e1->getMessage();
            }
        }else{
            try {
                $sql = "SELECT * FROM cliente ORDER BY cod_cliente ASC";

                $clientes = $conn->query($sql);
                $qnt = $clientes->rowCount();

                if($qnt > 0){

                    foreach ($clientes as $cRow) {
                        $id_cliente = $cRow['id_cliente'];
                        $nome_cliente = $cRow['nome_cliente'];
                        $cod_cliente = $cRow['cod_cliente']." - ";
                        $itemSelecionado = filter_input(INPUT_POST, 'cliente');
                        if($itemSelecionado == $id_cliente){
                            echo '<option selected value="'.$id_cliente.'">'.$cod_cliente.$nome_cliente.'</option>';
                        }else{
                            echo '<option value="'.$id_cliente.'">'.$cod_cliente.$nome_cliente.'</option>';
                        }
                    }
                }else{
                    echo '<option>Nenhum cliente cadastrado</option>';
                }

            } catch (Exception $e1) {
                echo $e1->getMessage();
            }
        }
    ?>
</select>
<br>
</div>

