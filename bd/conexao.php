<?php
require("config.php");

try {

	$conn = new PDO('mysql:host='.HOST.';dbname='.DB.';charset=utf8', USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
	echo "ERROR: ".$e->getMessage();
}
include("functions.php");
?>