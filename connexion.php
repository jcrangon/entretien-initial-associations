<?php
/* connexion.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Connexion";

$log->info("Retour dans connexion.php", "");

if(userISConnecte($fingerprint)){
	header("location:./accueil.php");
}

if($POST_Data_Avail){
	if(isset($_POST["pseudo"]) && !empty($_POST["pseudo"]) && isset($_POST["mdp"]) && !empty($_POST["mdp"])){
		extract($_POST);
		$mdp=$mdp.$pseudo;
		
		$resultat=$pdo->prepare("SELECT * FROM user WHERE pseudo=:pseudo");
		$resultat->bindParam(":pseudo",$pseudo,PDO::PARAM_STR);
		try{
			$resultat->execute();
		}
		catch(PDOException $e){
			$log->error("Erreur PDO",$e->getMessage());
			die($errCodes["resultat_execute_fails"]);
		}
		if($resultat->rowCount()!==0){
			$user=$resultat->fetch(PDO::FETCH_ASSOC);
			$log->info("recuperation de l'utilisateur en BDD",$user);
			$log->info("Verification du mot de passe","");
			if(Dcrypt($mdp,$user["pwd"])){
				$_SESSION[$fingerprint]["user"]=$user;
				unset($_SESSION[$fingerprint]["user"]["pwd"]);
				$log->info("Succes!! redirection vers accueil.php","");
				header("location:./accueil.php");
				exit();
			}
			else{
				$error["pwd"]="<div class='alert alert-danger text-center d-block'>Identifiant/mot de passe incorrect!<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></div>";
			}
		}
	}
}

$pseudo_r=(isset($pseudo))?$pseudo:"";
$mdp_r="";






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
<div class="container-fluid login-container">
    <div class="row">
    	<div class="col-md-4"></div>
        <div class="col-md-4 login-form-1 mt-3">
            <h3>Connexion</h3>
            <?=(isset($error["pwd"]))?$error["pwd"]:""; ?>
            <form action="connexion.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="ident" name="pseudo" placeholder="Votre Pseudo *" value="<?=$pseudo_r?>" required/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="" name="mdp" placeholder="Mot de Passe *" value="<?=$mdp_r?>" required/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" value="Login" />
                </div>
                <div class="form-group">
                    <a href="#" class="ForgetPwd">Mot de passe oublié?</a>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

<?php
require_once("./include/chunks/footer.inc.php");
?>