<?php
// traitement complet de 2 champs de formulaire en inseretion et en modification
$modify=false;


if($GET_Data_Avail){ // appel de la page en modification
	$log->info("$ GET",$_GET);
	
	$table="contacts";
	
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$req="SELECT * FROM ".$table." WHERE id=:id";
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
			$log->info("nom et prenom à modifier", $membre);
			$modify=true;
		}
		else{
			$log->error("La requete a retourné 0 lignes","");
		}
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
	
	
	if(empty($error)){
		
		$table="table";
		
		if(!$modify){ // requete d'insertion
		
			$log->info("Insertion en BDD","");
			
			
			$req="INSERT INTO ".$table." (nom, prenom) VALUES (:nom, :prenom)";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":nom",$_POST["nom"],PDO::PARAM_STR);
			$resultat->bindParam(":prenom",$_POST["prenom"],PDO::PARAM_STR);
			
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
				$log->error("Succès","");
				// ... autre code a executer 
				$success["success"]="<span class='alert alert-success d-block m-auto alert-dismissible'>Transaction validée avec succes! <a class='btn btn-outline-success' href='./ajout-transaction.php'>Nouvelle Transation</a> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
		}
		else{ // requete update (modify = true)
		
			$log->info("Update en BDD","");
			
			$req="UPDATE ".$table." SET nom=:nom, prenom=:prenom WHERE id=:id";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":id",$_GET["id"],PDO::PARAM_STR);
			$resultat->bindParam(":nom",$_POST["nom"],PDO::PARAM_STR);
			$resultat->bindParam(":prenom",$_POST["prenom"],PDO::PARAM_STR);
			
			try{
				$resultat->execute();
			}
			catch(PDOException $e){
				$log->error("Erreur PDO",$e->getMessage());
				die($errCodes["resultat_execute_fails"]);
			}
			
			if(!$resultat){
				$log->error("Echec requete UPDATE","");
				$notice.="<span class='alert alert-danger d-block alert-dismissible'>Une erreur est survenue. Essayez de recharger la page.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
			else{
				$log->error("Succès","");
				// ... autre code a executer 
				$success["success"]="<span class='alert alert-success d-block m-auto alert-dismissible'>Transaction validée avec succes! <a class='btn btn-outline-success' href='./ajout-transaction.php'>Nouvelle Transation</a> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
			}
		}
	}
	else{ // le tableau $error n'est pas vide
		$notice="<span class='alert alert-danger d-block m-auto alert-dismissible'>Des erreurs existent dans le formulaire.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
	}
}
if(!$modify){
	extract($_POST);
	$nom_r=(isset($nom))?$nom:"";
	$prenom_r=(isset($prenom))?$prenom:"";
}
else{
	$nom_r=(isset($_POST["nom"]))?$_POST["nom"]:$membre["nom_contact"];
	$prenom_r=(isset($_POST["prenom"]))?$_POST["prenom"]:$membre["prenom_contact"];
}


?>
<section class="container">
	<div class="row">
    <?=(isset($notice))?$notice:"";?>
    
    <div class="col-xs-11 col-sm-11 m-auto">
	    <form action="inscription.php<?=($modify)?"?id=".$_GET["id"]:""?>" method="post" autocomplete="on" id="inscr" enctype="multipart/form-data">
			<div class="form-group">
	            <label for="lname">Nom: (*)</label>
	            <input type="text" class="form-control" id="lname" name="nom" value="<?=$nom_r ?>" aria-describedby="last name" placeholder="Nom" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,20}"  title="3 caractères minimum" required>
	            <?php if(isset($error["nom"])){ echo $error["nom"];}  ?>
	        </div>
	
	        <div class="form-group">
	            <label for="fname">Prénom: (*)</label>
	            <input type="text" class="form-control" id="fname" name="prenom" value="<?=$prenom_r ?>" aria-describedby="first name" placeholder="Prénom" pattern="[A-Za-zÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ._-\'\s]{3,20}"  title="3 caractères minimum" required>
	            <span class="FieldError"></span>
	            <?php if(isset($error["prenom"])){ echo $error["prenom"];}  ?>
	        </div>
		</form>
	</div>
</section>