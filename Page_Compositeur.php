<?php
	session_start();
	include_once('fctGeneral.function.php');
	include_once("General/BDD/Connexion.php");
	//Variables de compositeur
	$Cmp_Id=0; $Cmp_Nom="";$Cmp_Prenom="";$Cmp_Naissance="";$Cmp_Mort="";$Cmp_Epoque=0;$Cmp_Nationalite=0;$Cmp_Initiales="";
	// variables de filtre
	$f_Nom="";$f_Epoque=0;$f_Nationalite=0;$f_Naissance=0;
	if (isset($_COOKIE['fCmp_Nom'])){$f_Nom=$_COOKIE['fCmp_Nom'];}
	if (isset($_COOKIE['fCmp_Epq'])){$f_Epoque=$_COOKIE['fCmp_Epq'];}
	if (isset($_COOKIE['fCmp_Nat'])){$f_Nationalite=$_COOKIE['fCmp_Nat'];}
	if (isset($_COOKIE['fCmp_Naissance'])){$f_Naissance=$_COOKIE['fCmp_Naissance'];}
	$t_Nom="";$t_Epoque=0;$t_Naissance=0;$t_Nationalite=0;
	if (isset($_COOKIE['tCmp_Nom'])){$t_Nom=$_COOKIE['tCmp_Nom'];}
	if (isset($_COOKIE['tCmp_Epq'])){$t_Epoque=$_COOKIE['tCmp_Epq'];}
	if (isset($_COOKIE['tCmp_Nat'])){$t_Nationalite=$_COOKIE['tCmp_Nat'];}
	if (isset($_COOKIE['tCmp_Naissance'])){$t_Naissance=$_COOKIE['tCmp_Naissance'];}
	$Action="";
	if (isset($_GET['Arg1'])){$Action=$_GET['Arg1'];}
	$ParamLecture=0;
	if (isset($_GET['Param'])){$ParamLecture=$_GET['Param'];}
	if ($Action=='INIT'){
		$f_Nom="";$f_Epoque=0;$f_Nationalite=0;$f_Naissance=0;
		$t_Nom=0;$t_Epoque=0;$t_Naissance=0;$t_Nationalite=0;
		setcookie("fCmp_Nom","",time()-1);
		setcookie("fCmp_Epq","",time()-1);
		setcookie("fCmp_Nat","",time()-1);
		setcookie("fCmp_Naissance","",time()-1);
		setcookie("tCmp_Nom","",time()-1);
		setcookie("tCmp_Epq","",time()-1);
		setcookie("tCmp_Nat","",time()-1);
		setcookie("tCmp_Naissance","",time()-1);
		
		//unset($_COOKIE["fCmp_Nom"]);
		//unset($_COOKIE["fCmp_Epq"]);
		//unset($_COOKIE["fCmp_Nat"]);
		//unset($_COOKIE["fCmp_Naissance"]);
		//unset($_COOKIE["tCmp_Nom"]);
		//unset($_COOKIE["tCmp_Epq"]);
		//unset($_COOKIE["tCmp_Nat"]);
		//unset($_COOKIE["tCmp_Naissance"]);
		
	}elseif ($Action=='EDT'){
	// une modification ou un ajout dans les données
		if (isset($_POST['sCmp_Id'])){$Cmp_Id=$_POST['sCmp_Id'];}
		if (isset($_POST['sCmp_Nom'])){$Cmp_Nom=$_POST['sCmp_Nom'];}
		if (isset($_POST['sCmp_Prenom'])){$Cmp_Prenom=$_POST['sCmp_Prenom'];}
		if (isset($_POST['sCmp_Initiales'])){$Cmp_Initiales=$_POST['sCmp_Initiales'];}
		if (isset($_POST['sCmp_Naissance'])){$Cmp_Naissance=$_POST['sCmp_Naissance'];}
		if (isset($_POST['sCmp_Mort'])){$Cmp_Mort=$_POST['sCmp_Mort'];}
		if (isset($_POST['sCmp_Nationalite'])){
			$Cmp_Nationalite_Tab=explode(";",$_POST['sCmp_Nationalite']);
			if ($Cmp_Nationalite_Tab[0]==""){$Cmp_Nationalite_Tab[0]=0;}
			if ($Cmp_Nationalite_Tab[0]!=0){$Cmp_Nationalite=$Cmp_Nationalite_Tab[0];}
		}
		if (isset($_POST['sCmp_Epoque'])){
			$Cmp_Epoque_Tab=explode(";",$_POST['sCmp_Epoque']);
			if ($Cmp_Epoque_Tab[0]==""){$Cmp_Epoque_Tab[0]=0;}
			if ($Cmp_Epoque_Tab[0]!=0){	$Cmp_Epoque=$Cmp_Epoque_Tab[0];}
		}
		if ($Cmp_Id!=0){
			// on va faire une MAJ
			$requete="UPDATE Compositeur SET Cmp_Nom='".$Cmp_Nom."',Cmp_Prenom='".$Cmp_Prenom."', Cmp_Initiales='".$Cmp_Initiales.
						 "' ,Cmp_Naissance='".$Cmp_Naissance."', Cmp_Mort='".$Cmp_Mort."', Cmp_Epoque=".$Cmp_Epoque.",".
						 "Cmp_Nationalite=".$Cmp_Nationalite.
						 " WHERE Cmp_Id=".$Cmp_Id ;
		} else {
			// on va faire une insertion
			$requete="INSERT INTO Compositeur ( Cmp_Nom, Cmp_Prenom, Cmp_Initiales, Cmp_Naissance, Cmp_Mort, Cmp_Epoque, Cmp_Nationalite) "
			            ."VALUES ('".$Cmp_Nom."','".$Cmp_Prenom."','".$Cmp_Initiales."','".$Cmp_Naissance."','".$Cmp_Mort."',"
						.$Cmp_Epoque.",".$Cmp_Nationalite.")" ;
		}
		if (!mysqli_query($mysqli,$requete)){
			$message  = 'Requête invalide : ' . mysqli_error($mysqli) . "\n";
			$message .= 'Requête complète : ' . $requete;
		} else {
			// on peut recharger les données
			if ($Cmp_Id==0){
				 $ParamLecture=mysqli_insert_id($mysqli) ;
			}
			$message = 'Enregitrement effectué';
		}
	} elseif ($Action=='FLT'){
		if (isset($_POST['f_Nom'])){
			$f_Nom=$_POST['f_Nom'];
			setcookie('fCmp_Nom',$f_Nom, time() + 24*3600);
		}
		if (isset($_POST['f_Epoque'])){
			$f_Epoque_Tab=explode(";",$_POST['f_Epoque']);
			setcookie('fCmp_Epq',$f_Epoque_Tab[0], time() + 24*3600);
			$f_Epoque=$f_Epoque_Tab[0];
		}
		if (isset($_POST['f_Nationalite'])){
			$f_Nationalite_Tab=explode(";",$_POST['f_Nationalite']);
			setcookie('fCmp_Nat',$f_Nationalite_Tab[0], time() + 24*3600);
			$f_Nationalite=$f_Nationalite_Tab[0];
		}
		if (isset($_POST['f_Naissance'])){
			$f_Naissance=$_POST['f_Naissance'];
			setcookie('fCmp_Naissance',$f_Naissance, time() + 24*3600);
		}
	// Un filtre est à placer sur la requete
	} elseif ($Action=='TRI'){
	// Un tri est à placer sur la requete
		if (isset($_POST['t_Nom'])){
			$t_Nom=$_POST['t_Nom'];
			setcookie('tCmp_Nom',$t_Nom, time() + 24*3600);
		}
		if (isset($_POST['t_Epoque'])){
			$t_Epoque_Tab=explode(";",$_POST['t_Epoque']);
			setcookie('tCmp_Epq',$t_Epoque_Tab[0], time() + 24*3600);
			$t_Epoque=$t_Epoque_Tab[0];
		}
		if (isset($_POST['t_Nationalite'])){
			$t_Nationalite_Tab=explode(";",$_POST['t_Nationalite']);
			setcookie('tCmp_Nat',$t_Nationalite_Tab[0], time() + 24*3600);
			$t_Nationalite=$t_Nationalite_Tab[0];
		}
		if (isset($_POST['t_Naissance'])){
			$t_Naissance=$_POST['t_Naissance'];
			setcookie('tCmp_Naissance',$t_Naissance, time() + 24*3600);
		}
	}
	if ($ParamLecture!=0){
	// on va aller lire le compositeur
		$requete="SELECT * FROM Compositeur WHERE Cmp_Id=".$ParamLecture;
		$resultat=mysqli_query($mysqli,$requete);
        if ($resultat){
			if (mysqli_num_rows($resultat)>=1){ 
				while($Enrgt = mysqli_fetch_assoc($resultat)){
					$Cmp_Id=$Enrgt['Cmp_Id'];
					$Cmp_Nom=$Enrgt['Cmp_Nom'];
					$Cmp_Prenom=$Enrgt['Cmp_Prenom'];
					$Cmp_Initiales=$Enrgt['Cmp_Initiales'];
					$Cmp_Naissance=$Enrgt['Cmp_Naissance'];
					$Cmp_Mort=$Enrgt['Cmp_Mort'];
					$Cmp_Epoque=$Enrgt['Cmp_Epoque'];
					$Cmp_Nationalite=$Enrgt['Cmp_Nationalite'];
				}
			}
			mysqli_free_result($resultat);
		}
	}
	$ClauseWhere="";$ClauseOrder="";
	//Contruction clause Where
	if ($f_Nom!=""){$ClauseWhere="Cmp_Nom like '".$f_Nom."'";}
	if ($f_Epoque!=0){
		if ($ClauseWhere==""){$ClauseWhere="Cmp_Epoque = ".$f_Epoque."";
		} else {$ClauseWhere=$ClauseWhere."AND Cmp_Epoque = ".$f_Epoque."";};
	}
	if ($f_Nationalite!=0){
		if ($ClauseWhere==""){$ClauseWhere="Cmp_Nationalite = ".$f_Nationalite."";
		} else {$ClauseWhere=$ClauseWhere."AND Cmp_Nationalite = ".$f_Nationalite."";};
	}
	if ($f_Naissance!=0){
		if ($ClauseWhere==""){$ClauseWhere="Cmp_Naissance = ".$f_Naissance."";
		} else {$ClauseWhere=$ClauseWhere."AND Cmp_Naissance = ".$f_Naissance."";};
	}
	//Contruction clause Orderby 
	if ($t_Nom!=0){
		if ($t_Nom==2){	$tmp="Cmp_Nom ASC"; } else { $tmp="Cmp_Nom"; }
		$ClauseOrder=$tmp;
	}
	if ($t_Epoque!=0){
		if ($t_Epoque==2){	$tmp="Epoque ASC"; } else { $tmp="Epoque"; }
		if ($ClauseOrder==""){$ClauseOrder=$tmp;} else {$ClauseOrder=$ClauseOrder.",".$tmp;}
	}
	if ($t_Nationalite!=0){
		if ($t_Nationalite==2){	$tmp="Nationalite ASC"; } else { $tmp="Nationalite"; }
		if ($ClauseOrder==""){$ClauseOrder=$tmp;} else {$ClauseOrder=$ClauseOrder.",".$tmp;}
	}
	if ($t_Naissance!=0){
	    if ($t_Naissance==2){ $tmp="Cmp_Naissance ASC"; } else { $tmp="Cmp_Naissance"; }
		if ($ClauseOrder==""){$ClauseOrder=$tmp;} else {$ClauseOrder=$ClauseOrder.",".$tmp;}
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="General/Test_Police_flex.css" />
        <title>Compositeurs</title>
        
    </head>
    <body id="CPageGenerale">
		<div class="EEntete">
		<h1>Compositeurs</h1>
		</div>
		<div class="EContenu">
		    <div id="CContenu">
			<!-- Chargement du menu-->
			<?php include("General/MenuGeneral.php");?>
			<section class="EContenu">
            <article class="BlocResultat">  
			<h2>
				Liste des compositeurs
			</h2>			
			<?php if (strlen($MSG1)>0){echo "<h1>".$MSG1."</h1>";}?>
			<?php if (strlen($message)>0){echo "<h1>".$message."</h1>";}?>
			<?php if ($Zach_Verif>0){ ?>
			<table class="SectionSaisie">
				<tr>
					<td colspan="2"> Nom / Prenom </td>
					<td> Initiales </td>
					<td> Epoque </td>
					<td> Nationalite </td>
					<td> Naissance </td>
					<td> Mort </td>
				<tr>
				<form action="Page_Compositeur.php?Arg1=EDT" method="post">
					<td><input type="hidden" name="sCmp_Id" Value="<?php echo $Cmp_Id; ?>"/>
					<input type="text" name="sCmp_Nom" Value="<?php echo $Cmp_Nom; ?>"/></td>
					<td><input type="text" name="sCmp_Prenom" Value="<?php echo $Cmp_Prenom; ?>"/></td>
					<td><input type="text" name="sCmp_Initiales" Value="<?php echo $Cmp_Initiales; ?>"/></td>
					<td><select name="sCmp_Epoque"/>
						<?php fct_select_prm_epoque($mysqli,$Cmp_Epoque); ?>
						</select></td>
					<td><select name="sCmp_Nationalite"/>
						<?php fct_select_prm_nationalite($mysqli,$Cmp_Nationalite); ?>
						</select></td>
					<td><input type="text" name="sCmp_Naissance" Value="<?php echo $Cmp_Naissance; ?>"/></td>
					<td><input type="text" name="sCmp_Mort" Value="<?php echo $Cmp_Mort; ?>"/>
						<button>Valider</button>
					</td>
				</form>
				</tr>
			</table>
			<?php } ?>
			
			<table class="SectionCritere">
				<tr>
				<form action="Page_Compositeur.php?Arg1=FLT" method="post">
					<td colspan="2"><input type="text" name="f_Nom" Value="<?php echo $f_Nom; ?>"/></td>
					<td><select name="f_Epoque"/>
						<?php fct_select_prm_epoque($mysqli,$f_Epoque); ?>
						</select></td>
					<td><select name="f_Nationalite"/>
						<?php fct_select_prm_nationalite($mysqli,$f_Nationalite); ?>
						</select></td>
					<td><input type="text" name="f_Naissance" Value="<?php echo $f_Naissance; ?>"/>
						<button>Filtre</button>
					</td>
				</form>
				</tr>
			</table>
			
			<table class="SectionResulat">
					<tr>
						<form name="Tri" action="Page_Compositeur.php?Arg1=TRI" method="POST">
							<td colspan="2"> <button name="t_Nom" value="<?php echo fct_definir_ordre_tri($t_Nom);?>">Compositeur</button></td>
							<td> <button name="t_Epoque" value="<?php echo fct_definir_ordre_tri($t_Epoque);?>">Epoque</button></td>
							<td> <button name="t_Nationalite" value="<?php echo fct_definir_ordre_tri($t_Nationalite);?>">Nationalite</button></td>
							<td> <button name="t_Naissance" value="<?php echo fct_definir_ordre_tri($t_Naissance);?>">Naissance</button></td>
							<td> Décès</td>
							<td> <button name="TRD" formaction="Page_Compositeur.php?Arg1=INIT">INIT</button></td>
						</form>
					</tr>
					<?php
					$requete="SELECT * FROM vCompositeurs";
					if ($ClauseWhere!=""){$requete=$requete." WHERE ".$ClauseWhere;}
					if ($ClauseOrder!=""){$requete=$requete." ORDER BY ".$ClauseOrder;}
					$requete=$requete.";";
					// echo "<p>".$requete."</p>"; 
					$resultat = mysqli_query($mysqli, $requete);
					while($donnees = mysqli_fetch_assoc($resultat))
					{
						echo "<tr><td colspan=\"2\">".htmlentities($donnees['Cmp_Nom'])." ".htmlentities($donnees['Cmp_Prenom'])."</td>";
						echo "<td>".htmlentities($donnees['Epoque'])."</td>";
						echo "<td>".htmlentities($donnees['Nationalite'])."</td>";
						echo "<td>".$donnees['Cmp_Naissance']."</td>";
						echo "<td>".$donnees['Cmp_Mort']."</td>";
						echo "<td><a href=\"Page_Compositeur.php?Param=".$donnees['Cmp_Id']."\">Vers</a></td></tr>";
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


