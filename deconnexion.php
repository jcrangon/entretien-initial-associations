<?php
/* connexion.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Déconnexion";

$log->info("Retour dans deconnexion.php", "");

$log->info("destruction de $ SESSION [fingerprint] [user]", "");
if(isset($_SESSION[$fingerprint]["user"])){
	unset($_SESSION[$fingerprint]["user"]);
}
$log->info("destruction de $ SESSION [fingerprint] [nouvelle_fiche]", "");
if(isset($_SESSION[$fingerprint]["nouvelle_fiche"])){
	unset($_SESSION[$fingerprint]["nouvelle_fiche"]);
}
$log->info("$ SESSION", $_SESSION);
$log->info("redirection vers accueil.php", "");

closeCnx($pdo);
$log->info("destruction de l'objet PDO", $pdo);
$log->stop();
$log->kill();


header("location:./accueil.php");
exit();
?>