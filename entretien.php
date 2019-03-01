<?php
/* contact.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Entretien";

$log->info("Retour dans entretien.php", "");

if(!userISConnecte($fingerprint)){
	header("location:./connexion.php");
	exit();
}


//-----------------------Traitement des données formulaire
$modify=false;


if($GET_Data_Avail){ // appel de la page en modification
	$log->info("$ GET",$_GET);
	
	$table="details_entretiens";
	
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$req="SELECT * FROM ".$table." WHERE id_details_entretien=:id";
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
			$log->info("association à ", $entretien);
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

	if(empty($error)){
		
		$table="details_entretiens";
		
		if(!$modify){ // requete d'insertion
		
			$log->info("Insertion en BDD","");
			
			if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"])){
				$log->info("la fiche n'existe pas encore en memoire de session:",$_SESSION[$fingerprint]);
				$req="INSERT INTO ".$table." (id_assos, rep1, rep2, rep3, rep4, rep5, rep6, rep7, rep8, rep9, rep10, rep11, rep12, rep13, rep14, rep15, rep16, rep17, rep18, rep19, rep20, rep21, rep22, rep23, rep24, rep25, rep26, rep27, rep28, rep29, rep30) ";
				$req.="VALUES (:id_assos, :rep1, :rep2, :rep3, :rep4, :rep5, :rep6, :rep7, :rep8, :rep9, :rep10, :rep11, :rep12, :rep13, :rep14, :rep15, :rep16, :rep17, :rep18, :rep19, :rep20, :rep21, :rep22, :rep23, :rep24, :rep25, :rep26, :rep27, :rep28, :rep29, :rep30)";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":id_assos",$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"],PDO::PARAM_INT);
				$resultat->bindParam(":rep1",$_POST["rep1"],PDO::PARAM_STR);
				$resultat->bindParam(":rep2",$_POST["rep2"],PDO::PARAM_STR);
				$resultat->bindParam(":rep3",$_POST["rep3"],PDO::PARAM_STR);
				$resultat->bindParam(":rep4",$_POST["rep4"],PDO::PARAM_STR);
				$resultat->bindParam(":rep5",$_POST["rep5"],PDO::PARAM_STR);
				$resultat->bindParam(":rep6",$_POST["rep6"],PDO::PARAM_STR);
				$resultat->bindParam(":rep7",$_POST["rep7"],PDO::PARAM_STR);
				$resultat->bindParam(":rep8",$_POST["rep8"],PDO::PARAM_STR);
				$resultat->bindParam(":rep9",$_POST["rep9"],PDO::PARAM_STR);
				$resultat->bindParam(":rep10",$_POST["rep10"],PDO::PARAM_STR);
				$resultat->bindParam(":rep11",$_POST["rep11"],PDO::PARAM_STR);
				$resultat->bindParam(":rep12",$_POST["rep12"],PDO::PARAM_STR);
				$resultat->bindParam(":rep13",$_POST["rep13"],PDO::PARAM_STR);
				$resultat->bindParam(":rep14",$_POST["rep14"],PDO::PARAM_STR);
				$resultat->bindParam(":rep15",$_POST["rep15"],PDO::PARAM_STR);
				$resultat->bindParam(":rep16",$_POST["rep16"],PDO::PARAM_STR);
				$resultat->bindParam(":rep17",$_POST["rep17"],PDO::PARAM_STR);
				$resultat->bindParam(":rep18",$_POST["rep18"],PDO::PARAM_STR);
				$resultat->bindParam(":rep19",$_POST["rep19"],PDO::PARAM_STR);
				$resultat->bindParam(":rep20",$_POST["rep20"],PDO::PARAM_STR);
				$resultat->bindParam(":rep21",$_POST["rep21"],PDO::PARAM_STR);
				$resultat->bindParam(":rep22",$_POST["rep22"],PDO::PARAM_STR);
				$resultat->bindParam(":rep23",$_POST["rep23"],PDO::PARAM_STR);
				$resultat->bindParam(":rep24",$_POST["rep24"],PDO::PARAM_STR);
				$resultat->bindParam(":rep25",$_POST["rep25"],PDO::PARAM_STR);
				$resultat->bindParam(":rep26",$_POST["rep26"],PDO::PARAM_STR);
				$resultat->bindParam(":rep27",$_POST["rep27"],PDO::PARAM_STR);
				$resultat->bindParam(":rep28",$_POST["rep28"],PDO::PARAM_STR);
				$resultat->bindParam(":rep29",$_POST["rep29"],PDO::PARAM_STR);
				$resultat->bindParam(":rep30",$_POST["rep30"],PDO::PARAM_STR);
			}
			else{
				$log->info("la fiche existe en memoire de session:",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				$req="UPDATE ".$table." SET id_assos=:id_assos, rep1=:rep1, rep2=:rep2, rep3=:rep3, rep4=:rep4, rep5=:rep5, rep6=:rep6, rep7=:rep7, rep8=:rep8 , rep9=:rep9, rep10=:rep10, rep11=:rep11, rep12=:rep12, rep13=:rep13, rep14=:rep14, rep15=:rep15, rep16=:rep16, rep17=:rep17, rep18=:rep18, rep19=:rep19, rep20=:rep20, rep21=:rep21, rep22=:rep22, rep23=:rep23, rep24=:rep24, rep25=:rep25, rep26=:rep26, rep27=:rep27, rep28=:rep28, rep29=:rep29, rep30=:rep30   WHERE id_details_entretien=:id_details_entretien";
				$resultat=$pdo->prepare($req);
				$resultat->bindParam(":id_details_entretien",$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"],PDO::PARAM_INT);
				$resultat->bindParam(":id_assos",$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"],PDO::PARAM_INT);
				$resultat->bindParam(":rep1",$_POST["rep1"],PDO::PARAM_STR);
				$resultat->bindParam(":rep2",$_POST["rep2"],PDO::PARAM_STR);
				$resultat->bindParam(":rep3",$_POST["rep3"],PDO::PARAM_STR);
				$resultat->bindParam(":rep4",$_POST["rep4"],PDO::PARAM_STR);
				$resultat->bindParam(":rep5",$_POST["rep5"],PDO::PARAM_STR);
				$resultat->bindParam(":rep6",$_POST["rep6"],PDO::PARAM_STR);
				$resultat->bindParam(":rep7",$_POST["rep7"],PDO::PARAM_STR);
				$resultat->bindParam(":rep8",$_POST["rep8"],PDO::PARAM_STR);
				$resultat->bindParam(":rep9",$_POST["rep9"],PDO::PARAM_STR);
				$resultat->bindParam(":rep10",$_POST["rep10"],PDO::PARAM_STR);
				$resultat->bindParam(":rep11",$_POST["rep11"],PDO::PARAM_STR);
				$resultat->bindParam(":rep12",$_POST["rep12"],PDO::PARAM_STR);
				$resultat->bindParam(":rep13",$_POST["rep13"],PDO::PARAM_STR);
				$resultat->bindParam(":rep14",$_POST["rep14"],PDO::PARAM_STR);
				$resultat->bindParam(":rep15",$_POST["rep15"],PDO::PARAM_STR);
				$resultat->bindParam(":rep16",$_POST["rep16"],PDO::PARAM_STR);
				$resultat->bindParam(":rep17",$_POST["rep17"],PDO::PARAM_STR);
				$resultat->bindParam(":rep18",$_POST["rep18"],PDO::PARAM_STR);
				$resultat->bindParam(":rep19",$_POST["rep19"],PDO::PARAM_STR);
				$resultat->bindParam(":rep20",$_POST["rep20"],PDO::PARAM_STR);
				$resultat->bindParam(":rep21",$_POST["rep21"],PDO::PARAM_STR);
				$resultat->bindParam(":rep22",$_POST["rep22"],PDO::PARAM_STR);
				$resultat->bindParam(":rep23",$_POST["rep23"],PDO::PARAM_STR);
				$resultat->bindParam(":rep24",$_POST["rep24"],PDO::PARAM_STR);
				$resultat->bindParam(":rep25",$_POST["rep25"],PDO::PARAM_STR);
				$resultat->bindParam(":rep26",$_POST["rep26"],PDO::PARAM_STR);
				$resultat->bindParam(":rep27",$_POST["rep27"],PDO::PARAM_STR);
				$resultat->bindParam(":rep28",$_POST["rep28"],PDO::PARAM_STR);
				$resultat->bindParam(":rep29",$_POST["rep29"],PDO::PARAM_STR);
				$resultat->bindParam(":rep30",$_POST["rep30"],PDO::PARAM_STR);
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
				$success["success"]="<span class='alert alert-success d-block m-auto alert-dismissible'>Transaction validée avec succes! <a class='btn btn-outline-success' href='./finalisation.php'>Terminer l'entretien</a> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></span>";
				
				// chargement de $_SESSION
				if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"])){
					$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"]=$pdo->lastInsertId();
				}
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep1"]=$_POST["rep1"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep2"]=$_POST["rep2"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep3"]=$_POST["rep3"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep4"]=$_POST["rep4"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep5"]=$_POST["rep5"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep6"]=$_POST["rep6"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep7"]=$_POST["rep7"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep8"]=$_POST["rep8"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep9"]=$_POST["rep9"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep10"]=$_POST["rep10"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep11"]=$_POST["rep11"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep12"]=$_POST["rep12"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep13"]=$_POST["rep13"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep14"]=$_POST["rep14"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep15"]=$_POST["rep15"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep16"]=$_POST["rep16"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep17"]=$_POST["rep17"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep18"]=$_POST["rep18"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep19"]=$_POST["rep19"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep20"]=$_POST["rep20"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep21"]=$_POST["rep21"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep22"]=$_POST["rep22"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep23"]=$_POST["rep23"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep24"]=$_POST["rep24"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep25"]=$_POST["rep25"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep26"]=$_POST["rep26"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep27"]=$_POST["rep27"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep28"]=$_POST["rep28"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep29"]=$_POST["rep29"];
				$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep30"]=$_POST["rep30"];
				
				
				$log->info("chargement de $ SESSION",$_SESSION[$fingerprint]["nouvelle_fiche"]);
				
				$log->info("mise a jour de la table liste_entretiens","");
				$table="liste_entretiens";
				$todayd=date("Y-m-d");
				
				if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["liste_entretien"]["id"])){
					$req="INSERT INTO ".$table." (date, id_contact, id_assos, id_details_entretien) VALUES (:date,:id_contact, :id_assos, :id_details_entretien)";
					$resultat=$pdo->prepare($req);
					$resultat->bindParam(":date",$todayd,PDO::PARAM_STR);
					$resultat->bindParam(":id_contact",$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"],PDO::PARAM_STR);
					$resultat->bindParam(":id_assos",$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"],PDO::PARAM_STR);
					$resultat->bindParam(":id_details_entretien",$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"],PDO::PARAM_STR);
				}
				else{
					$req="UPDATE ".$table." SET date=:date, id_contact=:id_contact, id_assos=:id_assos, id_details_entretien=:id_details_entretien WHERE id_entretien=:id_entretien";
						$resultat=$pdo->prepare($req);
						$resultat->bindParam(":date",$todayd,PDO::PARAM_STR);
						$resultat->bindParam(":id_contact",$_SESSION[$fingerprint]["nouvelle_fiche"]["contact"]["id"],PDO::PARAM_STR);
						$resultat->bindParam(":id_assos",$_SESSION[$fingerprint]["nouvelle_fiche"]["assos"]["id"],PDO::PARAM_STR);
						$resultat->bindParam(":id_details_entretien",$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["id"],PDO::PARAM_STR);
						$resultat->bindParam(":id_entretien",$_SESSION[$fingerprint]["nouvelle_fiche"]["liste_entretien"]["id"],PDO::PARAM_STR);
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
					$success["success"]="<span class='alert alert-success d-block m-auto '>Transaction validée avec succes! <a class='btn btn-outline-success' href='./finalisation.php'>Terminer l'entretien</a></span>";
					
					if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["liste_entretien"]["id"])){
						$_SESSION[$fingerprint]["nouvelle_fiche"]["liste_entretien"]["id"]=$pdo->lastInsertId();
					}
				}
			}
		}
		else{ // requete update (modify = true)
		
			$log->info("Update en BDD","");
			
			$req="UPDATE ".$table." SET id_assos=:id_assos, rep1=:rep1, rep2=:rep2, rep3=:rep3, rep4=:rep4, rep5=:rep5, rep6=:rep6, rep7=:rep7, rep8=:rep8 , rep9=:rep9, rep10=:rep10, rep11=:rep11, rep12=:rep12, rep13=:rep13, rep14=:rep14, rep15=:rep15, rep16=:rep16, rep17=:rep17, rep18=:rep18, rep19=:rep19, rep20=:rep20, rep21=:rep21, rep22=:rep22, rep23=:rep23, rep24=:rep24, rep25=:rep25, rep26=:rep26, rep27=:rep27, rep28=:rep28, rep29=:rep29, rep30=:rep30   WHERE id_details_entretien=:id_details_entretien";
			$resultat=$pdo->prepare($req);
			$resultat->bindParam(":id_details_entretien",$_GET["id"],PDO::PARAM_INT);
			$resultat->bindParam(":id_assos",$entretien["id_assos"],PDO::PARAM_INT);
			$resultat->bindParam(":rep1",$_POST["rep1"],PDO::PARAM_STR);
			$resultat->bindParam(":rep2",$_POST["rep2"],PDO::PARAM_STR);
			$resultat->bindParam(":rep3",$_POST["rep3"],PDO::PARAM_STR);
			$resultat->bindParam(":rep4",$_POST["rep4"],PDO::PARAM_STR);
			$resultat->bindParam(":rep5",$_POST["rep5"],PDO::PARAM_STR);
			$resultat->bindParam(":rep6",$_POST["rep6"],PDO::PARAM_STR);
			$resultat->bindParam(":rep7",$_POST["rep7"],PDO::PARAM_STR);
			$resultat->bindParam(":rep8",$_POST["rep8"],PDO::PARAM_STR);
			$resultat->bindParam(":rep9",$_POST["rep9"],PDO::PARAM_STR);
			$resultat->bindParam(":rep10",$_POST["rep10"],PDO::PARAM_STR);
			$resultat->bindParam(":rep11",$_POST["rep11"],PDO::PARAM_STR);
			$resultat->bindParam(":rep12",$_POST["rep12"],PDO::PARAM_STR);
			$resultat->bindParam(":rep13",$_POST["rep13"],PDO::PARAM_STR);
			$resultat->bindParam(":rep14",$_POST["rep14"],PDO::PARAM_STR);
			$resultat->bindParam(":rep15",$_POST["rep15"],PDO::PARAM_STR);
			$resultat->bindParam(":rep16",$_POST["rep16"],PDO::PARAM_STR);
			$resultat->bindParam(":rep17",$_POST["rep17"],PDO::PARAM_STR);
			$resultat->bindParam(":rep18",$_POST["rep18"],PDO::PARAM_STR);
			$resultat->bindParam(":rep19",$_POST["rep19"],PDO::PARAM_STR);
			$resultat->bindParam(":rep20",$_POST["rep20"],PDO::PARAM_STR);
			$resultat->bindParam(":rep21",$_POST["rep21"],PDO::PARAM_STR);
			$resultat->bindParam(":rep22",$_POST["rep22"],PDO::PARAM_STR);
			$resultat->bindParam(":rep23",$_POST["rep23"],PDO::PARAM_STR);
			$resultat->bindParam(":rep24",$_POST["rep24"],PDO::PARAM_STR);
			$resultat->bindParam(":rep25",$_POST["rep25"],PDO::PARAM_STR);
			$resultat->bindParam(":rep26",$_POST["rep26"],PDO::PARAM_STR);
			$resultat->bindParam(":rep27",$_POST["rep27"],PDO::PARAM_STR);
			$resultat->bindParam(":rep28",$_POST["rep28"],PDO::PARAM_STR);
			$resultat->bindParam(":rep29",$_POST["rep29"],PDO::PARAM_STR);
			$resultat->bindParam(":rep30",$_POST["rep30"],PDO::PARAM_STR);
			
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
	if(!isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"])){
		extract($_POST);
		$rep1_r=(isset($rep1))?$rep1:"";
		$rep2_r=(isset($rep2))?$rep2:"";
		$rep3_r=(isset($rep3))?$rep3:"";
		$rep4_r=(isset($rep4))?$rep4:"";
		$rep5_r=(isset($rep5))?$rep5:"";
		$rep6_r=(isset($rep6))?$rep6:"";
		$rep7_r=(isset($rep7))?$rep7:"";
		$rep8_r=(isset($rep8))?$rep8:"";
		$rep9_r=(isset($rep9))?$rep9:"";
		$rep10_r=(isset($rep10))?$rep10:"";
		$rep11_r=(isset($rep11))?$rep11:"";
		$rep12_r=(isset($rep12))?$rep12:"";
		$rep13_r=(isset($rep13))?$rep13:"";
		$rep14_r=(isset($rep14))?$rep14:"";
		$rep15_r=(isset($rep15))?$rep15:"";
		$rep16_r=(isset($rep16))?$rep16:"";
		$rep17_r=(isset($rep17))?$rep17:"";
		$rep18_r=(isset($rep18))?$rep18:"";
		$rep19_r=(isset($rep19))?$rep19:"";
		$rep20_r=(isset($rep20))?$rep20:"";
		$rep21_r=(isset($rep21))?$rep21:"";
		$rep22_r=(isset($rep22))?$rep22:"";
		$rep23_r=(isset($rep23))?$rep23:"";
		$rep24_r=(isset($rep24))?$rep24:"";
		$rep25_r=(isset($rep25))?$rep25:"";
		$rep26_r=(isset($rep26))?$rep26:"";
		$rep27_r=(isset($rep27))?$rep27:"";
		$rep28_r=(isset($rep28))?$rep28:"";
		$rep29_r=(isset($rep29))?$rep29:"";
		$rep30_r=(isset($rep30))?$rep30:"";
	}
	else{
		$rep1_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep1"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep1"]:"ERREUR!!!";
		$rep2_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep2"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep2"]:"ERREUR!!!";
		$rep3_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep3"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep3"]:"ERREUR!!!";
		$rep4_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep4"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep4"]:"ERREUR!!!";
		$rep5_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep5"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep5"]:"ERREUR!!!";
		$rep6_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep6"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep6"]:"ERREUR!!!";
		$rep7_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep7"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep7"]:"ERREUR!!!";
		$rep8_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep8"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep8"]:"ERREUR!!!";
		$rep9_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep9"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep9"]:"ERREUR!!!";
		$rep10_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep10"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep10"]:"ERREUR!!!";
		
		$rep11_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep11"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep11"]:"ERREUR!!!";
		$rep12_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep12"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep12"]:"ERREUR!!!";
		$rep13_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep13"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep13"]:"ERREUR!!!";
		$rep14_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep14"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep14"]:"ERREUR!!!";
		$rep15_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep15"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep15"]:"ERREUR!!!";
		$rep16_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep16"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep16"]:"ERREUR!!!";
		$rep17_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep17"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep17"]:"ERREUR!!!";
		$rep18_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep18"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep18"]:"ERREUR!!!";
		$rep19_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep19"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep19"]:"ERREUR!!!";
		$rep20_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep20"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep20"]:"ERREUR!!!";
		
		$rep21_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep21"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep21"]:"ERREUR!!!";
		$rep22_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep22"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep22"]:"ERREUR!!!";
		$rep23_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep23"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep23"]:"ERREUR!!!";
		$rep24_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep24"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep24"]:"ERREUR!!!";
		$rep25_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep25"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep25"]:"ERREUR!!!";
		$rep26_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep26"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep26"]:"ERREUR!!!";
		$rep27_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep27"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep27"]:"ERREUR!!!";
		$rep28_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep28"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep28"]:"ERREUR!!!";
		$rep29_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep29"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep29"]:"ERREUR!!!";
		$rep30_r=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep30"]))?$_SESSION[$fingerprint]["nouvelle_fiche"]["entretien"]["rep30"]:"ERREUR!!!";
	}
}
else{
	$rep1_r=(isset($_POST["rep1"]))?$_POST["rep1"]:$assos["rep1"];
	$rep2_r=(isset($_POST["rep2"]))?$_POST["rep2"]:$assos["rep2"];
	$rep3_r=(isset($_POST["rep3"]))?$_POST["rep3"]:$assos["rep3"];
	$rep4_r=(isset($_POST["rep4"]))?$_POST["rep4"]:$assos["rep4"];
	$rep5_r=(isset($_POST["rep5"]))?$_POST["rep5"]:$assos["rep5"];
	$rep6_r=(isset($_POST["rep6"]))?$_POST["rep6"]:$assos["rep6"];
	$rep7_r=(isset($_POST["rep7"]))?$_POST["rep7"]:$assos["rep7"];
	$rep8_r=(isset($_POST["rep8"]))?$_POST["rep8"]:$assos["rep8"];
	$rep9_r=(isset($_POST["rep9"]))?$_POST["rep9"]:$assos["rep9"];
	$rep10_r=(isset($_POST["rep10"]))?$_POST["rep10"]:$assos["rep10"];
	
	$rep11_r=(isset($_POST["rep11"]))?$_POST["rep11"]:$assos["rep11"];
	$rep12_r=(isset($_POST["rep12"]))?$_POST["rep12"]:$assos["rep12"];
	$rep13_r=(isset($_POST["rep13"]))?$_POST["rep13"]:$assos["rep13"];
	$rep14_r=(isset($_POST["rep14"]))?$_POST["rep14"]:$assos["rep14"];
	$rep15_r=(isset($_POST["rep15"]))?$_POST["rep15"]:$assos["rep15"];
	$rep16_r=(isset($_POST["rep16"]))?$_POST["rep16"]:$assos["rep16"];
	$rep17_r=(isset($_POST["rep17"]))?$_POST["rep17"]:$assos["rep17"];
	$rep18_r=(isset($_POST["rep18"]))?$_POST["rep18"]:$assos["rep18"];
	$rep19_r=(isset($_POST["rep19"]))?$_POST["rep19"]:$assos["rep19"];
	$rep20_r=(isset($_POST["rep20"]))?$_POST["rep20"]:$assos["rep20"];
	
	$rep21_r=(isset($_POST["rep21"]))?$_POST["rep21"]:$assos["rep21"];
	$rep22_r=(isset($_POST["rep22"]))?$_POST["rep22"]:$assos["rep22"];
	$rep23_r=(isset($_POST["rep23"]))?$_POST["rep23"]:$assos["rep23"];
	$rep24_r=(isset($_POST["rep24"]))?$_POST["rep24"]:$assos["rep24"];
	$rep25_r=(isset($_POST["rep25"]))?$_POST["rep25"]:$assos["rep25"];
	$rep26_r=(isset($_POST["rep26"]))?$_POST["rep26"]:$assos["rep26"];
	$rep27_r=(isset($_POST["rep27"]))?$_POST["rep27"]:$assos["rep27"];
	$rep28_r=(isset($_POST["rep28"]))?$_POST["rep28"]:$assos["rep28"];
	$rep29_r=(isset($_POST["rep29"]))?$_POST["rep29"]:$assos["rep29"];
	$rep30_r=(isset($_POST["rep30"]))?$_POST["rep30"]:$assos["rep30"];
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
		<h2>Fiche Entretien</h2>
		    <form class="formStyle1" action="entretien.php<?=($modify)?"?id=".$_GET["id"]:""?>" method="post" autocomplete="on" id="contactform" enctype="multipart/form-data">
		    	
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ol class="entretien-ol entretien_ol">
						I./ Se présenter
							<li> identite</li>
							<li> coordonnées</li>
							<li> parcours</li>
							<li> situation actuelle</li>
							<li> competences</li>
							<li> combien de projets realisés</li>
							<li> combien de projets en cours</li>
						</ol>
					</div>
		        </div>
		        
		        <div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
						II./ L'association
							<li> Quel genre d'association  est votre association ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep1" name="rep1"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep1_r)){echo $rep1_r; }?></textarea>
					            <label for="rep1">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep1"])){ echo $error["rep1"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Depuis combien de temps votre association  est-elle en activité ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep2" name="rep2"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep2_r)){echo $rep2_r; }?></textarea>
					            <label for="rep2">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep2"])){ echo $error["rep2"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle est la taille de l'association ?  combien d'adherents?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep3" name="rep3"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep3_r)){echo $rep3_r; }?></textarea>
					            <label for="rep3">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep3"])){ echo $error["rep3"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle est le but de votre association</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep4" name="rep4"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep4_r)){echo $rep4_r; }?></textarea>
					            <label for="rep4">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep4"])){ echo $error["rep4"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle est la réputation de l'association ? est elle connue?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep5" name="rep5"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep5_r)){echo $rep5_r; }?></textarea>
					            <label for="rep5">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep5"])){ echo $error["rep5"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle est votre adherent type ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep6" name="rep6"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep6_r)){echo $rep6_r; }?></textarea>
					            <label for="rep6">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep6"])){ echo $error["rep6"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Qui sont les associations oeuvrant dans le meme secteur que la votre ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep7" name="rep7"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep7_r)){echo $rep7_r; }?></textarea>
					            <label for="rep7">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep7"])){ echo $error["rep7"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Qui sont les associations oeuvrant dans le meme secteur que la votre ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep8" name="rep8"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep8_r)){echo $rep8_r; }?></textarea>
					            <label for="rep8">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep8"])){ echo $error["rep8"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
						III./ Le client
						<p class="entretien-p">investiguer sur la situation actuelle vécue par votre client et celle qu'il rêve de vivre. Comprendre ce qu'il cherche à obtenir ou à résoudre. Les questions doivent porter sur 5 domaines:</p>
							<li> Qu'envisagez vous pour l'avenir de votre association. Quels sont vos rêves, par ordre d'importance à vos yeux?</li>
							<p class="entretien-p">
								Que pensez-vous de...", "Quel est votre avis...", ou "Pourquoi à votre sens... ". Ce sont les questions propres à mettre au jour les modes de raisonnement, à comprendre les systèmes de valeur, les références culturelles de l'individu, ses attentes, ses blocages, ce qu'il admire, ce qu'il réprouve, etc. Grâce à elles, vous connaîtrez son goût pour les couleurs, pour les formes et ses modes de raisonnement.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep9" name="rep9"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep9_r)){echo $rep9_r; }?></textarea>
					            <label for="rep9">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep9"])){ echo $error["rep9"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Que pouvez vous me dire de la situation actuelle de votre association? Identifiez vous des difficultés particulières?</li>
							<p class="entretien-p">
								Questions commençant par combien, quand, qui, où, sont des questions dont les réponses sont concrètes et objective. Elles visent à permettre de définir les situations et sont particulièrement bien adaptées à cerner la situation actuelle.les règles et obligations auxquelles il est soumis. Les faits sont tout ce qui a un caractère réel, tangible et/ou quantifiable.Les réponses matérialisent concrètement le contexte dans lequel se trouve notre client.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep10" name="rep10"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep10_r)){echo $rep10_r; }?></textarea>
					            <label for="rep10">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep10"])){ echo $error["rep10"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Qu'envisagez vous comme action correctrice, curative, preventive? Comment pensez vous que le digital peut vous aider?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep11" name="rep11"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep11_r)){echo $rep11_r; }?></textarea>
					            <label for="rep11">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep11"])){ echo $error["rep11"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
						III./ Le projet
							<li> Quel est le plus important pour vous, la qualité ou la vitesse ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep12" name="rep12"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep12_r)){echo $rep12_r; }?></textarea>
					            <label for="rep12">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep12"])){ echo $error["rep12"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li>Il y aura t il intervention d'autre sous traitants comme pour les visuels par exemple ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep13" name="rep13"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep13_r)){echo $rep13_r; }?></textarea>
					            <label for="rep13">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep13"])){ echo $error["rep13"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle palette de couleurs voudriez-vous ?</li>
							<p class="entretien-p">
								Proposer d'envoyer par Email une sélection de site comme 'Coolors' ou le client pourra tranquillement choisir sa palette de couleurs.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep14" name="rep14"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep14_r)){echo $rep14_r; }?></textarea>
					            <label for="rep14">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep14"])){ echo $error["rep14"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Montrez-moi 3 sites auxquels vous aimeriez que votre site ressemble et pourquoi ?</li>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep15" name="rep15"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep15_r)){echo $rep15_r; }?></textarea>
					            <label for="rep15">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep15"])){ echo $error["rep15"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quel est le but de votre projet ?</li>
							<p class="entretien-p">
								les objectifs que celui-ci devra atteindre doivent impérativement être fixés. Attirer les donateurs, informer les adhérents, attirer de nouveaux membres, refléter les valeurs de votre association... Les possibilités sont nombreuses et vous seuls pouvez faire ce choix. Vous devez aussi savoir quelles sont les valeurs essentielles pour votre association, quelle image vous souhaitez projeter au public.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep16" name="rep16"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep16_r)){echo $rep16_r; }?></textarea>
					            <label for="rep16">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep16"])){ echo $error["rep16"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quel est le budget pour ce projet ?</li>
							<p class="entretien-p">
								Expliquer quelle est la structure et la repartition des remunérations entre programmation et creation.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep17" name="rep17"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep17_r)){echo $rep17_r; }?></textarea>
					            <label for="rep17">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep17"])){ echo $error["rep17"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Comment allez-vous effectuer votre paiement ?</li>
							<p class="entretien-p">
								Expliquer quelle est la structure et la repartition des remunérations entre programmation et creation.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep18" name="rep18"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep18_r)){echo $rep18_r; }?></textarea>
					            <label for="rep18">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep18"])){ echo $error["rep18"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quel est le délai pour ce projet ?</li>
							<p class="entretien-p">
								Expliquer normalement 6 à 8 semaines pour un site marchand complet. 3 a 4 semaines pour un site de base.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep19" name="rep19"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep19_r)){echo $rep19_r; }?></textarea>
					            <label for="rep19">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep19"])){ echo $error["rep19"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> On peut opter pour le site Vitrine, le site wordpress de base, ou le site web complet.</li>
							<p class="entretien-p">
								Si vous n'avez pas les moyens de vous offrir toutes les options, déterminez celles qui sont essentielles  et celles que vous pourrez ajouter plus tard.
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep20" name="rep20"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep20_r)){echo $rep20_r; }?></textarea>
					            <label for="rep20">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep20"])){ echo $error["rep20"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Expliquer Les étapes d'un projet. </li>
							<p class="entretien-p">
								Ces étapes doivent comprendre les éléments de la création, la structure du site et la présentation graphique des pages, puis des essais.
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Expliquer ce qu'il arrivera une fois que le site web sera terminé ?. </li>
							<p class="entretien-p">
								On ne songerait jamais à laisser la même vitrine dans un magasin durant toute l'année, dit-il. C'est la même chose pour un site web. Il faut constamment rafraîchir le contenu; cela contribue aussi à l'optimisation des moteurs de recherche. Il faut prévoir un entretien continu, l'ajout de photos et la mise à jour de l'information.
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> expliquer que le site wordpress possede un back office et qu'une formation à la livraison est possible sans frais supplémentaires. </li>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Combien d’améliorations (aller-retours) voudrez vous faire sur mon travail ? </li>
							<p class="entretien-p">
								expliquer le nombre d'AR gratuits avant facturation
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep21" name="rep21"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep21_r)){echo $rep21_r; }?></textarea>
					            <label for="rep21">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep21"])){ echo $error["rep21"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
						V./ Le détail du projet
							<li> Les raisons d'un echec de projet - Ce que nous devront a tout prix éviter!! </li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Absence de réponse après avoir reçu l’argent.</li>
									<li>Coûts supplémentaires n’ayant jamais été communiqués.</li>
									<li>Livraison du projet qui ne correspond pas du tout à ce qui était initialement prévu.</li>
									<li>Des questions qui n’arrivent qu’une fois le projet fini.</li>
									<li>Aspects importants du projet laissés de côté et résolus par quelqu’un d’autre.</li>
									<li>Réticence à fournir des accès ou des réponses claires.</li>
									<li>Retards ou délais excessifs pour les projets.</li>
								</ol>
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> A fournir par le client pour que le projet se deroule bien:</li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Comprendre, reconnaître et approuver tous les termes et conditions (oui, vous avez besoin de les lire tous)</li>
									<li>Un acompte initial pour commencer à travailler</li>
									<li>Livraison du projet qui ne correspond pas du tout à ce qui était initialement prévu.</li>
									<li>Les basics d’un site Web (logo, fichiers image, contenu).Les règles de rédaction de textes pour le web sont  différentes de celles qui s'appliquent aux textes imprimés.Il vaut la peine de confier cette tâche à un rédacteur qui a l'expérience du web</li>
									<li>Domaine existant, système de gestion de contenu et informations de connexion de l’hébergeur</li>
									<li>Existence de comptes de médias sociaux, URL et noms d’utilisateur</li>
									<li>Existence de suivi Google Analytics et Outils pour les webmasters avec  les informations de connexion</li>
									<li>Communiquer les réponses aux questions importantes de la stratégie de marketing en ligne</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep22" name="rep22"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep22_r)){echo $rep22_r; }?></textarea>
					            <label for="rep22">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep22"])){ echo $error["rep22"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Architecture du site et organisation des menus.</li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>association page-contenu</li>
									<li>Un acompte initial pour commencer à travailler</li>
									<li>contenu  non trié de la page d'accueil</li>
									<li>arborescence et navigation</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep23" name="rep23"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep23_r)){echo $rep23_r; }?></textarea>
					            <label for="rep23">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep23"])){ echo $error["rep23"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Design et chart graphique - Les maquettes</li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>en concordance avec les objectifs</li>
									<li>identité visuelle: En un coup d'oeil, vos membres, donateurs ou sympathisants doivent être en mesure de vous reconnaître.</li>
									<li>contenu  non trié de la page d'accueil</li>
									<li>les images et illustrations peuvent véhiculer un message tout aussi efficacement que le texte : il suffit de bien les choisir. Le web est un medium VISUEL</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep24" name="rep24"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep24_r)){echo $rep24_r; }?></textarea>
					            <label for="rep24">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep24"])){ echo $error["rep24"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Référencement naturel efficace.</li>
							<p class="entretizn-p">
								C'est le travail d'un concepteur professionnel!!
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Quelle est votre stratégie ?</li>
							<p class="entretien-p">
								Un « appel à l’action » fort et unique est indispensable à la réussite de votre site web. Si vous n’arrivez pas à le déterminer et à communique dessus, votre site va alors se perdre dans le trafic des autres sites web, sans vous donner de retours positifs.
							</p>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Qui est votre public cible (local, national, international)</li>
									<li>Quel est votre principale proposition pour inciter a l'adhésion?</li>
									<li>Par quel moyens faitent vous naitre l'envie d'adhérer</li>
									<li>Quelle est la procédure incitant les nouveaux visiteurs à s’inscrire ?</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep25" name="rep25"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep25_r)){echo $rep25_r; }?></textarea>
					            <label for="rep25">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep25"])){ echo $error["rep25"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> L'hébergement</li>
							<p class="entretien-p">
								Ne doit pas être négligé! proposer, 1&1, OVH, et les tenors du secteur
							</p>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Le lieu d’hébergement doit correspondre à la localisation (s) géographique de votre public cible.</li>
									<li>La configuration de l’hébergement (paramètres de base de données, paramètres du serveur, bande passante, …) doit être correctement mis en œuvre et préparée en fonction de du trafic attendu.</li>
									<li>La bande passante disponible et l’espace disque alloué à votre entreprise doivent éviter les temps de latence et problèmes de téléchargement.</li>
									<li>La réactivité et la fiabilité de votre fournisseur d’hébergement peuvent vous faire économiser beaucoup de temps et de stress quand les choses vont mal (ce qui peut parfois arriver).</li>
									<li>L’accès à vos paramètres d’hébergement (DNS, codes ftp, …) doit vous être fourni dans le cas où vous auriez besoin de faire quelque chose ou d’embaucher quelqu’un d’autre, pendant les heures d’indisponibilité.</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep26" name="rep26"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep26_r)){echo $rep26_r; }?></textarea>
					            <label for="rep26">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep26"])){ echo $error["rep26"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Ouverture : Demander au client si on peut faire plus pour lui</li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Y at-il autre chose que je peux concevoir pour vous, tels que des cartes de visite, logos, etc ?</li>
									<li>Connaissez-vous quelqu’un d’autre qui pourrait bénéficier de mes services ?</li>
									<li>Voulez-vous me faire connaître d’autres entreprises (vous appartenant)?</li>
									<li>Vous êtes intéressé par la consultation de statistiques ?</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep27" name="rep27"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep27_r)){echo $rep27_r; }?></textarea>
					            <label for="rep27">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep27"])){ echo $error["rep27"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
						VI./ SE poser les questions suivantes (et y REPONDRE!!)
							<li> Mon développement va-t-il durer dans le temps ?</li>
							<p class="entretien-p">
								La question du développeur en d’autres termes est : est-ce que mon développement fonctionnera toujours dans quelques temps (à moyen ou long terme) ?
							</p>
							<p class="entretien-p">
								On peut prendre par exemple le fait de ne pas avoir penser à intégrer un système de pagination sur une liste de données qui, au moment du développement, est assez courte.Cela peut être plus subtil comme le fait d’arranger son code pour qu’il soit simple à faire évoluer. Il faut également laisser de la place pour de futures fonctionnalités à ajouter. Par exemple, penser à intégrer les mécanismes pour que tous les textes en dur soit traduisibles sans avoir à éditer le code permettra, dans le futur, que la fonctionnalité fonctionne avec un site multi-langue.
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Est-ce que j’ai pensé à tous les cas de figure ? Est-ce que je me suis mis à la place de mon client ?</li>
							<p class="entretien-p">
								Les développeurs apprennent dès leurs études qu’il faut se mettre à la place du client (ou de l’utilisateur) etanticiper les erreurs qui pourraient être faites lors de l’utilisation de la fonctionnalité développée.Le clic sur un bouton de soumission, alors que tous les champs ne sont pas remplis, est l’exemple type auquel tout développeur est vite confronté. Quelles sont les réponses, les éléments à mettre en place pour être sûr que la fonctionnalité est complète? Ce sont tous ces points auquel le développeur doit réfléchir en amont du développement pour réussir et être efficace dans son travail.
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Que vais-je devoir mettre en place pour assurer la sécurité des informations traitées ?</li>
							<p class="entretien-p">
								<ol class="entretien-ol">
									<li>Protection des données personnelles. Ne pas sécuriser une url intermédiaire peut permettre à une personne mal attentionnée de récupérer les informations personnelles d’un client ou d’un membre du site,</li>
									<li>Sécurité du code. Protéger son code pour éviter que des personnes mal attentionnées analysent le code pour y trouver une faille,</li>
									<li>Sécurité de la sauvegarde des données : permettre la soumission d’un formulaire ou d’une action sans contrôle derrière peut engendrer des erreurs en base de données qui auront un impact sur le reste de la fonctionnalité.</li>
									<li>Une méthode de développement permettant d’éviter ces erreurs est l’utilisation de tests unitaires.</li>
								</ol>
							</p>
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep28" name="rep28"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep28_r)){echo $rep28_r; }?></textarea>
					            <label for="rep28">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep28"])){ echo $error["rep28"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							<li> Est-ce que j’optimise bien le temps que je passe sur mon développement ?</li>
							<p class="entretien-p">
								C’est au développeur de savoir juger ce qui doit être fait et ce qui ne peut pas être fait dans les délais. En effet, le développeur se doit d’être professionnel vis à vis du client et cela ne doit pas avoir d’impact sur la livraison du développement au client.
							</p>
							<p class="entretien-p">
								Si toutefois une fonctionnalité parait indispensable au développement mais que son développement impacte les délais de livraison, en parler immédiatement commanditaire pour trouver une solution.
							</p>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							SUIVI 1
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep29" name="rep29"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep29_r)){echo $rep29_r; }?></textarea>
					            <label for="rep29">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep29"])){ echo $error["rep29"]; } ?></span>
					        </div>
						</ul>
					</div>
				</div>
				<div class="form1Container">
					<div class="question" style="color:white;">
						<ul class="entretien_ul">
							SUIVI 2
							<div class="inputGroup">
					        	<textarea class="textareaEffect1" id="rep30" name="rep30"  placeholder="Note" cols="30" rows="10"><?php if(isset($rep30_r)){echo $rep30_r; }?></textarea>
					            <label for="rep30">Note</label>
					            <span class="fieldError"><?php if(isset($error["rep30"])){ echo $error["rep30"]; } ?></span>
					        </div>
						</ul>
					</div>
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