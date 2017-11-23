<?php
require("config.php");
//As linhas comentadas abaixo são para definir as constantes de acordo com sua configuração. 
//É preciso criar o arquivo "config.php" na pasta "bd" e adicionar essas linhas com suas configurações.
//define("HOST", "localhost");
//define("USER", "username");
//define("PASS", "password!");
//define("DB", "arquivo");

try {

	$conn = new PDO('mysql:host='.HOST.';dbname='.DB.';charset=utf8', USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
	echo "ERROR: ".$e->getMessage();
}
include("functions.php");
?>