<?php
/* Inclusão da classe mPDF */
include('../class/mpdf/mpdf.php');
require_once("../bd/conexao.php");

if(isset($_POST['acao'])){
	$marcar = filter_input(INPUT_POST, 'marcar');
	$sql = $_POST['sql'];
	if($marcar == 'sim'){
		try {
			$conn->query($sql);
		} catch (Exception $erro) {
			echo $erro;
		}
	}
}

// Extrai os dados do HTML gerado pelo programa PHP
$filename =  "code.html";
$html = file_get_contents($filename);

	$mpdf = new mPDF('','A4',11,'DejaVuSansCondensed', '11', '10', '15', '10'); // Página, fonte;


	/*
	 * A conversão de caracteres foi necessária aqui, mas pode não ser no seu servidor.
	 * Certifique-se disso nas configurações globais do PHP.
	 * Usar codificação errada resulta em travamento.
	 */
	$mpdf->allow_charset_conversion = true; //Ativa a conversão de caracteres;
	$mpdf->charset_in = 'utf-8'; //Codificação do arquivo '$filename';
	$mpdf->setColumns(2,'J', 14);


	/* Propriedades do documento PDF */
	$mpdf->SetAuthor('Leonardo Oliveira'); // Autor
	$mpdf->SetSubject("Etiqueta"); //Assunto
	$mpdf->SetTitle('Etiquetas'); //Titulo
	$mpdf->SetCreator('Leonardo Oliveira'); //Criador

	/* A proteção para o PDF é opcional */
	$mpdf->SetProtection(array('copy','print'), '', 'd354#589574'); // Permite apenas copiar e imprimir

	/* Geração do PDF */
	$mpdf->WriteHTML($html,0); // Carrega o conteudo do HTML criado;
	$mpdf->Output(); // Cria PDF usando 'D' para forçar o download;
	unlink($filename); // Apaga o HTML
	ob_clean(); // Descarta o buffer;
	exit();
?>