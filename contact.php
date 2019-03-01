<?php
/* contact.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Contact";

$log->info("Retour dans contact.php", "");

if(!userISConnecte($fingerprint)){
	header("location:./connexion.php");
	exit();
}


//-----------------------Traitement des données formulaire
$modify=false;


if($GET_Data_Avail){ // appel de la page en modification
	$log->info("$ GET",$_GET);
	
	$table="contacts";
	
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$req="SELECT * FROM ".$table." WHERE id_contact=:id";
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
			$membre=$resultat->fetch();
			$log->info("user à modifier", $membre);
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
	if(empty($_POST["nom"])){
		$error["nom"]="<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner le champs 'Nom'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		$log->warning("error[nom]",$error["nom"]);
	}
	else{
		$verif_nom = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._\-'\s]{3,20}+$/",$_POST["nom"]);
		if(!$verif_nom){
			$error["nom"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'nom' doit contenir 3 caratères au minimum, 20 caractères au maximum sans caractères spéciaux'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			$log->warning("error[nom]",$error["nom"]);
		}
	}
	
	
	// verification du prenom
	$log->info("Verification du prénom","");
	if(empty($_POST["prenom"])){
		$error["prenom"]="<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner le champs 'Nom'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		$log->warning("error[prenom]",$error["prenom"]);
	}
	else{
		$verif_prenom = preg_match("/^[a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._\-'\s]{3,20}+$/",$_POST["prenom"]);
		if(!$verif_prenom){
			$error["prenom"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'prenom' doit contenir 3 caratères au minimum, 20 caractères au maximum sans caractères spéciaux'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			$log->warning("error[prenom]",$error["prenom"]);
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
	
	
	// verification du numero de tel mobile ( CHAMPSREQUIS)
	$log->info("Verification du tel mobile","");
	if(empty($_POST["telmob"])){
		$log->warning("error[telmob]",$error["telmob"]);
		$error["telmob"]="<span class='alert alert-danger d-block alert-dismissible'>Veuillez renseigner le champs 'Tel.Portable'<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
	}
	else{
		$verif_telmob = preg_match("/[0-9]{10}/",$_POST["telmob"]);
		if(!$verif_telmob){
			$log->warning("error[telmob]",$error["telmob"]);
			$error["telmob"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'Tel.Portable' doit contenir 10 chiffres sans espaces<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
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
	
	
	
	if(empty($error)){
		
		$table="contacts";
		
		if(!$modify){ // requete d'insertion
		
			$log->info("Insertion en BDD","");
			
			if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"])){
				$log->info("la fiche n'existe pas en memoire de session:",$_SESSION[$fingerprint]);
				$req="INSERT INTO ".$table." (nom_contact, prenom, email, telmobile, telfixe, note) VALUES (:nom, :prenom,:email,:telmob,:telfixe, :note_contact)";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":nom",$_POST["nom"],PDO::PARAM_STR);
				$resultat->bindParam(":prenom",$_POST["prenom"],PDO::PARAM_STR);
				$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
				$resultat->bindParam(":telmob",$_POST["telmob"],PDO::PARAM_STR);
				$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
				$resultat->bindParam(":note_contact",$_POST["note_contact"],PDO::PARAM_STR);
			}
			else{
				$log->info("la fiche existe en memoire de session:",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				$req="UPDATE ".$table." SET nom_contact=:nom, prenom=:prenom, email=:email, telmobile=:telmob, telfixe=:telfixe, note=:note_contact WHERE id_contact=:id";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":id",$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"],PDO::PARAM_STR);
				$resultat->bindParam(":nom",$_POST["nom"],PDO::PARAM_STR);
				$resultat->bindParam(":prenom",$_POST["prenom"],PDO::PARAM_STR);
				$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
				$resultat->bindParam(":telmob",$_POST["telmob"],PDO::PARAM_STR);
				$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
				$resultat->bindParam(":note_contact",$_POST["note_contact"],PDO::PARAM_STR);
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
				if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"])){
					$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"]=$pdo->lastInsertId();
				}
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["nom_contact"]=$_POST["nom"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["prenom_contact"]=$_POST["prenom"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["email_contact"]=$_POST["email"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telmob_contact"]=$_POST["telmob"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telfixe_contact"]=$_POST["telfixe"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["note_contact"]=$_POST["note_contact"];
				
				
				$log->info("chargement de $ SESSION",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				
				$log->info("redirection vers association.php","");
				header("location:association.php");
				exit();
			}
		}
		else{ // requete update (modify = true)
		
			$log->info("Update en BDD","");
			
			$req="UPDATE ".$table." SET nom_contact=:nom, prenom=:prenom, email=:email, telmobile=:telmob, telfixe=:telfixe, note=:note_contact WHERE id_contact=:id";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":id",$_GET["id"],PDO::PARAM_STR);
			$resultat->bindParam(":nom",$_POST["nom"],PDO::PARAM_STR);
			$resultat->bindParam(":prenom",$_POST["prenom"],PDO::PARAM_STR);
			$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
			$resultat->bindParam(":telmob",$_POST["telmob"],PDO::PARAM_STR);
			$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
			$resultat->bindParam(":note_contact",$_POST["note_contact"],PDO::PARAM_STR);
			
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
	if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"])){
		extract($_POST);
		$nom_r=(isset($nom))?$nom:"";
		$prenom_r=(isset($prenom))?$prenom:"";
		$email_r=(isset($email))?$email:"";
		$telmob_r=(isset($telmob))?$telmob:"";
		$telfixe_r=(isset($telfixe))?$telfixe:"";
		$note_contact_r=(isset($note_contact))?$note_contact:"";
	}
	else{
		$nom_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["nom_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["nom_contact"]:"ERREUR!!!";
		$prenom_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["prenom_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["prenom_contact"]:"ERREUR!!!";
		$email_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["email_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["email_contact"]:"ERREUR!!!";
		$telmob_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telmob_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telmob_contact"]:"ERREUR!!!";
		$telfixe_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telfixe_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["telfixe_contact"]:"ERREUR!!!";
		$note_contact_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["note_contact"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["note_contact"]:"ERREUR!!!";
	}
}
else{
	$nom_r=(isset($_POST["nom"]))?$_POST["nom"]:$membre["nom_contact"];
	$prenom_r=(isset($_POST["prenom"]))?$_POST["prenom"]:$membre["prenom"];
	$email_r=(isset($_POST["email"]))?$_POST["email"]:$membre["email"];
	$telmob_r=(isset($_POST["telmob"]))?$_POST["telmob"]:$membre["telmobile"];
	$telfixe_r=(isset($_POST["telfixe"]))?$_POST["telfixe"]:$membre["telfixe"];
	$note_contact_r=(isset($_POST["note_contact"]))?$_POST["note_contact"]:$membre["note"];
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
		<h2>Fiche Contact</h2>
		    <form class="formStyle1" action="contact.php<?=($modify)?"?id=".$_GET["id"]:""?>" method="post" autocomplete="on" id="contactform" enctype="multipart/form-data">
		    	
				<div class="inputGroup">
		        	<input class="textEffect1" id="lname" name="nom" type="text" placeholder="Nom" maxlength="20" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,20}" title="3 caractères minimum" value="<?php if(isset($nom_r)){echo $nom_r; }?>" required>
		            <label for="lname">Nom</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["nom"])){ echo $error["nom"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input class="textEffect1" id="fname" name="prenom" type="text" placeholder="Prénom" maxlength="20" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,20}" title="3 caractères minimum"  required value="<?php if(isset($prenom_r)){echo $prenom_r; }?>" required>
		            <label for="fname">Prénom</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["prenom"])){ echo $error["prenom"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="email" class="textEffect1" id="mail" name="email" pattern="[A-Za-z0-9._%+-]{1,}@[a-zA-Z]{1,}([.]{1}[a-zA-Z]{2,}|[.]{1}[a-zA-Z]{2,}[.]{1}[a-zA-Z]{2,})"value="<?=$email_r ?>" aria-describedby="Email" placeholder="Email" required>
		            <label for="mail">Email</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["email"])){ echo $error["email"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telph" name="telmob"  min="0" max="9999999999" pattern="[0-9]{10}" value="<?=$telmob_r ?>" aria-describedby="telephone" placeholder="Tel.Portable" required>
		            <label for="telph">Tel.Portable</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["telmob"])){ echo $error["telmob"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telphf" maxlength="10" name="telfixe" min="0" max="9999999999" pattern="[0-9]{0,10}" value="<?=$telfixe_r ?>" aria-describedby="telephone" placeholder="Tel.Fixe" > <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="telphf">Tel.Fixe</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["telfixe"])){ echo $error["telfixe"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<textarea class="textareaEffect1" id="notec" name="note_contact"  placeholder="Note" cols="30" rows="10"><?php if(isset($note_contact_r)){echo $note_contact_r; }?></textarea>
		            <label for="notec">Note</label>
		            <span class="fieldError"><?php if(isset($error["note_contact"])){ echo $error["note_contact"]; } ?></span>
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