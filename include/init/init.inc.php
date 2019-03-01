<?php
//*********** boutique/inc/init.inc.php  ************

//--SESSION
session_start();

//--CHEMIN
/**********************/
define("RACINE_SITE","");
define("RACINE_SERVEUR",$_SERVER["DOCUMENT_ROOT"]);


//--INCLUDES
include("./class/autoloader/autoloader.php");
include("./include/init/dbdata.inc.php");
include("./lib/pdo-lib.php");
include("./pagetransitions/page-transitions.php");
require_once("./include/functions/fonctions.inc.php");

//--LOGGER
/**********************/
$CONFIG["log"]["innerlog_activate"]=1;
$CONFIG["log"]["mainlog_activate"]=1;
$CONFIG["log"]["php_error_log"]=1;
$log=new phplogger("./class",1,$CONFIG["log"]["innerlog_activate"]);
$log->createglobalref();
$log->activate();
if($CONFIG["log"]["mainlog_activate"]==0){$log->quietmode();}
if($CONFIG["log"]["php_error_log"]==0){$log->php_quietmode();}
$log->start();
$log->info("Dans include/init.inc.php","");


//--ERRCODES
/**********************/
$errCodes=array(
	"getPDO_returns_False"   => "Error FE001",
	"getPDO_returns_Fatal"   => "Error FE002",
	"Multiple_pseudos"       => "Error FE003",
	"resultat_execute_fails" => "Error FE004",
	);


//--LOG CURRENT FILE
/**********************/
$tab_url=explode("/",$_SERVER['REQUEST_URI']);
$fichier_actuel=$tab_url[sizeof($tab_url)-1];
$log->info("Appel de ***************************** ".$fichier_actuel,"");


//--CONNEXION_BDD
/**********************/
if(!$pdo=getPDO($dbdata)){
	$log->info("Retour dans include/init/init.inc.php","");
	$log->error("Fatal Error dans fonction: getPDO()","");
	$log->stop();
	$log->kill();
	DIE($errCodes["getPDO_returns_Fatal"]);
}
if(is_numeric($pdo) && $pdo<0){
	$log->info("Retour dans include/init/init.inc.php","");
	$log->error("Erreur de connexion dans fonction: getPDO()","");
	$log->stop();
	$log->kill();
	DIE($errCodes["getPDO_returns_False"]);
}
$log->info("Retour dans include/init/init.inc.php","");
$log->info("Objet PDO créé avec succes, \$pdo",$pdo);

//--VARIABLES
/**********************/
$error=array();
$success=array();
$page="";
$notice="";
$fingerprint="F5s5f3oiurgk23131f5irj632vr65sSDrsg";


$GET_Data_Avail=false;
$POST_Data_Avail=false;

if(isset($_SESSION["user"])){
	$log->info("\$_SESSION['user']",$_SESSION["user"]);
}

$log->info("vérification des données entrantes \$_GET & \$_POST","");
if(isset($_GET) && !empty($_GET)){
	$_GET=cleanIncomingData($_GET);
	$GET_Data_Avail=true;
	$log->info("Nettoyage des données entrantes \$_GET",$_GET);
}
else{
	$log->info("pas de données entrantes \$_GET",$_GET);
}

if(isset($_POST) && !empty($_POST)){
	$_POST=cleanIncomingData($_POST);
	$POST_Data_Avail=true;
	$log->info("Nettoyage des données entrantes \$_POST",$_POST);
}
else{
	$log->info("pas de données entrantes \$_POST",$_POST);
}
?>