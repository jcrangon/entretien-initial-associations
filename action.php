<?php
/* accueil.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Liste";

$log->info("Retour dans action.php", "");

if(!userISConnecte($fingerprint)){
	closeCnx($pdo);
	$log->info("destruction de l'objet PDO", $pdo);
	$log->stop();
	$log->kill();
	header("location:./connexion.php");
	exit();
}





/**********************/
closeCnx($pdo);
$log->info("destruction de l'objet PDO", $pdo);
$log->stop();
$log->kill();
?>





