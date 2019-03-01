<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">

<title>Entretien<?=(isset($page))?" | ".$page:""?></title>

<meta name="description" content="Fiche d'entretien initial avant conception d'un site web associatif">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="Keywords" content="">
<meta name="Subject" content="">
<meta name="Copyright" content="Jean-Christophe Rangon">
<meta name="Author" content="Jean-Christophe Rangon">
<meta name="Publisher" content="">
<meta name="Reply-To" content="jc.rangon@gmail.com">
<meta name="Revisit-After" content="30 days">
<meta name="expires" content="never">
<meta name="Robots" content="all">
<meta name="Rating" content="general">
<meta name="Distribution" content="global">
<meta name="Geography" content="Puteaux, France, 92800">

<!-- FB + LinkedIn -->
<meta name="og:type" content="website">
<meta name="og:title" content="Template bootstrap">
<meta name="og:image" content="./image/logo-big.png">
<meta name="og:description" content="">
<meta name="og:url" content="https://">

<!-- Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@">
<meta name="twitter:title" content="Template bootstrap">
<meta name="twitter:image" content="./image/logo-big.png">
<meta name="twitter:description" content="">
<meta name="twitter:url" content="https://">


<!--        Font awsome-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<!--        Bootstrap CSS-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<!--        Page Transitions-->
<link rel="stylesheet" href="./pagetransitions/page-transitions.css">

<!--        Page CSS-->
<link rel="stylesheet" href="./css/style.css">

</head>

<body class="<?=$jcr_page_transition;?>">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<noscript>
	<p><strong>Attention, cette page Web nécessite que JavaScript soit activé !</strong></p>
	<p>JavaScript est un langage de programmation couramment utilisé pour créer des effets intéractifs dans les navigateurs Web.</p>
	<p>Malheureusement, il est désactivé dans votre navigateur. Veuillez l'activer pour afficher cette page.</p>
	<p><a href="https://goo.gl/koeeaJ">Comment activer JavaScript ?</a></p>   
</noscript>

<!--Site Monitor-->
<div class="link-to-monitor">
	<a href="./class/monitor.php" target="_blank">Monitor</a>
</div>

<!--Page Container-->
<div class="container-fluid bg-white bkg">
	<div class="row">
		<div class="col-sm-12 col-xl-10 p-0 m-auto docBkg shadow-lg border-left border-right border-light">
			
<!--            Header -->
			<header>

			</header>
			
<!--            Navbar -->
			<div class="sticky">
				<nav class="navbar navbar-dark bg-light justify-content-end shadow-lg">
				    <ul class="nav nav-pills justify-content-end">
				       <li class="nav-item">
				           <a class="nav-link <?=($page=="Liste")?"active":""?>" href="./accueil.php">Liste</a>
				       </li>
				       <?php if(userISConnecte($fingerprint)):?>
				       <li class="nav-item">
				           <a class="nav-link <?=(isset($_SESSION[$fingerprint]["nouvelle_fiche"]) && !isset($_SESSION[$fingerprint]["nouvelle_fiche"]["liste_entretien"]) && $page!=="Liste" && !isset($_GET["id"]))?"active":""?>" href="./new-entretien.php">Nouveau</a>
				       </li>
				       <li class="nav-item">
				           <a class="nav-link" href="./deconnexion.php">déconnexion</a>
				       </li>
				       <?php else:?>
				       <li class="nav-item">
				           <a class="nav-link" href="./connexion.php">connexion</a>
				       </li>
				       <?php endif;?>
				    </ul>
				</nav>
		
	<!--            BreadCrumb -->                
				<nav class="border-bottom border-light shadow bgblkl" aria-label="breadcrumb" role="navigation">
					<ol class="breadcrumb">
						<?php if(isset($page) && $page=="Liste"):?>
							<li class="breadcrumb-item active"><a href="./accueil.php">Liste</a></li>
						<?php endif; ?>
						
						<?php if(isset($page) && $page=="Connexion"):?>
							<li class="breadcrumb-item"><a href="./accueil.php">Liste</a></li>
							<li class="breadcrumb-item active">Connexion</li>
						<?php endif; ?>
						
						<?php if(isset($page) && $page=="Contact"):?>
							<li class="breadcrumb-item"><a href="./accueil.php">Liste</a></li>
							<?php if(isset($_SESSION[$fingerprint]["nouvelle_fiche"])):?>
							<li class="breadcrumb-item active">Nouveau Contact</li>
							<?php else: ?>
							<li class="breadcrumb-item active">Fiche Contact</li>
							<?php endif; ?>
						<?php endif; ?>
						
						<?php if(isset($page) && $page=="Association"):?>
							<li class="breadcrumb-item"><a href="./accueil.php">Liste</a></li>
							<li class="breadcrumb-item"><a href="./contact.php">Nouveau Contact</a></li>
							<li class="breadcrumb-item active">Association</li>
						<?php endif; ?>
						
						<?php if(isset($page) && $page=="Entretien"):?>
							<li class="breadcrumb-item"><a href="./accueil.php">Liste</a></li>
							<li class="breadcrumb-item"><a href="./contact.php">Nouveau Contact</a></li>
							<li class="breadcrumb-item"><a href="./association.php">Association</a></li>
							<li class="breadcrumb-item active">Entretien</li>
						<?php endif; ?>
	
					</ol>
				</nav>
			</div>
<!--            Main -->
			<main>
				