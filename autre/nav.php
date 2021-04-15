<?php session_start()?>

<center>Bienvenue <?php echo $_SESSION['prenom_utilisateur']."  "; echo $_SESSION['nom_utilisateur'] ?></center>
<nav>
	<ul>

		<?php
			if (strpos($_SESSION['role_utilisateur'], "A")!==false)
			{
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_utilisateur.php\">Admin utilisateur</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_groupe.php\">Admin groupe</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_module.php\">Admin module</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_lier_module.php\">Admin lier module</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_lier_groupe.php\">Admin lier groupe</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_AddSupprEvent.php\">Admin type évenement</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_AddSupprSeance.php\">Admin type séance</a></li>\n";
				echo "<li class=\"red\"><a href=\"../page_admin/page_admin_parametre.php\">Paramètres</a></li>\n";
			}

			if (strpos($_SESSION['role_utilisateur'], "E")!==false || strpos($_SESSION['role_utilisateur'], "T")!==false)
			{
				echo "<li><a href=\"../autre/nouveau_mdp.php\">Modifier mon mot de passe</a></li>\n";
				echo "<li><a href=\"../affichage/mes_seances.php\">Mes séances</a></li>\n";
				echo "<li><a href=\"../affichage/tableau_de_bord.php\">Tableau de bord</a></li>\n";
				echo "<li><a href=\"../ajouter/ajouter_seance.php\">Ajouter une séance</a></li>\n";
				echo "<li><a href=\"../affichage/etats_seance.php\">Chercher une séance</a></li>\n";
			}
		?>


	</ul>
</nav>

<form action="../autre/connexion.php" method="post">
	<input type="submit" name="deconnexion" value="Déconnexion" class="navDeco">
</form>