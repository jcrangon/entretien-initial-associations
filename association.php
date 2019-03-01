<?php
/* contact.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Association";

$log->info("Retour dans contact.php", "");

if(!userISConnecte($fingerprint)){
	header("location:./connexion.php");
	exit();
}


//-----------------------Traitement des données formulaire
$modify=false;


if($GET_Data_Avail){ // appel de la page en modification
	$log->info("$ GET",$_GET);
	
	$table="associations";
	
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$req="SELECT * FROM ".$table." WHERE id_assos=:id";
		$resultat=$pdo->prepare($req);
		$resultat->bindParam(":id",$_GET["id"],PDO::PARAM_STR);
		try{
			$resultat->execute();
		}
		catch(PDOException $e){
			$log->info("Erreur PDO", $e->getMessage());
			DIE($errCodes["resultat_execute_fails"]);
		}
		if($resultat->rowCount()!==0){
			$assos=$resultat->fetch();
			$log->info("association à ", $assos);
			$modify=true;
		}
		else{
			$log->error("La requete a retourné 0 lignes","");
		}
	}
}

if(!$modify){
	if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"])){
		header("location:./accueil.php");
	}
}


if($POST_Data_Avail){ // appel de la page en insertion
	// verification des données formulaire
	$log->info("Verification des données POST","");
	$log->info("$ POST",$_POST);
	if(!empty($_FILES)){
		$log->info("$ FILE",$_FILES);
	}
	
	// verification du nom
	$log->info("Verification du nom","");
	if(empty($_POST["nom_assos"])){
		$error["nom_assos"]="<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner le champs 'Nom'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		$log->warning("error[nom_assos]",$error["nom_assos"]);
	}
	else{
		$verif_nom = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._\-'\s]{3,100}+$/",$_POST["nom_assos"]);
		if(!$verif_nom){
			$error["nom_assos"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'nom' doit contenir 3 caratères au minimum, 20 caractères au maximum sans caractères spéciaux'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			$log->warning("error[nom_assos]",$error["nom_assos"]);
		}
	}
	
	
	// verification de l'adresse CHAMP NON OBLIGATOIRE
	$log->info("Verification de l'adresse","");
	if(!empty($_POST["adresse"])){
		$verif_adresse = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._\-'\s]{3,150}+$/",$_POST["adresse"]);
		if(!$verif_adresse){
			$error["adresse"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'adresse' doit contenir 3 caratères au minimum, 20 caractères au maximum sans caractères spéciaux'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			$log->warning("error[adresse]",$error["adresse"]);
		}
	}
	
	
	// verification du numero du code postal ( CHAMPS NON REQUIS)
	if(!empty($_POST["zip"])){
		$log->info("Verification du tel fixe","");
		$verif_zip = preg_match("/[0-9]{5}/",$_POST["zip"]);
		if(!$verif_zip){
			$log->warning("error[zip]",$error["zip"]);
			$error["zip"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'Cde.Postal' doit contenir 5 chiffres sans espaces<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		}
	}
	
	
	// verification de la ville  CHAMP NON OBLIGATOIRE
	$log->info("Verification de la ville","");
	if(!empty($_POST["ville"])){
		$verif_ville = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._\-'\s]{3,100}+$/",$_POST["ville"]);
		if(!$verif_ville){
			$error["ville"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'adresse' doit contenir 3 caratères au minimum, 20 caractères au maximum sans caractères spéciaux'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			$log->warning("error[ville]",$error["ville"]);
		}
	}
	
	
	// verification du numero de tel mobile ( CHAMPSREQUIS)
	$log->info("Verification du tel mobile","");
	if(empty($_POST["telmobile"])){
		$log->warning("error[telmobile]",$error["telmobile"]);
		$error["telmobile"]="<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner le champs 'Tel.Portable'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
	}
	else{
		$verif_telmobile = preg_match("/[0-9]{10}/",$_POST["telmobile"]);
		if(!$verif_telmobile){
			$log->warning("error[telmobile]",$error["telmobile"]);
			$error["telmobile"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'Tel.Portable' doit contenir 10 chiffres sans espaces<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		}
	}
	
	
	// verification du numero de tel fixe ( CHAMPS NON REQUIS)
	if(!empty($_POST["telfixe"])){
		$log->info("Verification du tel fixe","");
		$verif_telfixe = preg_match("/[0-9]{10}/",$_POST["telfixe"]);
		if(!$verif_telfixe){
			$log->warning("error[telfixe]",$error["telfixe"]);
			$error["telfixe"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'Tel.Fixe' doit contenir 10 chiffres sans espaces<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		}
	}
	
	
	// verification de l'email
    $log->info("Verification de l'email","");
    if(empty($_POST['email'])){
        $error["email"]= "<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner un email<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
        $log->warning("error[email]",$error["email"]);
    }
    else{
        $verif_email =filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
        
        if(!$verif_email){
        	$log->warning("error[email]",$error["email"]);
            $error["email"]= "<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner un email valide<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
        }
        elseif($verif_email){
        	$verif2_email = preg_match("/[A-Za-z0-9._%+-]{1,}@[a-zA-Z]{1,}([.]{1}[a-zA-Z]{2,}|[.]{1}[a-zA-Z]{2,}[.]{1}[a-zA-Z]{2,})/",$_POST["email"]);
        	if(!$verif2_email){
        		$error["email"]= "<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner un email valide<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
        	}
        }
        elseif(isForbiddenEmail($_POST['email'])){ // isForbiddenEmail est une fonction custom
        	$log->warning("error[email]",$error["email"]);
        	$error["email"]= "<span class='alert alert-danger d-block alert-dismissible'>Cette extension d'email est interdite<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
        }
    }
	

	
	if(empty($error)){
		
		$table="associations";
		
		if(!$modify){ // requete d'insertion
		
			$log->info("Insertion en BDD","");
			
			if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"])){
				$log->info("la fiche n'existe pas encore en memoire de session:",$_SESSION[$fingerprint]);
				$req="INSERT INTO ".$table." (id_contact, nom_assos, adresse, zip, ville, telmobile, telfixe, email, note) ";
				$req.="VALUES (:id_contact, :nom_assos, :adresse,:zip,:ville,:telmobile,:telfixe,:email,:note)";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":id_contact",$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"],PDO::PARAM_INT);
				$resultat->bindParam(":nom_assos",$_POST["nom_assos"],PDO::PARAM_STR);
				$resultat->bindParam(":adresse",$_POST["adresse"],PDO::PARAM_STR);
				$resultat->bindParam(":zip",$_POST["zip"],PDO::PARAM_STR);
				$resultat->bindParam(":ville",$_POST["ville"],PDO::PARAM_STR);
				$resultat->bindParam(":telmobile",$_POST["telmobile"],PDO::PARAM_STR);
				$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
				$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
				$resultat->bindParam(":note",$_POST["note"],PDO::PARAM_STR);
			}
			else{
				$log->info("la fiche existe en memoire de session:",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				$req="UPDATE ".$table." SET id_contact=:id_contact, nom_assos=:nom_assos, adresse=:adresse, zip=:zip, ville=:ville, telmobile=:telmobile, telfixe=:telfixe, email=:email, note=:note  WHERE id_assos=:id_assos";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":id_assos",$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"],PDO::PARAM_STR);
				$resultat->bindParam(":id_contact",$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"],PDO::PARAM_STR);
				$resultat->bindParam(":nom_assos",$_POST["nom_assos"],PDO::PARAM_STR);
				$resultat->bindParam(":adresse",$_POST["adresse"],PDO::PARAM_STR);
				$resultat->bindParam(":zip",$_POST["zip"],PDO::PARAM_STR);
				$resultat->bindParam(":ville",$_POST["ville"],PDO::PARAM_STR);
				$resultat->bindParam(":telmobile",$_POST["telmobile"],PDO::PARAM_STR);
				$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
				$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
				$resultat->bindParam(":note",$_POST["note"],PDO::PARAM_STR);
			}
			
			try{
				$resultat->execute();
			}
			catch(PDOException $e){
				$log->error("Erreur PDO",$e->getMessage());
				die($errCodes["resultat_execute_fails"]);
			}
			
			if(!$resultat){
				$log->error("Echec requete INSERT","");
				$notice.="<span class='alert alert-danger d-block m-auto alert-dismissible'>Une erreur est survenue. Essayez de recharger la page.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
			else{
				$log->info("Succès","");
				// ... autre code a executer 
				$success["success"]="<span class='alert alert-success d-block m-auto alert-dismissible'>Transaction validée avec succes! <a class='btn btn-outline-success' href='./ajout-transaction.php'>Nouvelle Transation</a> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
				
				// chargement de $_SESSION
				if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"])){
					$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"]=$pdo->lastInsertId();
				}
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["nom_assos"]=$_POST["nom_assos"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["adresse_assos"]=$_POST["adresse"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["zip_assos"]=$_POST["zip"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["ville_assos"]=$_POST["ville"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telmobile_assos"]=$_POST["telmobile"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telfixe_assos"]=$_POST["telfixe"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["email_assos"]=$_POST["email"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["note_assos"]=$_POST["note"];
				
				
				$log->info("chargement de $ SESSION",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				
				$log->info("redirection vers entretien.php","");
				header("location:./entretien.php");
				exit();
			}
		}
		else{ // requete update (modify = true)
		
			$log->info("Update en BDD","");
			
			$req="UPDATE ".$table." SET id_contact=:id_contact, nom_assos=:nom_assos, adresse=:adresse, zip=:zip, ville=:ville, telmobile=:telmobile, telfixe=:telfixe, email=:email, note=:note  WHERE id_assos=:id_assos";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":id_assos",$assos["id_assos"],PDO::PARAM_STR);
			$resultat->bindParam(":id_contact",$assos["id_contact"],PDO::PARAM_STR);
			$resultat->bindParam(":nom_assos",$_POST["nom_assos"],PDO::PARAM_STR);
			$resultat->bindParam(":adresse",$_POST["adresse"],PDO::PARAM_STR);
			$resultat->bindParam(":zip",$_POST["zip"],PDO::PARAM_STR);
			$resultat->bindParam(":ville",$_POST["ville"],PDO::PARAM_STR);
			$resultat->bindParam(":telmobile",$_POST["telmobile"],PDO::PARAM_STR);
			$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
			$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
			$resultat->bindParam(":note",$_POST["note"],PDO::PARAM_STR);
			
			try{
				$resultat->execute();
			}
			catch(PDOException $e){
				$log->error("Erreur PDO",$e->getMessage());
				die($errCodes["resultat_execute_fails"]);
			}
			
			if(!$resultat){
				$log->error("Echec requete UPDATE","");
				$notice.="<span class='alert alert-danger d-block m-auto alert-dismissible'>Une erreur est survenue. Essayez de recharger la page.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
			else{
				$log->error("Succès","");
				// ... autre code a executer 
				$success["success"]="<span class='alert alert-success d-block m-auto alert-dismissible'>Transaction validée avec succes! <a class='btn btn-outline-success' href='./accueil.php'>Retour à la liste</a> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
		}
	}
	else{ // le tableau $error n'est pas vide
		$notice="<span class='alert alert-danger d-block m-auto alert-dismissible'>Des erreurs existent dans le formulaire.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
	}
}


if(!$modify){
	if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"])){
		extract($_POST);
		$nom_assos_r=(isset($nom_assos))?$nom_assos:"";
		$adresse_r=(isset($adresse))?$adresse:"";
		$zip_r=(isset($zip))?$zip:"";
		$ville_r=(isset($ville))?$ville:"";
		$telmobile_r=(isset($telmobile))?$telmobile:"";
		$telfixe_r=(isset($telfixe))?$telfixe:"";
		$email_r=(isset($email))?$email:"";
		$note_r=(isset($note))?$note:"";
	}
	else{
		$nom_assos_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["nom_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["nom_assos"]:"ERREUR!!!";
		$adresse_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["adresse_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["adresse_assos"]:"ERREUR!!!";
		$zip_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["zip_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["zip_assos"]:"ERREUR!!!";
		$ville_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["ville_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["ville_assos"]:"ERREUR!!!";
		$telmobile_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telmobile_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telmobile_assos"]:$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telmob_contact"];
		$telfixe_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telfixe_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["telfixe_assos"]:"ERREUR!!!";
		$email_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["email_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["email_assos"]:$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["email_contact"];
		$note_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["note_assos"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["note_assos"]:"ERREUR!!!";
	}
}
else{
	$nom_assos_r=(isset($_POST["nom_assos"]))?$_POST["nom_assos"]:$assos["nom_assos"];
	$adresse_r=(isset($_POST["adresse"]))?$_POST["adresse"]:$assos["adresse"];
	$zip_r=(isset($_POST["zip"]))?$_POST["zip"]:$assos["zip"];
	$ville_r=(isset($_POST["ville"]))?$_POST["ville"]:$assos["ville"];
	$telmobile_r=(isset($_POST["telmobile"]))?$_POST["telmobile"]:$assos["telmobile"];
	$telfixe_r=(isset($_POST["telfixe"]))?$_POST["telfixe"]:$assos["telfixe"];
	$email_r=(isset($_POST["email"]))?$_POST["email"]:$assos["email"];
	$note_r=(isset($_POST["note"]))?$_POST["note"]:$assos["note"];
}


//---------------------------------------------------------

//--PAGE TRANSITIONS
/**********************/
//$jcr_page_transition=set_jcr_page_transition(4,"strict");
$jcr_page_transition=set_jcr_page_transition();
$log->info("Affichage du HTML", "");
 
/**********************/
closeCnx($pdo);
$log->info("destruction de l'objet PDO", $pdo);
$log->stop();
$log->kill();
//*************  AFFICHAGE du HTML
require_once("./include/chunks/header.inc.php");
?>

<section class="container">
	<div class="row">
    <?=(isset($notice))?$notice:"";?>
    <?=(isset($success["success"]))?$success["success"]:"";?>
	
	<div class="col-xs-11 col-sm-11 m-auto form1Container">
		<h2>Fiche Association</h2>
		    <form class="formStyle1" action="association.php<?=($modify)?"?id=".$_GET["id"]:""?>" method="post" autocomplete="on" id="contactform" enctype="multipart/form-data">
		    	
				<div class="inputGroup">
		        	<input class="textEffect1" id="lname" name="nom_assos" type="text" placeholder="Nom" maxlength="100" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,100}" title="3 caractères minimum" value="<?php if(isset($nom_assos_r)){echo $nom_assos_r; }?>" required>
		            <label for="lname">Nom</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["nom_assos"])){ echo $error["nom_assos"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input class="textEffect1" id="addr" name="adresse" type="text" placeholder="Adresse" maxlength="150" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,150}" title="3 caractères minimum"  value="<?php if(isset($adresse_r)){echo $adresse_r; }?>"> <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="addr">Adresse</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["adresse"])){ echo $error["adresse"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="zipcode" name="zip" min="0" max="99999" pattern="[0-9]{0,5}" value="<?=$zip_r ?>" aria-describedby="code postal" placeholder="Cde.Postal" > <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="zipcode">Cde.Postal</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["zip"])){ echo $error["zip"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input class="textEffect1" id="city" name="ville" type="text" placeholder="Ville" maxlength="100" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,100}" title="3 caractères minimum"  value="<?php if(isset($ville_r)){echo $ville_r; }?>"> <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="city">Ville</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["ville"])){ echo $error["ville"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telph" name="telmobile"  min="0" max="9999999999" pattern="[0-9]{10}" value="<?=$telmobile_r ?>" aria-describedby="telephone" placeholder="Tel.Portable" required>
		            <label for="telph">Tel.Portable</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["telmobile"])){ echo $error["telmobile"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telphf" maxlength="10" name="telfixe" min="0" max="9999999999" pattern="[0-9]{0,10}" value="<?=$telfixe_r ?>" aria-describedby="telephone" placeholder="Tel.Fixe" > <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="telphf">Tel.Fixe</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["telfixe"])){ echo $error["telfixe"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="email" class="textEffect1" id="mail" name="email" pattern="[A-Za-z0-9._%+-]{1,}@[a-zA-Z]{1,}([.]{1}[a-zA-Z]{2,}|[.]{1}[a-zA-Z]{2,}[.]{1}[a-zA-Z]{2,})"value="<?=$email_r ?>" aria-describedby="Email" placeholder="Email" required>
		            <label for="mail">Email</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["email"])){ echo $error["email"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<textarea class="textareaEffect1" id="notec" name="note"  placeholder="Note" cols="30" rows="10"><?php if(isset($note_r)){echo $note_r; }?></textarea>
		            <label for="notec">Note</label>
		            <span class="fieldError"><?php if(isset($error["note"])){ echo $error["note"]; } ?></span>
		        </div>
		        
		        
		        <div class="inputGroup">
		        	<input type="submit" value="Valider">
		        </div>
			</form>
		</div>
</section>
<?php
require_once("./include/chunks/footer.inc.php");
?>