<?php
	session_start();
	include_once('fctGeneral.function.php');
	include_once("General/BDD/Connexion.php");
	//Variables de compositeur
	$Cmp_Id=0; $Cmp_Nom="";$Cmp_Prenom="";$Cmp_Naissance="";$Cmp_Mort="";$Cmp_Epoque=0;$Cmp_Nationalite=0;$Cmp_Initiales="";
	// variables de filtre
	$f_Nom="";$f_Pays=0;
	if (isset($_COOKIE['fEdt_Nom'])){$f_Nom=$_COOKIE['fEdt_Nom'];}
	if (isset($_COOKIE['fEdt_Pays'])){$f_Pays=$_COOKIE['fEdt_Pays'];}
	$t_Nom="";$t_Pays=0;
	if (isset($_COOKIE['tEdt_Nom'])){$t_Nom=$_COOKIE['tEdt_Nom'];}
	if (isset($_COOKIE['tEdt_Pays'])){$t_Pays=$_COOKIE['tEdt_Pays'];}
	$Action="";
	if (isset($_GET['Arg1'])){$Action=$_GET['Arg1'];}
	$ParamLecture=0;
	if (isset($_GET['Param'])){$ParamLecture=$_GET['Param'];}
	if ($Action=='INIT'){
		$f_Nom="";$f_Epoque=0;$f_Nationalite=0;$f_Naissance=0;
		$t_Nom=0;$t_Epoque=0;$t_Naissance=0;$t_Nationalite=0;
		setcookie("fEdt_Nom","",time()-1);
		setcookie("fEdt_Pays","",time()-1);
		setcookie("tEdt_Nom","",time()-1);
		setcookie("tEdt_Pays","",time()-1);
	}elseif ($Action=='EDT'){
	// une modification ou un ajout dans les données
		if (isset($_POST['sEdt_Id'])){$Edt_Id=$_POST['sEdt_Id'];}
		if (isset($_POST['sEdt_Nom'])){$Edt_Nom=$_POST['sEdt_Nom'];}
		if (isset($_POST['sEdt_Collection'])){$Edt_Collection=$_POST['sEdt_Collection'];}
		if (isset($_POST['sEdt_Internet'])){$Edt_Internet=$_POST['sEdt_Internet'];}
		if (isset($_POST['sEdt_Pays'])){
			$Edt_Pays_Tab=explode(";",$_POST['sEdt_Pays']);
			if ($Edt_Pays_Tab[0]==""){$Edt_Pays_Tab[0]=0;}
			if ($Edt_Pays_Tab[0]!=0){$Edt_Pays=$Edt_Pays_Tab[0];}
		}
		if ($Edt_Id!=0){
			// on va faire une MAJ
			$requete="UPDATE Editeur SET Edt_Nom='".$Edt_Nom."',Edt_Collection='".$Edt_Collection."', Edt_Site_Internet='".$Edt_Internet."',".
						 "edt_Pays=".$Edt_Pays.
						 " WHERE Edt_Id=".$Edt_Id ;
		} else {
			// on va faire une insertion
			$requete="INSERT INTO Editeur ( Edt_Nom, Edt_Collection, Edt_Pays, Edt_Site_Internet) "
			            ."VALUES ('".$Edt_Nom."','".$Edt_Collection."',".$Edt_Pays.",'".$Edt_Internet."')" ;
		}
		if (!mysqli_query($mysqli,$requete)){
			$message  = 'Requête invalide : ' . mysqli_error($mysqli) . "\n";
			$message .= 'Requête complète : ' . $requete;
		} else {
			// on peut recharger les données
			if ($Edt_Id==0){
				 $ParamLecture=mysqli_insert_id($mysqli) ;
			}
			$message = 'Enregitrement effectué';
		}
	} elseif ($Action=='FLT'){
	// Un filtre est à placer sur la requete	
		if (isset($_POST['f_Nom'])){
			$f_Nom=$_POST['f_Nom'];
			setcookie('fEdt_Nom',$f_Nom, time() + 24*3600);
		}
		if (isset($_POST['f_Pays'])){
			$f_Pays_Tab=explode(";",$_POST['f_Pays']);
			setcookie('fEdt_Pays',$f_Pays_Tab[0], time() + 24*3600);
			$f_Epoque=$f_Epoque_Tab[0];
		}
	} elseif ($Action=='TRI'){
	// Un tri est à placer sur la requete
		if (isset($_POST['t_Nom'])){
			$t_Nom=$_POST['t_Nom'];
			setcookie('tEdt_Nom',$t_Nom, time() + 24*3600);
		}
		if (isset($_POST['t_Pays'])){
			$t_Pays_Tab=explode(";",$_POST['t_Pays']);
			setcookie('tEdt_Pays',$t_Pays_Tab[0], time() + 24*3600);
			$t_Pays=$t_Pays_Tab[0];
		}
	}
	if ($ParamLecture!=0){
	// on va aller lire l'Editeur
		$requete="SELECT * FROM Editeur WHERE Edt_Id=".$ParamLecture;
		$resultat=mysqli_query($mysqli,$requete);
        if ($resultat){
			if (mysqli_num_rows($resultat)>=1){ 
				while($Enrgt = mysqli_fetch_assoc($resultat)){
					$Edt_Id=$Enrgt['Edt_Id'];
					$Edt_Nom=$Enrgt['Edt_Nom'];
					$Edt_Collection=$Enrgt['Edt_Collection'];
					$Edt_Pays=$Enrgt['Edt_Pays'];
					$Edt_Internet=$Enrgt['Edt_Site_Internet'];
				}
			}
			mysqli_free_result($resultat);
		}
	}
	$ClauseWhere="";$ClauseOrder="";
	//Contruction clause Where
	if ($f_Nom!=""){$ClauseWhere="Edt_Nom like '".$f_Nom."'";}
	if ($f_Pays!=0){
		if ($ClauseWhere==""){$ClauseWhere="Edt_Pays = ".$f_Pays."";
		} else {$ClauseWhere=$ClauseWhere."AND Edt_Pays = ".$f_Pays."";};
	}
	//Contruction clause Orderby 
	if ($t_Nom!=0){
		if ($t_Nom==2){	$tmp="Edt_Nom ASC"; } else { $tmp="Edt_Nom"; }
		$ClauseOrder=$tmp;
	}
	if ($t_Pays!=0){
		if ($t_Pays==2){	$tmp="Pays ASC"; } else { $tmp="Pays"; }
		if ($ClauseOrder==""){$ClauseOrder=$tmp;} else {$ClauseOrder=$ClauseOrder.",".$tmp;}
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="General/Test_Police_flex.css" />
        <title>Editeurs</title>
        
    </head>
    <body id="CPageGenerale">
		<div class="EEntete">
		<h1>Editeurs</h1>
		</div>
		<div class="EContenu">
		    <div id="CContenu">
			<!-- Chargement du menu-->
			<?php include("General/MenuGeneral.php");?>
			<section class="EContenu">
            <article class="BlocResultat">  
			<h2>
				Liste des Editeurs
			</h2>			
			<?php if (strlen($MSG1)>0){echo "<h1>".$MSG1."</h1>";}?>
			<?php if (strlen($message)>0){echo "<h1>".$message."</h1>";}?>
			<?php if ($Zach_Verif>0){ ?>
			<table class="SectionSaisie">
				<tr>
					<td> Nom</td>
					<td> Collection </td>
					<td> Pays </td>
					<td> Site Internet </td>
				</tr>
				<tr>
				<form action="Page_Editeur.php?Arg1=EDT" method="post">
					<td><input type="hidden" name="sEdt_Id" Value="<?php echo $Edt_Id; ?>"/>
					<input type="text" name="sEdt_Nom" Value="<?php echo $Edt_Nom; ?>"/></td>
					<td><input type="text" name="sEdt_Collection" Value="<?php echo $Edt_Collection; ?>"/></td>
					<td><select name="sEdt_Pays"/>
						<?php fct_select_prm_pays($mysqli,$Edt_Pays); ?>
						</select></td>
					<td><input type="text" name="sEdt_Internet" Value="<?php echo $Edt_Internet; ?>"/>
						<button>Valider</button>
					</td>
				</form>
				</tr>
			</table>
			<?php } ?>
			
			<table class="SectionCritere">
				<tr>
				<form action="Page_Editeur.php?Arg1=FLT" method="post">
					<td colspan="2"><input type="text" name="f_Nom" Value="<?php echo $f_Nom; ?>"/></td>
					<td><select name="f_Pays"/>
						<?php fct_select_prm_pays($mysqli,$f_Pays); ?>
						</select>
					</td>
					<td>
						<button>Filtre</button>
					</td>
				</form>
				</tr>
			</table>
			
			<table class="SectionResulat">
					<tr>
						<form name="Tri" action="Page_Editeur.php?Arg1=TRI" method="POST">
							<td colspan="2"> <button name="t_Nom" value="<?php echo fct_definir_ordre_tri($t_Nom);?>">Editeur</button></td>
							<td> <button name="t_Pays" value="<?php echo fct_definir_ordre_tri($t_Pays);?>">Pays</button></td>
							<td> <button name="TRD" formaction="Page_Editeur.php?Arg1=INIT">INIT</button></td>
						</form>
					</tr>
					<?php
					$requete="SELECT * FROM vEditeurs";
					if ($ClauseWhere!=""){$requete=$requete." WHERE ".$ClauseWhere;}
					if ($ClauseOrder!=""){$requete=$requete." ORDER BY ".$ClauseOrder;}
					$requete=$requete.";";
					echo "<p>".$requete."</p>"; 
					$resultat = mysqli_query($mysqli, $requete);
					while($donnees = mysqli_fetch_assoc($resultat))
					{
						echo "<tr><td>".htmlentities($donnees['Edt_Nom'])."</td>";
						echo "<td>".htmlentities($donnees['Edt_Collection'])."</td>";
						echo "<td>".htmlentities($donnees['Pays'])."</td>";
						echo "<td>".htmlentities($donnees['Edt_Site_Internet'])."</td>";
						echo "<td><a href=\"Page_Editeur.php?Param=".$donnees['Edt_Id']."\">Vers</a></td></tr>";
					}
					mysqli_free_result($resultat);
					?>		
			</table>
            </article>
        </section>
		
		</div>
		</div>
		<div class="EPied">
		
		</div>
		</body>
</html>


