<?php
     include "../inc/DB.inc.php";
     $bdd = connexionBdd();

     //On s'occupe de l'import du dossier
     $dossier = '../upload/';
     $fichier = basename($_FILES['fichier']['name']);
     $taille_maxi = 100000;
     $taille = filesize($_FILES['fichier']['tmp_name']);
     
     if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
     {
          //On formate le nom du fichier ici...
          $fichier = strtr($fichier, 
                     'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                     'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
          $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

          $request = "SELECT libelleMod FROM module WHERE nature='NBPJ'";
          $reponse = $bdd->query($request)->fetch();

          $request  = "SELECT COUNT(*) FROM lienPieceJointe WHERE id_even='".$_POST['evenement'][0]."'";
          $reponse1 = $bdd->query($request)->fetch();

          if($reponse[0] > $reponse1[0])
          {
               if(move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier.$fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
               {
                    ajouterFichier($_POST['evenement'][0] ,$fichier);
                    header("location: ajouter_evenement.php");
               }
               else //Sinon (la fonction renvoie FALSE).
               {
                    $erreur = "Echec de l'upload !";
                    header("location: ajouter_evenement.php?erreur=$erreur");
               }
          }
          else
          {
               $erreur = "Limite de pièce jointe atteinte";
               header("location: ajouter_evenement.php?erreur=$erreur");
          }
     }
?>