<?php

$marcarImpressa = '';

$where = ' WHERE impressa = 0';


$cliente = $_POST['cliente'];
$tipo = $_POST['tipo'];

if($cliente != '0' && $tipo == '0'){
	$where = ' WHERE impressa = 0 AND localizacao_cliente_id_cliente = '.$cliente;
}else if($cliente == '0' && $tipo != '0'){
	$where = ' WHERE impressa = 0 AND tipo_etiqueta = \''.$tipo.'\'';
}else if($cliente != '0' && $tipo != '0'){
	$where = ' WHERE impressa = 0 AND localizacao_cliente_id_cliente = \''.$cliente.'\' AND tipo_etiqueta = \''.$tipo.'\'';
}


try {

	$sql = 'SELECT * FROM etiqueta'.$where.' ORDER BY id_etiqueta';

	$result = $conn->query($sql);
	$qnt = $result->rowCount();
	$row = $result->fetchAll();
	$html = '';
	$cont = 3;

	for ($i=0; $i < $qnt; $i++) {
		if($i == $cont){
			$cont += 4;
			$html .= $row[$i][1].'<columnbreak />';
			// $marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
		}else{
			$html .= $row[$i][1];
			// $marcarImpressa .= "UPDATE etiqueta SET impressa = 1 WHERE id_etiqueta = ".$row[$i][0].";";
		}
	}

	echo $html;

} catch (Exception $e) {
	echo 'Erro ao carregar as tabelas!<div>Detalhes: '.$e->getMessage();
}

?>