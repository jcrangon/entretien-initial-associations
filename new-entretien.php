<?php
/* accueil.php */
require_once('include/init/init.inc.php');
$log->info("dans new-entretien.php", "");
//**************  MAIN CODE
$log->info("destruction de $ SESSION [$ fingerprint][nouvelle_fiche] ", "");
if(isset($_SESSION[$fingerprint]["nouvelle_fiche"])){
	unset($_SESSION[$fingerprint]["nouvelle_fiche"]);
}

$_SESSION[$fingerprint]["nouvelle_fiche"]=array();

$log->info("redirection vers contact.php", "");
/**********************/
closeCnx($pdo);
$log->info("destruction de l'objet PDO", $pdo);
$log->stop();
$log->kill();
//*************  AFFICHAGE du HTML
header("location:./contact.php");
exit();

?>