<?php
require_once("../bd/conexao.php");
    $cliente = $_POST['id'];
    try {
        $sql = "SELECT * FROM caso WHERE cliente_id_cliente = {$cliente} ORDER BY num_caso";

        $casos = $conn->query($sql);
        $qnt = $casos->rowCount();

        if($qnt > 0){
                 foreach ($casos as $cRow) {
                    $id_caso = $cRow['id_caso'];
                    $descricao_caso = $cRow['descricao_caso'];
                    $num_caso = $cRow['num_caso'];

                    if($_POST['cs_id'] != 0){
                        if($_POST['cs_id'] == $id_caso){
                            echo '<option class="casoDoc" selected value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
                        }else{
                            echo '<option class="casoDoc" value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
                        }
                    }else{
                        echo '<option class="casoDoc" value="'.$id_caso.'">'.$num_caso.' - '.$descricao_caso.'</option>';
                    }

                }
        }else{
            echo '<option>Nenhum caso vinculado a este cliente</option>';
        }

    } catch (Exception $e1) {
        echo $e1->getMessage();
    }
?>
