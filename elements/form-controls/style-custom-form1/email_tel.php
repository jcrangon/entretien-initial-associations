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
			$log->info("email à modifier", $membre);
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
			$error["telfixe"]="<span class='alert alert-danger d-block alert-dismissible'>Le champs 'Tel.Portable' doit contenir 10 chiffres sans espaces<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
		}
	}
    
	
	if(empty($error)){
		
		$table="table";
		
		if(!$modify){ // requete d'insertion
		
			$log->info("Insertion en BDD","");
			
			
			$req="INSERT INTO ".$table." (email, telmob, telfixe) VALUES (:email, :telmob, :telfixe)";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
			$resultat->bindParam(":telmob",$_POST["telmob"],PDO::PARAM_STR);
			$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
			
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
			
			$req="UPDATE ".$table." SET email=:email, telmobile=:telmob, telfixe=:telfixe WHERE id=:id";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":email",$_POST["email"],PDO::PARAM_STR);
			$resultat->bindParam(":telmob",$_POST["telmob"],PDO::PARAM_STR);
			$resultat->bindParam(":telfixe",$_POST["telfixe"],PDO::PARAM_STR);
			
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
	$email_r=(isset($email))?$email:"";
	$telmob_r=(isset($telmob))?$telmob:"";
	$telfixe_r=(isset($telfixe))?$telfixe:"";
}
else{
	$email_r=(isset($_POST["email"]))?$_POST["email"]:$membre["email"];
	$telmob_r=(isset($_POST["telmob"]))?$_POST["telmob"]:$membre["telmobile_contact"];
	$telfixe_r=(isset($_POST["telfixe"]))?$_POST["telfixe"]:$membre["telfixe_contact"];
}




?>
<section class="container">
	<div class="row">
    <?=(isset($notice))?$notice:"";?>
    
    <div class="col-xs-11 col-sm-11 m-auto">
    	
    	<div class="form1Container">
		    <form class="formStyle1" action="inscription.php<?=($modify)?"?id=".$_GET["id"]:""?>" method="post" autocomplete="on" id="inscr" enctype="multipart/form-data">
		    	
				<div class="inputGroup">
		        	<input type="email" class="textEffect1" id="mail" name="email" pattern="[A-Za-z0-9._%+-]{1,}@[a-zA-Z]{1,}([.]{1}[a-zA-Z]{2,}|[.]{1}[a-zA-Z]{2,}[.]{1}[a-zA-Z]{2,})"value="<?=$email_r ?>" aria-describedby="Email" placeholder="Email" required>
		            <label for="mail">Email</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["email"])){ echo $error["email"]; } ?></span>
		        </div>
		        
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telph" name="telmob"  min="0" max="9999999999" pattern="[0-9]{10}" value="<?=$telmob_r ?>" aria-describedby="telephone mobile" placeholder="Tel.Portable" required>
		            <label for="telph">Tel.Portable</label>
		            <span class="requiredField">*</span>
		            <span class="fieldError"><?php if(isset($error["telmob"])){ echo $error["telmob"]; } ?></span>
		        </div>
		        
		        <div class="inputGroup">
		        	<input type="number" class="textEffect1" id="telphf" min="0" max="9999999999" name="telfixe" pattern="[0-9]{0,10}" value="<?=$telfixe_r ?>" aria-describedby="telephone fixe" placeholder="Tel.Fixe" > <!-- CHAMPS NON OBLIGATOIRE -->
		            <label for="telphf">Tel.Fixe</label>
		            <span class="requiredField"></span>
		            <span class="fieldError"><?php if(isset($error["telfixe"])){ echo $error["telfixe"]; } ?></span>
		        </div>
		        
			</form>
		</div>
		<div class="inputGroup">
        	<input type="submit" value="Valider">
        </div>
	</div>
</section>














