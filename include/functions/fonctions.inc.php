<?php
function cleanIncomingData($post){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	if($log){$logger->info("** Dans function cleanIncomingData()","");}
	$tab=array();
	foreach($post as $key=>$value){
		$tab[$key]=trim(stripslashes(strip_tags($value, ENT_QUOTES)));
	}
	return $tab;
}

function debug($var){
	echo "<div style='background:#".rand(111111, 999999).";color:white;padding:5px;'>";
	$trace=debug_backtrace();// retourne  un array contenant des infos surla ligne executée.
	$info=array_shift($trace); // extrait la premiere valeur d'un array
	
	echo "Le debug a été demandé dans le fichier ".$info["file"]." à la ligne ".$info["line"]."<hr/>";
	echo "<pre>";
	print_r($var);
	echo "</pre>";
	echo "</div>";
}

function Bcrypt($pwd){
    $options = [
		'cost' => 12,
		];
	$mot_de_passe=password_hash($pwd, PASSWORD_BCRYPT, $options);
    return $mot_de_passe;
}

function Dcrypt($mdpclair,$mdpstocke){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	if($log){$logger->info("** Dans function checkpass()","",__FILE__,__LINE__);}
	if (password_verify($mdpclair, $mdpstocke)) {
		if($log){$logger->info("-- Valeur de retour : TRUE","",__FILE__,__LINE__);}
		return true;
	}
	else {
		if($log){$logger->info("-- Valeur de retour : FALSE","",__FILE__,__LINE__);}
		return false;
	}
}

function getMailExtension($email){
	$result=substr(substr($email,strpos($email,"@")),1,strpos(substr($email,strpos($email,"@")),".")-1);
	return $result;
}

function isForbiddenEmail($email){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	if($log){$logger->info("** Dans function isForbiddenEmail()","");}
	$forbiddenExt=array(
		"yopmail",
		"mailinator",
		"mail"
		);
	$ext=getMailExtension($email);
	if($log){$logger->info("\$ext",$ext);}
	if($log){$logger->info("\$ext",in_array($ext,$forbiddenExt));}
	if(in_array($ext,$forbiddenExt)){
		return true;
	}
	else{
		return false;
	}
}

function setperiod($periode){
	switch($periode){
		case 1:
			 $datereq="BETWEEN  DATE_SUB(CURDATE(),INTERVAL 1 MONTH) AND CURDATE()";
		break;
		
		case 2:
			 $datereq="BETWEEN DATE_SUB(CURDATE(),INTERVAL 3 MONTH) AND CURDATE()";
		break;
		
		case 3:
			 $datereq="BETWEEN DATE_SUB(CURDATE(),INTERVAL 12 MONTH) AND CURDATE()";
		break;
		default:
			 $datereq="BETWEEN  DATE_SUB(CURDATE(),INTERVAL 1 MONTH) AND CURDATE()";
	}
	return $datereq;
}

function frenchDateTime($mysqldate,$option){
	if($option==1){
		setlocale(LC_TIME, 'fr_FR.utf8','fra');
		$b=utf8_encode(strftime("%a %d %b %H:%M:%S",strtotime($mysqldate)));
		return $b;
	}
	
	if($option==2){
		setlocale(LC_TIME, 'fr_FR.utf8','fra');
		$b=utf8_encode(strftime("%a %d %b",strtotime($mysqldate)));
		return $b;
	}
}

function mysqltofrenchdate($mysqldate){
	return date("d-m-Y",strtotime($mysqldate));
}

function frenchtomysqldate($frenchdate){
	return date("Y-m-d",strtotime($mysqldate));
}

function mysqlnow($option){
	if (($option==1)) {
		$msqldate=date("Y-m-d");
	}

	if (($option==2)) {
		$msqldate=date("Y-m-d H:i:s");
	}

	return $msqldate;
}

function userISConnecte($fingerprint){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	if($log){$logger->info("** Dans function userISConnecte()","");}
	
	if(isset($_SESSION[$fingerprint]["user"]) && !empty($_SESSION[$fingerprint]["user"]) && sizeof($_SESSION[$fingerprint]["user"])==2){
		if($log){$logger->info("$ SESSION[fingerprint][user] existe",$_SESSION[$fingerprint]["user"]);}
		return true;
	}
	else{
		if(isset($_SESSION[$fingerprint]["user"])){
			unset($_SESSION[$fingerprint]["user"]);
			if($log){$logger->info("$ SESSION[fingerprint][user] n'existe pas!!","");}
		}
		return false;
	}
}

function userISAdmin(){
	if($_SESSION["user"]["role"]=="1"){
		return true;
	}
	else{
		return false;
	}
}

// Fonction pour ajouter un produit au panier
function ajouterProduit($id_produit, $quantite, $photo, $titre, $prix, $categorie){
	if(!isset($_SESSION['panier'])){
		$_SESSION['panier'] = array();
		// $_SESSION['panier']['id_produit'] = array();
		// $_SESSION['panier']['titre'] = array();
		// $_SESSION['panier']['photo'] = array();
		// $_SESSION['panier']['prix'] = array();
		// $_SESSION['panier']['quantite'] = array();
	}
	else{
		$position = array_search($id_produit, $_SESSION['panier']['id_produit']);
		// Si le produit existe déjà dans le panier, $position va contenir un chiffre (0, 1, 2...), ou alors false si le produit n'est pas déjà dans le panier. 
	}
	
	if(isset($position) && $position !==  false){
		// Si le produit existe dans le panier, on va dans le tableau qui stocke les quantité pour lui ajouter la nouvelle quantité
		$_SESSION['panier']['quantite'][$position] += $quantite;
	}
	else{
		// Le produit n'était pas dans le panier
		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['categorie'][] = $categorie;
		$_SESSION['panier']['id_produit'][] = $id_produit;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['photo'][] = $photo;
		$_SESSION['panier']['prix'][] = $prix;
	}
}

function totalArtPanier(){
	$i=0;
	if(isset($_SESSION['panier']) && !empty($_SESSION['panier'])){
		foreach($_SESSION['panier']['quantite'] as $qte)
		$i+=$qte;
	}
	return $i;
}

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

?>