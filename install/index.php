<?php
session_start();
ini_set('display_errors', 'Off');
		ini_set('log_errors', "On");
		ini_set('error_log', './errlog.txt');
		error_reporting(E_ALL);
//--INCLUDES
include("../class/autoloader/autoloader.php");
include("../include/init/dbdata.inc.php");
include("../lib/pdo-lib.php");
require_once("../include/functions/fonctions.inc.php");

//--ERRCODES
/**********************/
$errCodes=array(
	"getPDO_returns_False"   => "Error FE001",
	"getPDO_returns_Fatal"   => "Error FE002",
	"Multiple_pseudos"       => "Error FE003",
	"resultat_execute_fails" => "Error FE004",
	);
	
//--CONNEXION_BDD
/**********************/
if(!$pdo=getPDO($dbdata)){
	DIE($errCodes["getPDO_returns_Fatal"]);
}
if(is_numeric($pdo) && $pdo<0){
	DIE($errCodes["getPDO_returns_False"]);
}

$notice="";

if(isset($_POST) && !empty($_POST)){
	if(empty($_POST["pseudo"])){
		$notice.="<span style='color:red;'>Veuillez renseigner le champs 'pseudo'</span><br>";
	}
	else{
		$verif_pseudo = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]{3,20}+$/",$_POST["pseudo"]);
		if(!$verif_pseudo){
			$notice.="<span stymle='color:red;>Le champs 'nom' doit contenir 3 caratères au minimum, 20 caractères au maximum sans espaces no caractères spéciaux'</span><br>";
		}
	}
	
	if(empty($_POST["mdp"])){
		$notice.="<span style='color:red;'>Veuillez renseigner le champs 'mot de passe'</span><br>";
	}
	else{
		if(strlen($_POST["mdp"])<8){
			$notice.="<span style='color:red;'>Le champs 'mot de passe' doit contenir au moins 8 caractères</span><br>";
		}
	}
	
	if(empty($notice)){
		extract($_POST);
		$mdp=Bcrypt($mdp.$pseudo);
		$table="user";
		$req="INSERT INTO ".$table." (pseudo,pwd) VALUES(:pseudo, :mdp)";
		$resultat=$pdo->prepare($req);
		$resultat->bindParam(":pseudo",$_POST["pseudo"],PDO::PARAM_STR);
		$resultat->bindParam(":mdp",$mdp,PDO::PARAM_STR);
		try{
			$resultat->execute();
		}
		catch(PDOException $e){
			die($errCodes["resultat_execute_fails"]."->".$e->getMessage());
		}
		if($resultat){
			$notice.="<span style='color:green;'>admin correctement enregistré</span><br>";
		}
		else{
			$notice.="<span style='color:red;'>une erreur est survenue et je sais pas laquelle!!! :D</span><br>";
		}
	}
}




/**********************/
closeCnx($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
	<?=$notice;?>
	<form action="./index.php" method="post">
		<input type="text" placeholder="identifiant" name="pseudo" required>
		<input type="password" placeholder="mot de passe" name="mdp" required>
		<input type="submit" value="envoyer">
	</form>
	
</html>
