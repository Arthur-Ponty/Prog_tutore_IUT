<div class="diviut"">

<h1>Modifier/Supprimer un module</h1>
<hr/>

<table align="center">

    <form action="page_admin_module.php" method="post">

        <tr>
            <td><label name="role" id="role">Module</label></td>
            <td>
                <select name="role[]">
                    <?php

                        $request = "SELECT code, libelleMod FROM module where nature = 'MO'";
                        $reponse = $bdd->query($request);

                        while ($id = $reponse->fetch())
                        {
                            echo "<option value=\"$id[0]\"> $id[1]";
                        }
                           
                        $reponse ->closeCursor();

                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <input type="submit" name="modif" value="Choisir">
            </td>
        </tr>

    </form>

</table>

<?php

    if(isset($_POST['modif']) )
    {
        if($_POST['modif'] )
        {
            $code = "";

            if(isset($_POST['role']))
            {
                $code = $_POST['role'];
                $code = $code[0];
            }
            if($code != -1 )
            {
                $request = "SELECT libelleMod, droit, couleur FROM module WHERE code='".$code."' ";
                $reponse = $bdd->query($request);
                $tuple   = $reponse->fetch();

                if( $tuple[1] == "E")
                    $enseignant = "checked";
                else
                    $enseignant = "";

                if($tuple[1] == "T")
                    $tuteur = "checked";
                else
                    $tuteur = "";

                $tpl = $twig->loadTemplate( "modifModule.tpl" );
                echo $tpl->render( array("code" => $code, "libelleMod" => $tuple[0], "droit" => $tuple[1], "couleur" => $tuple[2], "enseignant" => $enseignant, "tuteur" => $tuteur, "id_cache" => $code) );
            }
            header('Refresh: 0.01;URL=page_admin_module.php');
        }
    }

    if(isset($_POST['valider_modif']) )
    {
        if($_POST['valider_modif']  )
        {
            if(isset($_POST['code'], $_POST['libelleMod'], $_POST['droit'], $_POST['color']))
            {
                if(!empty($_POST['code']) AND !empty($_POST['libelleMod']) AND !empty($_POST['droit']) AND !empty($_POST['color']))
                {

                    $new_code       = htmlspecialchars($_POST['code'      ]);
                    $new_libelleMod = htmlspecialchars($_POST['libelleMod']);
                    $new_droit      = $_POST['droit'     ];
                    $new_color      = $_POST['color'     ];
                    $id_ref         = htmlspecialchars($_POST['id_cache']);


                    $update = $bdd->prepare("UPDATE module SET code=?, libelleMod=?, droit=?,
                                             couleur=? where code =? ");
                    
                    if(!$update->execute(array($new_code, $new_libelleMod, $new_droit[0], $new_color, $id_ref)))
                        echo '<center><p class="erreur">Erreur le module peut pas étre modifier !!</p></center>';
                    else
                        header('Refresh: 0.01;URL=page_admin_module.php');
                }
            }
        }
    }

    if(isset($_POST['suppr']) )
    {
        if($_POST['suppr']  )
        {

            if(isset($_POST['code']) AND !empty($_POST['code']))
            {
                $id = htmlspecialchars($_POST['code']);

                $suprression = $bdd->prepare("DELETE  FROM module WHERE code=?");
                $suprression->execute(array($id));
            }
            header('Refresh: 0.01;URL=page_admin_module.php');
        }
    }
?>