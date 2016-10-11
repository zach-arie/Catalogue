<?php
    session_start();
	include_once('fctGeneral.function.php');
	include_once("General/BDD/Connexion.php");
    // 0: pas de tri, 1: Croissant, 2:Decroissant
	$Prm_T_C1=0 ; $Prm_T_C2=0 ; $Prm_T_N1=0 ; $Prm_T_N2=0 ; $PrmAppel="";$Prm_Type="";
	if (isset($_COOKIE['Prm_T_C1'])){$Prm_T_C1=$_COOKIE['Prm_T_C1'];}
	if (isset($_COOKIE['Prm_T_C2'])){$Prm_T_C2=$_COOKIE['Prm_T_C2'];}
	if (isset($_COOKIE['Prm_T_N1'])){$Prm_T_N1=$_COOKIE['Prm_T_N1'];}
	if (isset($_COOKIE['Prm_T_N2'])){$Prm_T_N2=$_COOKIE['Prm_T_N2'];}
    if (isset($_COOKIE['Prm_Type'])){$Prm_Type=$_COOKIE['Prm_Type'];}
    if (isset($_GET['arg1'])){$PrmAppel=$_GET['arg1'];}
	$Prm_Ordre=0;$Prm_Char1=0;$Prm_Char2=0;$Prm_Num1=0;$Prm_Num2=0;$MSG1="";
	if ($PrmAppel=='VLD'){
		if (isset($_COOKIE['Prm_Ordre'])){
			$Prm_Ordre=$_COOKIE['Prm_Ordre'];
			$MSG1="Enregistrement Effectué";
		} else {
			$MSG1="Anomalie lors de l'enregistrement";
		}
	}
	if (isset($_GET['Param'])){$Prm_Ordre=$_GET['Param'];}
	//Lecture et affectation des cookies pour les étapes suivantes
	if ($PrmAppel=='MDF'){
		// on a eu une demande d'enregistrement
		if (isset($_POST['Prm_Ordre'])){$Prm_Ordre=$_POST['Prm_Ordre'];}
		if (isset($_POST['Prm_Char1'])){$Prm_Char1=html_entity_decode($_POST['Prm_Char1']);}
		if (isset($_POST['Prm_Char2'])){$Prm_Char2=html_entity_decode($_POST['Prm_Char2']);}
		if (isset($_POST['Prm_Num1'])) {$Prm_Num1=$_POST['Prm_Num1'];}
		if (isset($_POST['Prm_Num2'])) {$Prm_Num2=$_POST['Prm_Num2'];}
		if (strlen($Prm_Type)>=0){
			if ($Prm_Ordre>0) {
				$requete="UPDATE Parametrage set Prm_Char1='".$Prm_Char1."',Prm_Char2='".$Prm_Char2.
					 "', Prm_Num1=".$Prm_Num1.",Prm_Num2=".$Prm_Num2." WHERE Prm_Type_Id='".$Prm_Type."' AND Prm_Type_Ordre=".$Prm_Ordre;
				if (mysqli_query($mysqli,$requete)){
					setcookie('Prm_Ordre',$Prm_Ordre, time() + 24*3600);
				} else { 
					echo "<p>".$requete."</p>";
					die("Error: " . $mysqli->connect_error . "\n");
				}
				mysqli_free_result($requete);
			} else {
				// c'est un nouvel enregistrement
				$RequeteSelect="SELECT MAX(Prm_Type_Ordre) as Max_Ordre FROM Parametrage WHERE Prm_Type_Id='".$Prm_Type."'";
				$requete=mysqli_query($mysqli,$RequeteSelect);
				if (!$requete) {
					$message  = 'Requête invalide : ' . mysql_error($mysqli) . "\n";
					$message .= 'Requête complète : ' . $RequeteSelect;
					die($message);
				}
				$Prm_Ordre=0;
				if ($requete){ 
					$Enrgt = mysqli_fetch_Array($requete); 
					$Prm_Ordre=$Enrgt['Max_Ordre'];
					$Prm_Ordre++;
				}
				mysqli_free_result($requete);
				if ($Prm_Ordre>0){
					$RequeteSelect="INSERT INTO Parametrage (Prm_Type_Id,Prm_Type_Ordre,Prm_Char1,Prm_Char2,Prm_Num1,Prm_Num2,Prm_Image) Values ('"
					               .$Prm_Type."',".$Prm_Ordre.",'".$Prm_Char1."','".$Prm_Char2."',".$Prm_Num1.",".$Prm_Num2.",'')";
                                        $requete=mysqli_query($mysqli,$RequeteSelect);
					if (!$requete) {
						$message  = 'Requête invalide : ' . mysql_error($mysqli) . "\n";
						$message .= 'Requête complète : ' . $RequeteSelect;
						die($message);
					}
					mysqli_free_result($requete);
				}
				setcookie('Prm_Ordre',$Prm_Ordre, time() + 24*3600);
			}
			// on ve demander le rechargement de la page
			header ("Location:Page_Parametrage.php?arg1=VLD");
		}
	} elseif ($PrmAppel=='TRI'){
		if (isset($_POST['Tri_Char1'])){
			setcookie('Prm_T_C1',$_POST['Tri_Char1'], time() + 24*3600);
			$Prm_T_C1=$_POST['Tri_Char1'];
		}
		if (isset($_POST['Tri_Char2'])){
			setcookie('Prm_T_C2',$_POST['Tri_Char2'], time() + 24*3600);
			$Prm_T_C2=$_POST['Prm_T_C2'];
		}
		if (isset($_POST['Tri_Num1'])){
			setcookie('Prm_T_N1',$_POST['Tri_Num1'], time() + 24*3600);
			$Prm_T_N1=$_POST['Prm_T_N1'];
		} 
		if (isset($_POST['Tri_Num2'])){
			setcookie('Prm_T_N2',$_POST['Tri_Num2'], time() + 24*3600);
			$Prm_T_N2=$_POST['Prm_T_N2'];
		}
	} elseif ($PrmAppel=='TRD'){
		if (isset($_COOKIE['Prm_T_C1'])){unset($_COOKIE['Prm_T_C1']);}
		if (isset($_COOKIE['Prm_T_C2'])){unset($_COOKIE['Prm_T_C2']);}
		if (isset($_COOKIE['Prm_T_N1'])){unset($_COOKIE['Prm_T_N1']);}
		if (isset($_COOKIE['Prm_T_N2'])){unset($_COOKIE['Prm_T_N2']);}
		$Prm_T_C1=0 ; $Prm_T_C2=0 ; $Prm_T_N1=0 ; $Prm_T_N2=0 ;
	}
		
	// Définition des clauses
	// ORDER BY
	$ClauseOrder="";
	if ($Prm_T_C1>0){
		if ($Prm_T_C1==2){
			$ClauseOrder= "Prm_Char1 DESC";
		} elseif ($Prm_T_C1==1) {
			$ClauseOrder="Prm_Char1 ASC";	
		}
	}
	if ($Prm_T_C2>0){
		$TmpClauseOrder="";
		if ($Prm_T_C2==2){
			$Prm_T_C2= "Prm_Char2 DESC";
		} elseif ($Prm_T_C2==1) {
			$Prm_T_C2="Prm_Char2 ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	if ($Prm_T_N1>0){
		$TmpClauseOrder="";
		if ($Prm_T_N1==2){
			$TmpClauseOrder= "Prm_Num1 DESC";
		} elseif ($Prm_T_N1==1) {
			$TmpClauseOrder="Prm_Num1 ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	if ($Prm_Num2>0){
		$TmpClauseOrder="";
		if ($Prm_Num2==2){
			$TmpClauseOrder= "Prm_Num2 DESC";
		} elseif ($Prm_Num2==1) {
			$TmpClauseOrder="Prm_Num2 ASC";	
		}
		if (strlen($TmpClauseOrder)>0) {
			if (strlen($ClauseOrder)>0){
				$ClauseOrder=$ClauseOrder.", ".$TmpClauseOrder;
			} else {
				$ClauseOrder=$TmpClauseOrder;
			}
		}
	}
	// la page est déstinée à faire afficher les paramétrages
	// plus ou moins complete avec des ordres de tris spécifiés aux cours des consultations.
	if ($PrmAppel=='FLT'){
		if (strlen($_POST['Filtre_Parametrage'])>0){
			$Sel_Filtre= explode(";",$_POST['Filtre_Parametrage']);
			setcookie('Prm_Type',$Sel_Filtre[1], time() + 24*3600);	
			$Prm_Type=$Sel_Filtre[1];
		}
	}
	// définir la requete d'appel et charger la requete
	$RequetePrm="SELECT * FROM Parametrage WHERE Prm_Type_Id='".$Prm_Type."' AND Prm_Type_Ordre<>0";
	if (strlen($ClauseOrder)>0) {
		$Requeteprm=$RequetePrm." ORDER BY ".$ClauseOrder;
	}
	$requete=mysqli_query($mysqli,$RequetePrm);
	if (!$requete) {
	   $message  = 'Requête invalide : ' . mysql_error() . "\n";
	   $message .= 'Requête complète : ' . $RequeteCount;
	   die($message);
	}
	if ($requete){ 
		$TotalPrm = mysqli_num_rows($requete); 
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="Shortcut Icon" href="favicon.png" type="image/png">
        <link rel="stylesheet" href="General/Test_Police_flex.css" />
        <title>Param&eacute;trages</title>
    </head>
    <body id="CPageGenerale">
	    <!-- </div id="PageGeneral"> -->
		<div class="EEntete">
		<h1>Param&eacute;trages</h1>
		</div>
		<div class="EContenu">
		    <div id="CContenu">
			<!-- Chargement du menu-->
			<?php include("General/MenuGeneral.php");?>
			<section class="EContenu">
            <article class="BlocResultat">   
				<h2>
					D&eacute;finition des param&eacute;trages
				</h2>
				<?php if (strlen($MSG1)>0){echo "<h1>".$MSG1."</h1>";}?>
				<table class="SectionCritere">
                                    <tr>        <form action="Page_Parametrage.php?arg1=FLT" method="post">
                                                    <td><input type="text" name="Filtre_Type"/></td>
						<td><Select name="Filtre_Parametrage">
								<?php fct_select_prm_parametrage($mysqli,$Prm_Type); ?>
                                                    </Select>
                                                    <button>Filtre</button>
						</td>
                                                </form>
					</tr>
				</table>
				<table class="SectionResulat">
					<tr>
						<form name="Tri" action="Page_Parametrage.php?arg1=TRI" method="POST">
							<td> Ordre 	  </td> 
							<td> <button name="Tri_Char1" value="<?php echo fct_definir_ordre_tri($Prm_T_C1);?>">Char1</button></td>
							<td> <button name="Tri_Char2" value="<?php echo fct_definir_ordre_tri($Prm_T_C2);?>">Char2</button></td>
							<td> <button name="Tri_Num1" value="<?php echo fct_definir_ordre_tri($Prm_T_N1);?>">Num1</button></td>
							<td> <button name="Tri_Num2" value="<?php echo fct_definir_ordre_tri($Prm_T_N2);?>">Num2</button></td>
							<td> <button name="TRD" formaction="Page_Parametrage.php?arg1=TRD">INIT</button></td>
						</form>
					</tr>
					<?php
					if ($TotalPrm >=1){
						while ($Resultat = mysqli_fetch_assoc($requete)){
							echo "<tr><td>".$Resultat['Prm_Type_Ordre']."</td>";
							echo "<td>".htmlentities($Resultat['Prm_Char1'])."</td>";
							echo "<td>".htmlentities($Resultat['Prm_Char2'])."</td>";
							echo "<td>".$Resultat['Prm_Num1']."</td>";
							if ($Zach_Verif>0){
								// on n'autorise la modification que si authentifié
								echo "<td>".$Resultat['Prm_Num2']."</td>";
								echo "<td><a href=\"Page_Parametrage.php?Param=".$Resultat['Prm_Type_Ordre']."\">Vers</a></td>";
							} else {
								echo "<td colspan=\"2\">".$Resultat['Prm_Num2']."</td>";
							}
							echo "</tr>";
							// valorisation des variables pour la zone de saisie
							if ($Prm_Ordre==$Resultat['Prm_Type_Ordre']){
								$Prm_Char1=$Resultat['Prm_Char1'];
								$Prm_Char2=$Resultat['Prm_Char2'];
								$Prm_Num1=$Resultat['Prm_Num1'];
								$Prm_Num2=$Resultat['Prm_Num2'];
							}
						}
					} else {
						echo '<tr><td colspan="6"> Pas de param&eacute; &agrave; lister </td></tr>';
					}
					if ($Zach_Verif>0) {
					// on n'autorise la modification que si authentifié
					?>
					<form name="Tri" action="Page_Parametrage.php?arg1=MDF" method="POST">
					<tr>
					<td><input type="hidden" Value="<?php echo $Prm_Ordre; ?>" name="Prm_Ordre"/><?php echo $Prm_Ordre; ?></td>
					<td><input type="text" Value="<?php echo htmlentities($Prm_Char1); ?>"  name="Prm_Char1"/></td>
					<td><input type="text" Value="<?php echo htmlentities($Prm_Char2); ?>"  name="Prm_Char2"/></td>
					<td><input type="text" Value="<?php echo $Prm_Num1; ?>"   name="Prm_Num1"/></td>
					<td><input type="text" Value="<?php echo $Prm_Num2; ?>"   name="Prm_Num2"/></td>
					<td><button Name="Prm_Valid">Valider</button></td>
					</tr>
					</form>
				<?php } ?>
				
				</table>
            </article>
        </section>
		
		</div>
		</div>
		<div class="EPied">
		
		</div>
		</body>
</html>
