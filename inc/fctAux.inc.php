<?php
	function enTete($titre, $style)
	{
		echo "<html>\n";
		echo "\t<head>\n";
		echo "\t\t<meta charset=\"UTF-8\">";
		echo "\t\t<title>$titre</title>\n";
		echo "\t\t<link rel=\"stylesheet\" href=\"../css/$style.css\" type=\"text/css\">";
		echo "\t</head>\n";
		echo "\t<body>\n";
	}

	function pied()
	{
		echo "\t<footer>";
		echo "\t\t";
		echo "\t</footer>";
		echo "\t</body>";
		echo "</html>";
	}

	function isMotDePasseOk($login, $mdp)
    {
        $bdd = connexionBdd();

        $request = "SELECT mdp FROM utilisateur WHERE id_utilisateur='".$login."'";
        $reponse = $bdd->query($request);
		
        $mdp_bdd = $reponse->fetch()[0];

        if(password_verify($mdp, $mdp_bdd ) OR $mdp === $mdp_bdd)
        {
            return 1;
        }
    }
	
	function isPasswordStrong($mdp)
	{
		$mdp   = $_POST['mdp'  ];
		
		$majus = 0; $minus = 0; $special = 0;
		for ($i = 0, $j = strlen($mdp); $i < $j; $i++) 
		{ 
			$c = substr($mdp,$i,1); 
			if (preg_match('/^[[:upper:]]$/',$c)) 
			{ 
				$majus++; 
			} 
			elseif (preg_match('/^[[:lower:]]$/',$c)) 
			{ 
				$minus++;
			} 
			elseif (preg_match('/^[[:digit:]]$/',$c)) {} 
			else
			{
				$special++;
			}
		}
		
		//Cette condition permet de vérifier la forme du mdp			
		if(!$majus>=2 || !$minus>=2 || !$special>=2 || strlen($mdp) < 8) 
		{
			echo '<center><p class="erreur">Le mot de passe doit contenir 8 caractères avec au moins 2 minuscules, 2 majuscules et 2 caractères spéciaux</p></center>';
			return false;
		}
		return true;
	}

	function recupMois()
	{
		$mois = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

		date_default_timezone_set("Europe/Paris"); // fuseau horaire

		$date = date('Y-m-d'); // Date du jour
		setlocale(LC_TIME, "fr_FR", "French");

		return $mois[$_SESSION['mois']]."  ".$_SESSION['annee'];
	}

	function recupMoisChiffre()
	{
		date_default_timezone_set("Europe/Paris"); // fuseau horaire

		$date = date('Y-m-d'); // Date du jour
		setlocale(LC_TIME, "fr_FR", "French");
		return  strftime("%m", strtotime($date));
	}

	function moisCourantOk($dateSeance)
	{
		$moisSeance = strftime("%m", strtotime($dateSeance));

		return ( $_SESSION['mois'] == $moisSeance );
	}

	function anneeCouranteOk($dateSeance)
	{
		$anneeSeance = strftime("%Y", strtotime($dateSeance));

		return ( $_SESSION['annee'] == $anneeSeance );
	}

	function recupSemaine($date)
	{
		date_default_timezone_set("Europe/Paris"); // fuseau horaire
		
		return strftime("%U", strtotime($date));
	}

	function changerDate($date)
	{
		date_default_timezone_set("Europe/Paris"); // fuseau horaire

		setlocale(LC_TIME, "fr_FR", "French");
		return strftime("%a %x", strtotime($date));
	}

	function recupSeance($request)
	{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		require_once( "../Twig/lib/Twig/Autoloader.php" );
		Twig_Autoloader::register();

		$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

		$tpl_tab_bord_seance     = $twig->loadTemplate( "tab_bord_seance.tpl"    );
		$tpl_tab_bord_evenement  = $twig->loadTemplate( "tab_bord_evenement.tpl" );

		$bdd = connexionBdd();
		$reponse = $bdd->query($request);

		while ($seance = $reponse->fetch())
		{
			$request  = "SELECT * FROM evenement WHERE id_seance='".$seance[0]."'";
			$reponse1 = $bdd->query($request);

			$utilisateur = recupNomUtilisateur($seance[5])."  ".recupPrenomUtilisateur($seance[5]);
			$couleur     = recupCouleurModule($seance[1]);
			$libelleMod  = recupLibelleMod($seance[1]);

			$tab_parametres = array(recupSemaine($seance[2]), $libelleMod, changerDate($seance[2]), $seance[3], $seance[4], $utilisateur, "", "", "");
			echo $tpl_tab_bord_seance ->render(array("items" => $tab_parametres, "couleur_module" => $couleur, "vu" => "checked"));

		    while($evenement = $reponse1->fetch())
		    {
		    	/* Evènement */
				$request = "SELECT * FROM lienPieceJointe WHERE id_even='".$evenement[0]."'";
				$reponse2 = $bdd->query($request);

				$tab_fichier = array();
				while($piece_jointe = $reponse2->fetch())
				{
					$tab_fichier[] = $piece_jointe[2];
				}

		    	/* Evènement */
				$tab_parametres = array($evenement[2], $evenement[1], $evenement[3], changerDate($evenement[4]));
				echo $tpl_tab_bord_evenement ->render(array("items" => $tab_parametres, "fichier" => $tab_fichier));
		    }
		}

		echo '</table>';
		echo '</div>';
	}

	function recupEvenement($request)
	{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		require_once( "../Twig/lib/Twig/Autoloader.php" );
		Twig_Autoloader::register();

		$twig = new Twig_Environment( new Twig_Loader_Filesystem("../tpl"));

		$tpl_tab_bord_seance     = $twig->loadTemplate( "tab_bord_seance.tpl"    );
		$tpl_tab_bord_evenement  = $twig->loadTemplate( "tab_bord_evenement.tpl" );

		$bdd = connexionBdd();
		$reponse = $bdd->query($request);

		while ($evenement = $reponse->fetch())
		{
			$request  = "SELECT * FROM seance WHERE id_seance='".$evenement[5]."'";
			$reponse1 = $bdd->query($request);

			$seance = $reponse1->fetch();
			$utilisateur = recupNomUtilisateur($seance[5])."  ".recupPrenomUtilisateur($seance[5]);
			$couleur     = recupCouleurModule($seance[1]);
			$libelleMod  = recupLibelleMod($seance[1]);

			$tab_parametres = array(recupSemaine($seance[2]), $libelleMod, changerDate($seance[2]), $seance[3], $seance[4], $utilisateur, "", "", "");
			echo $tpl_tab_bord_seance ->render(array("items" => $tab_parametres, "couleur_module" => $couleur, "vu" => "checked"));

	    	/* Evènement */
	    	$request = "SELECT * FROM lienPieceJointe WHERE id_even='".$evenement[0]."'";
			$reponse2 = $bdd->query($request);

			$tab_fichier = array();
			while($piece_jointe = $reponse2->fetch())
			{
				$tab_fichier[] = $piece_jointe[2];
			}

	    	/* Evènement */
			$tab_parametres = array($evenement[2], $evenement[1], $evenement[3], changerDate($evenement[4]));
			echo $tpl_tab_bord_evenement ->render(array("items" => $tab_parametres, "fichier" => $tab_fichier));
		}

		echo '</table>';
		echo '</div>';
	}
?>