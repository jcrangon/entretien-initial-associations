<?php
/* accueil.php */
require_once('include/init/init.inc.php');

//**************  MAIN CODE
$page="Liste";

$log->info("Retour dans accueil.php", "");

if(userISConnecte($fingerprint)){
	$resultat=$pdo->prepare("SELECT le.id_entretien, c.id_contact, a.id_assos, le.date, a.nom_assos,c.nom_contact, c.prenom, c.telmobile AS contactphone, c.email AS contactemail, de.id_details_entretien FROM liste_entretiens AS le INNER JOIN contacts AS c ON le.id_contact=c.id_contact INNER JOIN associations AS a ON c.id_contact=a.id_contact INNER JOIN details_entretiens AS de ON a.id_assos=de.id_assos ORDER BY le.date DESC");
	try{
		$resultat->execute();
	}
	catch(PDOException $e){
		$log->error("Erreur PDO",$e->getMessage());
		die($errCodes["resultat_execute_fails"]);
	}
	
	if($resultat->rowCount()!=0){
		$liste=$resultat->fetchAll(PDO::FETCH_ASSOC);
		$table_body="";
		foreach($liste as $item){
			$table_body.="<tr>";
			$table_body.="<th scope='row'>".$item["id_entretien"]."</th>";
			$table_body.="<td><span class='badge badge-success' style='font-size:1rem; color:#000;'>".date("d-m-Y",strtotime($item["date"]))."</span></td>";
			$table_body.="<td><a href='./association.php?id=".$item["id_assos"]."' title='Fiche'><span class='badge badge-warning' style='font-size:1rem; color:#000;'>".$item["nom_assos"]."</span></a></td>";
			$table_body.="<td><a href='./contact.php?id=".$item["id_contact"]."' title='Fiche'><span style='font-size:1rem; color:orange;'>".$item["nom_contact"]." ".$item["prenom"]."</a></span></td>";
			$table_body.="<td><a href='tel:".$item["contactphone"]."' title='lancer un appel'><span style='font-size:1rem; color:orange;'>".$item["contactphone"]."</span></td>";
			$table_body.="<td><a href='mailto:".$item["contactemail"]."' title='mailto'><span style='font-size:1rem; color:orange;'>".$item["contactemail"]."</span></td>";
			$table_body.="<td><a class='btn btn-outline-danger' href='./action.php?action=2&id=".$item["id_entretien"]."' title='Supprimer' style='vertical-align:top;'><i class='fas fa-trash fatype-cat'></i></a></td>";
			$table_body.="<td><a class='btn btn-outline-primary' href='./entretien.php?&id=".$item["id_details_entretien"]."' title='Voir' style='vertical-align:top;'><i class='far fa-eye'></i></a></td>";
			$table_body.="</tr>";	
		}
	}
	else{
		$notice.="<div class='alert alert-danger text-center d-block w-75 m-auto'>Aucun entretien trouvé.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times</span></button></div>";
	}
}

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
<?php if(!userISConnecte($fingerprint)):?>
<section class="container justify-content-center align-items-center mt-5">
	<div>
		<a class="btn btn-warning d-block w-50 m-auto shadow-lg p-3" href="./connexion.php">Connexion</a>
	</div>
</section>


<?php else: ?>
<?=$notice;?>
<section class="container flex-column justify-content-center align-items-center mt-5">
	<div class="row">
		<div class="col-xs-12 col-sm-8 offset-sm-2">
			<a class="btn btn-primary d-block m-auto shadow-lg p-3" href="./new-entretien.php">Démarrer un nouveau projet</a>
		</div>
	</div>
<?php if(isset($liste)): ?>
	<div class="ovf mt-4">
		<h2 class="text-center">Liste des Entretiens</h2>
		<table class="table table-borderless" id="interviewliste">
			<thead>
				<tr>
				<th scope="col">Id</th>
				<th scope="col">Date</th>
				<th scope="col">Association</th>
				<th scope="col">Contact</th>
				<th scope="col">Téléphone</th>
				<th scope="col">Email</th>
				<th scope="col">Supprimer</th>
				<th scope="col">Voir</th>
				</tr>
			</thead>
			<tbody>
				<?=$table_body;?>
			</tbody>
		</table>
	</div>
<?php endif;?>
</section>


<?php endif; ?>


<?php
require_once("./include/chunks/footer.inc.php");
?>