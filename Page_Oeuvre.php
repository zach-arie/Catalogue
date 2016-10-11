<?php
    session_start();
	include_once('fctGeneral.function.php');
	include("General/BDD/Connexion.php");
    $Oeu_F_Titre="";$Oeu_F_Compo=0;$Oeu_F_Date="";$Oeu_F_Opus=0;
	//Lecture et affectation des cookies pour les étapes suivantes
	if (isset($_GET['arg1'])=='INI'){
		// on vide tous les COOKIES
		if (isset($_COOKIES['Oeu_F_Titre'])) { unset($_COOKIES['Oeu_F_Titre']);}
		if (isset($_COOKIES['Oeu_F_Compo'])) { unset($_COOKIES['Oeu_F_Compo']);}
		if (isset($_COOKIES['Oeu_F_Date'])) { unset($_COOKIES['Oeu_F_Date']);}
		if (isset($_COOKIES['Oeu_F_Opus'])) { unset($_COOKIES['Oeu_F_Opus']);}
		if (isset($_COOKIES['Oeu_T_Cmp'])) { unset($_COOKIES['Oeu_T_Cmp']);}
		if (isset($_COOKIES['Oeu_T_Titre'])) { unset($_COOKIES['Oeu_T_Titre']);}
		if (isset($_COOKIES['Oeu_T_Opus'])) { unset($_COOKIES['Oeu_T_Opus']);}
		if (isset($_COOKIES['Oeu_T_Date'])) { unset($_COOKIES['Oeu_T_Date']);}
	} elseif (isset($_GET['arg1'])=='FLT'){
		if (isset($_POST['Filtre_Titre'])){
			if (strlen($_POST['Filtre_Titre'])>0){
				setcookie('Oeu_F_Titre',$_POST['Filtre_Titre'], time() + 24*3600);
				$Oeu_F_Titre=$_POST['Filtre_Titre'];
			} 
		} else {
			if (isset($_COOKIE['Oeu_F_Titre'])){
				$Oeu_F_Titre=$_COOKIE['Oeu_F_Titre'];
			}
		}
		if (isset($_POST['Filtre_Compositeur'])){
			if (strlen($_POST['Filtre_Compositeur'])>0){
				setcookie('Oeu_F_Compo',$_POST['Filtre_Compositeur'], time() + 24*3600);
				$Oeu_F_Titre=$_POST['Filtre_Compositeur'];
			}
		} else {
			if (isset($_COOKIE['Oeu_F_Compo'])){
				$Oeu_F_Titre=$_COOKIE['Oeu_F_Compo'];
			}
		}
		if (isset($_POST['Filtre_DateCompo'])){
			if (strlen($_POST['Filtre_DateCompo'])>0){
				setcookie('Oeu_F_Date',$_POST['Filtre_DateCompo'], time() + 24*3600);
				$Oeu_F_Date=$_POST['Filtre_DateCompo'];
			}
		} else {
			if (isset($_COOKIE['Oeu_F_Date'])){
				$Oeu_F_Date=$_COOKIE['Oeu_F_Date'];
			}
		}
		if (isset($_POST['Filtre_Opus'])){
			if (strlen($_POST['Filtre_Opus'])>0){
				setcookie('Oeu_F_Opus',$_POST['Filtre_Opus'], time() + 24*3600);
				$Oeu_F_Date=$_POST['Filtre_Opus'];
			}
		} else {
			if (isset($_COOKIE['Oeu_F_Opus'])){
				$Oeu_F_Date=$_COOKIE['Oeu_F_Opus'];
			}
		}
	} elseif (isset($_GET['arg1'])=='TRI'){
		if (isset($_POST['Tri_Compo'])){
			setcookie('Oeu_T_Cmp',$_POST['Tri_Compo'], time() + 24*3600);
		} else {
			if (isset($_COOKIE['Oeu_T_Cmp'])){
				unset($_COOKIE['Oeu_T_Cmp']);
			}
		}
		if (isset($_POST['Tri_Titre'])){
			setcookie('Oeu_T_Titre',$_POST['Tri_Titre'], time() + 24*3600);
		} else {
			if (isset($_COOKIE['Oeu_T_Titre'])){
				unset($_COOKIE['Oeu_T_Titre']);
			}
		}
		if (isset($_POST['Tri_Opus'])){
			setcookie('Oeu_T_Opus',$_POST['Tri_Opus'], time() + 24*3600);
		} else {
			if (isset($_COOKIE['Oeu_T_Opus'])){
				unset($_COOKIE['Oeu_T_Opus']);
			}
		}
		if (isset($_POST['Tri_Date'])){
			setcookie('Oeu_T_Date',$_POST['Tri_Date'], time() + 24*3600);
		} else {
			if (isset($_COOKIE['Oeu_T_Date'])){
				unset($_COOKIE['Oeu_T_Date']);
			}
		}
	}
	// Définition des clauses
	// WHERE
        $ClauseWhere="";
        $ClauseOrder="";
	if (Oeu_F_Titre!=""){
		$ClauseWhere="Oeu_Titre LIKE '".$Oeu_F_Titre."%'";
	}
	if ($Oeu_F_Compo!=0){
		if (strlen($ClauseWhere)>0) {
			$ClauseWhere=$ClauseWhere." AND Compositeur=" .$Oeu_F_Compo;
		} else {
			$ClauseWhere="Compositeur=" .$Oeu_F_Compo;
		}
	}
	if ($Oeu_F_Date!=""){
		if (strlen($ClauseWhere)>0) {
			$ClauseWhere=$ClauseWhere." AND Oeu_Annee>=" .$Oeu_F_Date;
		} else {
			$ClauseWhere="Oeu_Annee>=" .$Oeu_F_Date;
		}
	}
	if ($Oeu_F_Opus!=0){
		if (strlen($ClauseWhere)>0) {
			$ClauseWhere=$ClauseWhere." AND Oeu_Opus>=" .$Oeu_F_Opus;
		} else {
			$ClauseWhere="Oeu_Opus>=" .$Oeu_F_Opus;
		}
	}	
	// ORDER BY
	if (isset($_COOKIE['Oeu_T_Cmp'])){
		if ($_COOKIE['Oeu_T_Cmp']=='D'){
			$ClauseOrder= "Compositeur DESC";
		} elseif ($_COOKIE['Oeu_T_Cmp']=='A') {
			$ClauseOrder="Compositeur ASC";	
		}
	}
	if (isset($_COOKIE['Oeu_T_Titre'])){
		$TmpClauseOrder="";
		if ($_COOKIE['Oeu_T_Titre']=='D'){
			$TmpClauseOrder= "Oeu_Titre DESC";
		} elseif ($_COOKIE['Oeu_T_Titre']=='A') {
			$TmpClauseOrder="Oeu_Titre ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	if (isset($_COOKIE['Oeu_T_Opus'])){
		$TmpClauseOrder="";
		if ($_COOKIE['Oeu_T_Opus']=='D'){
			$TmpClauseOrder= "Oeu_Opus DESC";
		} elseif ($_COOKIE['Oeu_T_Opus']=='A') {
			$TmpClauseOrder="Oeu_Opus ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	if (isset($_COOKIE['Oeu_T_Date'])){
		$TmpClauseOrder="";
		if ($_COOKIE['Oeu_T_Date']=='D'){
			$TmpClauseOrder= "Oeu_Annee DESC";
		} elseif ($_COOKIE['Oeu_T_Date']=='A') {
			$TmpClauseOrder="Oeu_Annee ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	
	// la page est déstinée à faire afficher le liste des oeuvres, 
	// plus ou moins complete avec des ordres de tris spécifiés aux cours des consultations.
        
        $PageEnCours=1;
        if (isset($_COOKIE['Oeu_NbLignes'])){$NbLigneTotal=$_COOKIE['Oeu_NbLignes'];}else{$NbLigneTotal=1;}
	if (isset($_GET['Page'])){
            // on est en cours de parcours
            setcookie('Oeu_Page',$_GET['Page'], time() + 24*3600);
            $PageEnCours=$_GET['Page'];
            $RequeteOeuvre="SELECT * FROM vOeuvre";
            if (strlen($ClauseWhere)>0) {
                    $RequeteOeuvre=$RequeteOeuvre." WHERE ".$ClauseWhere;
            }
            if (strlen($ClauseOrder)>0) {
                    $RequeteOeuvre=$RequeteOeuvre." ORDER BY ".$ClauseOrder;
            } else {
                    $RequeteOeuvre=$RequeteOeuvre." ORDER BY Oeu_Titre";
            }
         } else {
            // c'est la première fois que l'on vient sur la page on va calculer le nombre total d'enreg
            // setcookie('Zach_Oeu'.$AvantLigne,$DerniereLigne->fct_ccomposition_tostring, time() + 365*24*3600);
            setcookie('Oeu_Page',1, time() + 24*3600);
            setcookie('Oeu_NbLignes',1, time() + 24*3600);
            
            $RequeteOeuvre="SELECT * FROM vOeuvre";
            $RequeteCount="SELECT COUNT(*) as Total FROM vOeuvre";
            if (strlen($ClauseWhere)>0) {
                    $RequeteOeuvre=$RequeteOeuvre." WHERE ".$ClauseWhere;
                    $RequeteCount=$RequeteCount." WHERE ".$ClauseWhere;
            }
            if (strlen($ClauseOrder)>0) {
                    $RequeteOeuvre=$RequeteOeuvre." ORDER BY ".$ClauseOrder;
                    $RequeteCount=$RequeteCount." ORDER BY ".$ClauseOrder;
            }
            $requete=mysqli_query($mysqli,$RequeteCount);
            if (!$requete) {
               $message  = 'Requête invalide : ' . mysql_error() . "\n";
               $message .= 'Requête complète : ' . $RequeteCount;
               die($message);
            }
            if ($requete){ 
                    $Enrgt = mysqli_fetch_Array($requete); 
                    setcookie('Oeu_NbLignes',$Enrgt['Total'], time() + 24*3600);
                    $NbLigneTotal=$Enrgt['Total'];
            } else { 
                setcookie('Oeu_NbLignes',1, time() + 24*3600);
            }
            mysqli_free_result($requete);
	}
	// arrondir au supérieur
	$NbPageTotal=ceil($NbLigneTotal/30);
	$RequeteOeuvre=$RequeteOeuvre." LIMIT ".((($PageEnCours-1)*30)+1).",30";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="General/Test_Police_flex.css" />
        <title>tests Page Compositeur</title>
    </head>
    <body id="CPageGeneral">
        <?php 
            
            echo $MsgConnexion[1];
            echo $MsgConnexion[2];
            echo $RequeteOeuvre;
            echo "Page : ".$PageEnCours."/".$NbPageTotal."(".$NbLigneTotal.")";
        ?>
        <!-- </div id="PageGeneral"> -->
        <div class="EEntete">
            <h1>Titre</h1>
            </div>
        <h1></h1>
        <div class="EContenu">
            <div id="CContenu">
                <?php include("General/MenuGeneral.php");?>

            <td valign="top">
            <section class="EContenu">
                <article class="BlocResultat">   
                    <table border="1px" width="100%">
                        <tr>
                        <td class="TitreSection" width="80%">Titre Resultat</td>
                        <td >  
                            <!-- Pagination-->
                            <ul class="ListePage"> 
                                <li class="ListePage"><a href="Page_Oeuvre.php?Page=1"><img src="img/BtnFirst.gif" alt="Prem" title="Aller à la première page"/></a>
                                    <?php if ($PageEnCours>1){?>
                                <li class="ListePage"><a href="Page_Oeuvre.php?Page=<?php echo $_COOKIE['Oeu_Page']-1; ?>"><img src="img/BtnPrev.gif" alt="Prec" title="Aller à la page précédente"/></a>
                                            <?php } ?>
                                <li class="ListePage">Page <?php echo $PageEnCours."/".$NbPageTotal;?>
                                            <?php if ($_COOKIE['Oeu_Page']<$NbPageTotal){?>
                                <li class="ListePage"><a href="Page_Oeuvre.php?Page=<?php echo $_COOKIE['Oeu_Page']+1; ?>"><img src="img/BtnNext.gif" alt="Suiv" title="Aller à la page suivante"/></a>
                                            <?php } ?>
                                <li class="ListePage"><a href="Page_Oeuvre.php?Page=<?php echo $NbPageTotal; ?>"><img src="img/BtnLast.gif" alt="Dern" title="Aller à la dernière page"/></a>
                            </ul>
                        </td></tr>
                    </table>
                    <table class="SectionCritere">
                        <tr> 
                            <td> Titre 		   </td>
                            <td> Compositeur   </td>
                            <td> Date Compo    </td>
                            <td> Opus 		   </td>
                        </tr>
                        <tr> 
                            <form name="Filtre" action="Page_Oeuvre.php?arg1=FLT" method="POST">
                            <td><input type="text" name="Filtre_Titre"></td>
                            <td><Select name="Filtre_Compositeur">
                                <?php fct_select_compositeur($mysqli,1); ?>
                                </select>
                            </td>
                            <td><input type="text" name="Filtre_DateCompo"> </td>
                            <td> <input type="text" name="Filtre_Opus"> 
							<<button>Filtre</button> </td>
                        </tr>
                    </table>
                    <table class="SectionResulat">
                        <tr>
                            <form name="Tri" action="Page_Oeuvre.php?arg1=TRI">
                            <td> Titre 		   </td>
                            <td> Compositeur   </td>
                            <td> Date Compo    </td>
                            <td> Opus 		   </td>
                            <td> Ref Catalogue </td>
                            <td> Vers </td>
                            </form>
                        </tr>
                        <?php
                        $requete=mysqli_query($mysqli,$RequeteOeuvre);
                        if (mysqli_num_rows($requete)>=1){
                                while ($ListeOeuvre = mysqli_fetch_assoc($requete)){
                                        echo "<tr><td>".htmlentities($ListeOeuvre['Oeu_Titre'])."</td>";
                                        echo "<td>".htmlentities($ListeOeuvre['Cmp_Nom'])." ".htmlentities($ListeOeuvre['Cmp_Prenom'])."</td>";
                                        echo "<td>".$ListeOeuvre['Compo_Annee']."</td>";
                                        echo "<td>".$ListeOeuvre['TypeOpus']." ".$ListeOeuvre['Oeu_Opus']."</td>";
                                        echo "<td>".$ListeOeuvre['Oeu_Ref_Catalogue']."</td>";
                                        echo '<td><a href="Detail_Oeuvre_V3.php?Oeuvre='.$ListeOeuvre['Oeu_Id'].'">Vers</a></td></tr>';
                                }
                        } else {
                                echo '<tr><td colspan="6"> Pas d\'oeuvres à lister </td></tr>';
                        }
                        ?>
                    </table>
                </article>
            </section>
        </div>
	</div>
	<div class="EPied">		
				<!-- </div id="PageGeneral"> -->
        <h1></h1>
        <p class="NoteLassus">Bonjour et bienvenue sur mon site !0123456</p>
        <p class="NoteRousseau">Pour le moment, mon site est un peu <em>vide</em>. Patientez encore un peu !</p>
        </div>
		</body>
</html>
