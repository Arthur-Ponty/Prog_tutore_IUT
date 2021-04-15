<?php

	function connexionBdd()
	{
		// On se connecte a la base de données
		try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=projet tut;charset=utf8', 'root', 'root');
		}
		catch(Exception $e)
		{
			// En cas d'erreur, on affiche un message et on arrête tout
			die('Erreur : '.$e->getMessage());
		}

		return $bdd;
	}

	function recupNomUtilisateur($login)
	{
		$bdd = connexionBdd();

		$request = "SELECT nom FROM utilisateur WHERE id_utilisateur='".$login."'";
		$reponse = $bdd->query($request);
		
		return $reponse->fetch()[0];
	}

	function recupPrenomUtilisateur($login)
	{
		$bdd = connexionBdd();

		$request = "SELECT prenom FROM utilisateur WHERE id_utilisateur='".$login."'";
		$reponse = $bdd->query($request);
		
		return $reponse->fetch()[0];
	}

	function recupCouleurModule($code)
	{
		$bdd = connexionBdd();

		$request = "SELECT couleur FROM module WHERE code='".$code."'";
		$reponse = $bdd->query($request);

		return $reponse->fetch()[0];
	}

	function recupLibelleMod($code)
	{
		$bdd = connexionBdd();

		$request = "SELECT libelleMod FROM module WHERE code='".$code."'";
		$reponse = $bdd->query($request);

		return $reponse->fetch()[0];
	}

	function ajouterFichier($idEven, $fichier)
	{
		$bdd = connexionBdd();

		$insertion = $bdd->prepare("INSERT INTO lienPieceJointe(id_even, chemin_piece_jointe) VALUES (?, ?)");
		$insertion->execute(array($idEven, $fichier));
	}
?>