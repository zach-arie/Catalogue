<?php
    session_start();
	include_once('fctGeneral.function.php');
	include_once("General/BDD/DefQuery.php");
    include_once("General/BDD/Connexion.php");
	
	$LigneCours=1;	$Avantligne=0;	$OeuvreCours=0;	$Message="";$bln_charger_Oeuvre=false;$bln_charger_Composition=false;
	$Cms_Ordre=0;$Cms_TitreMvt="";$Cms_Titre="";$Cms_Ton=0;$Cms_Mode=0;$Cms_Id=0;
	if (isset($_COOKIE['cOeu_Id'])){$OeuvreCours=$_COOKIE['cOeu_Id'];}
	$Prm_Ordre=0;
	if (isset($_GET['Param'])){$Prm_Ordre=$_GET['Param'];}
	$Prm_Appel="";
	if (isset($_GET['arg1'])){$Prm_Appel=$_GET['arg1'];}
	
	// c'est le formulaire composition qui a demandé la validation
	if ($Prm_Appel=='TRD'){
		$OeuvreCours=0;
		setcookie('cOeu_Id',0, time() + 24*3600);
		
	} elseif ($Prm_Appel=='COMP'){
		// on a eu une saisie on va l'enregistrer car il a été demandé
		if ($OeuvreCours>0){
			$Prm_Ordre=0;$Cms_Ordre=0;$Cms_TitreMvt=""; $Cms_Titre="";$Cms_Ton=0;$Cms_Mode=0;$Message="";
			// valoriser les variables
			if (isset($_POST['fCms_Id'])){
				$Prm_Ordre=$_POST['fCms_Id'];
			}
			if (isset($_POST['fCms_Ordre'])){
				$Cms_Ordre=$_POST['fCms_Ordre'];
			}
			if (isset($_POST['fCms_Titre'])){
				$Cms_Titre=$_POST['fCms_Titre'];
			}
			if (isset($_POST['fCms_Titre_Mvt'])){
				$Cms_TitreMvt=$_POST['fCms_Titre_Mvt'];
			}
			if (isset($_POST['fCms_Ton'])){
				$Cms_Ton=$_POST['fCms_Ton'];
			}
			if (isset($_POST['fCms_Mode'])){
				$Cms_Mode=$_POST['fCms_Mode'];
			}
			// Définition de la requete à lancer
			$Cms_Ton_Tab=explode(";",$Cms_Ton);
			$Cms_Mode_Tab=explode(";",$Cms_Mode);
			//list($Cms_Ton_Id, $Cms_Ton_Lib)=split(";",$Cms_Ton);
			//list($Cms_Mode_Id, $Cms_Mode_Lib)=split(";",$Cms_Mode);
			if ($Prm_Ordre!=0) {
				// on fait une mise à jour de l'élément de composition
				$requete="UPDATE Composition SET Cms_Ordre=".$Cms_Ordre.",Cms_Ton=".$Cms_Ton_Tab[0].", Cms_Mode=".$Cms_Mode_Tab[0].
						 ", Cms_Titre_Mvt='".$Cms_TitreMvt."', Cms_Titre='".$Cms_Titre."' WHERE Cms_Oeuvre=".$OeuvreCours." AND Cms_Id=".$Prm_Ordre ;
			} else {
				// on fait l'insertion de l'élément de composition demandé
				$requete="INSERT INTO Composition (Cms_Oeuvre, Cms_Ordre,Cms_Titre_Mvt, Cms_Ton,Cms_Mode, Cms_Titre)
						 VALUES (".$OeuvreCours.",".$Cms_Ordre.",'"
								 .$Cms_TitreMvt."',".$Cms_Ton_Tab[0].",".$Cms_Mode_Tab[0].",'".$Cms_Titre."')" ;
			}
			if (!mysqli_query($mysqli,$requete)){
				$message  = 'Requête invalide : ' . mysql_error($mysqli) . "\n";
				$message .= 'Requête complète : ' . $requete;
			} else {
				// on peut recharger les données
				$message = 'Enregitrement effectué';
				$Prm_Ordre=0;$Cms_Ordre=0;$Cms_TitreMvt=""; $Cms_Titre="";$Cms_Ton=0;$Cms_Mode=0;
			}
			mysqli_free_result($requete);
			
			$bln_charger_Oeuvre=true;
		} else {
			$message="Une composition ne peut être saisie qu'avec une oeuvre de référence, validation ignorée";
		}
	} elseif ($Prm_Appel=='OEU') {
		// c'est l'oeuvre dont la validation a été demandée
		$Oeu_Titre=""; $Oeu_Compositeur=0;$Oeu_DateComposition;$Oeu_JusteAnnee=1;$Oeu_RefCatal="";$Oeu_Opus=0;
		$Oeu_TypeOpus=0;$Oeu_TypeOeuvre=0;$Oeu_Niveau=0;$Oeu_Annee="";$Oeu_Type=0;
		if (isset($_POST['fOeu_Id'])){
			$OeuvreCours=$_POST['fOeu_Id'];
		}
		$blq_enrgt=0;
		if (isset($_POST['fOeu_Titre'])){
			$Oeu_Titre=$_POST['fOeu_Titre'];
		} else {
			$message="Le Titre est obligatoire";
			$blq_enrgt=1;
		}
		if (isset($_POST['fOeu_Compositeur'])){
			$Oeu_Compositeur=$_POST['fOeu_Compositeur'];
		} else {
			if (strlen($message)>0){
				$message.="<br>Le Compositeur est obligatoire";
			} else {
				$message="Le Compositeur est obligatoire";
			}
			$blq_enrgt=1;
		}
		if (isset($_POST['fOeu_DateComposition'])){
			$Oeu_DateComposition=$_POST['fOeu_DateComposition'];
		}
		if (isset($_POST['fOeu_JusteAnnee'])){
			if ($_POST['fOeu_JusteAnnee']=='on'){
				$Oeu_JusteAnnee=1;
			} else { $Oeu_JusteAnnee=0;}
		}
		if (isset($_POST['fOeu_RefCatalogue'])){
			$Oeu_RefCatalogue=$_POST['fOeu_RefCatalogue'];
		}
		if (isset($_POST['fOeu_Annee'])){
			$Oeu_Annee=$_POST['fOeu_Annee'];
		}
		if (isset($_POST['fOeu_Opus'])){
			if ($_POST['fOeu_Opus']!=""){
				$Oeu_Opus=$_POST['fOeu_Opus'];
			}
		}
		if (isset($_POST['fOeu_TypeOpus'])){
			$Oeu_TypeOpus=$_POST['fOeu_TypeOpus'];
		}
		if (isset($_POST['fOeu_Type'])){
			$Oeu_Type=$_POST['fOeu_Type'];
		}
		if (isset($_POST['fOeu_TypeOeuvre'])){
			$Oeu_TypeOeuvre=$_POST['fOeu_TypeOeuvre'];
		}
		if (isset($_POST['fOeu_Niveau'])){
			$Oeu_Niveau=$_POST['fOeu_Niveau'];
		}
		if ($blq_enrgt==0){
			// préparer la requete pour faire evoluer les oeuvres
			$Oeu_TypeOpus_Tab=explode(";",$Oeu_TypeOpus);
			$Oeu_Type_Tab=explode(";",$Oeu_Type);
			$Oeu_Niveau_Tab=explode(";",$Oeu_Niveau);
			
			//list($Oeu_Type_Id, $Oeu_Type_Lib)=split(";",$Oeu_TypeOpus);
			//list($Oeu_Niveau_Id, $Oeu_Niveau_Lib)=split(";",$Oeu_Niveau);
			if ($OeuvreCours!=0) {
				// Modification de l'oeuvre
				$requete="UPDATE Oeuvre SET Oeu_Compositeur=".$Oeu_Compositeur.",Oeu_Opus=".$Oeu_Opus.", Oeu_Annee='".$Oeu_Annee.
						 "' ,Oeu_JusteAnnee=".$Oeu_JusteAnnee.", Oeu_Type_Opus=".$Oeu_TypeOpus_Tab[0].", Oeu_Titre='".$Oeu_Titre."',".
						 "Oeu_Ref_Catalogue='".$Oeu_RefCatalogue."', Oeu_Type=".$Oeu_Type_Tab[0].", Oeu_Niveau=".$Oeu_Niveau_Tab[0].
						 " WHERE Oeu_Id=".$OeuvreCours ;
			} else {
				// Ajout de l'oeuvre
				$requete="INSERT INTO Oeuvre ( Oeu_Compositeur, Oeu_Opus, Oeu_Annee, Oeu_JusteAnnee, Oeu_Type_Opus, Oeu_Titre, Oeu_Ref_Catalogue, Oeu_Type, Oeu_Niveau) "
			            ."VALUES (".$Oeu_Compositeur.",".$Oeu_Opus.",'".$Oeu_Annee."',".$Oeu_JusteAnnee.",".$Oeu_TypeOpus_Tab[0].",'"
						.$Oeu_Titre."','".$Oeu_RefCatalogue."',".$Oeu_Type_Tab[0].",".$Oeu_Niveau_Tab[0].")" ;
			}
			if (!mysqli_query($mysqli,$requete)){
				$message  = 'Requête invalide : ' . mysqli_error($mysqli) . "\n";
				$message .= 'Requête complète : ' . $requete;
			} else {
				// on peut recharger les données
				if ($OeuvreCours==0){
					 $OeuvreCours=mysqli_insert_id($mysqli) ;
					 setcookie('cOeu_Id',$OeuvreCours, time() + 24*3600);
				}
				$message = 'Enregitrement effectué';
				$bln_charger_Oeuvre=true;
			}
			$bln_charger_Oeuvre=TRUE;
		} else {
			$message="Données obligatoires non reignées<BR>".$message;
		}
		$bln_charger_Composition=TRUE;
		
	} elseif (isset($_GET['Oeuvre'])){
		// lire l'oeuvre ainsi que son contenu car c'est un appel à cette oeuvre qui a été fait
		setcookie('cOeu_Id',$_GET['Oeuvre'], time() + 24*3600);
		$OeuvreCours=$_GET['Oeuvre'];
		$bln_charger_Oeuvre=TRUE;
		$bln_charger_Composition=TRUE;
	} elseif ($Prm_Ordre!=0){
		// on a demander la modification de la composition
		$requete="SELECT * FROM Composition WHERE Cms_Id=".$Prm_Ordre.";";
		$resultat=mysqli_query($mysqli,$requete);
        if ($resultat){
			if (mysqli_num_rows($resultat)>=1){ 
				while($Enrgt = mysqli_fetch_assoc($resultat)){
					$Cms_Id=$Enrgt['Cms_Id'];
					$Cms_Ordre=$Enrgt['Cms_Ordre'];
					$Cms_TitreMvt=$Enrgt['Cms_Titre_Mvt'];
					$Cms_Titre=$Enrgt['Cms_Titre'];
					$Cms_Ton=$Enrgt['Cms_Ton'];
					$Cms_Mode=$Enrgt['Cms_Mode'];
				}
			}
			mysqli_free_result($resultat);
			$bln_charger_Oeuvre=TRUE;
		}
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="General/Test_Police_flex.css") />
        <title>Détail d'une Oeuvre</title>
		
    </head>
    <body id="CPageGeneral">
        <!-- </div id="PageGeneral"> -->
        <div class="EEntete">
            <h1>Détail d'une Oeuvre</h1>
		</div>
	   <div class="EContenu">
            <div id="CContenu">
                <?php include("General/MenuGeneral.php");?>
                <section class="EContenu">
                <article class="BlocResultat">   
				<h2>
					D&eacute;finition d'une oeuvre (<?php echo $OeuvreCours;?>)
				</h2>
				
				<?php if (strlen($message)>0){echo "<h1>".$message."</h1>";}?>

				<table class="DetailOeuvre">        
				<?php // on va charger l'oeuvre si c'est nécessaire
					if ($OeuvreCours!=0 && $bln_charger_Oeuvre==true){
						$requete="SELECT * FROM Oeuvre WHERE Oeu_Id=".$OeuvreCours.";";
						$resultat=mysqli_query($mysqli,$requete);
						$OeuvreIdentifie=false;
						if (mysqli_num_rows($resultat)==1){ 
							$Enrgt = mysqli_fetch_assoc($resultat) ;
							$Oeu_Compositeur=$Enrgt['Oeu_Compositeur'];
							$Oeu_Titre=$Enrgt['Oeu_Titre'];
							$Oeu_Opus=$Enrgt['Oeu_Opus'];
							$Oeu_TypeOpus=$Enrgt['Oeu_Type_Opus'];
							$Oeu_JusteAnnee=$Enrgt['Oeu_JusteAnnee'];
							$Oeu_Annee=$Enrgt['Oeu_Annee'];
							$Oeu_RefCatalogue=$Enrgt['Oeu_Ref_Catalogue'];
							$Oeu_Type=$Enrgt['Oeu_Type'];
							$Oeu_Niveau=$Enrgt['Oeu_Niveau'];
						}
						mysqli_free_result($resultat);
					}
				?>
                <form name="SaisieOeuvre" action="Detail_Oeuvre_V3.php?arg1=OEU" method="post">
				<input type="hidden"  name="fOeu_Id" value="<?php echo $OeuvreCours;?>"></input>
				<tr><td colspan="2">
					<label for="fOeu_Titre"> Titre de l'oeuvre </label>
					<input type="text" name="fOeu_Titre" value="<?php echo $Oeu_Titre;?>"></input>
				</td></tr>
				<tr><td>
					<label for="fOeu_DateComposition"> Date Composition </label> 
					<input type="text" name="fOeu_Annee" value="<?php echo $Oeu_Annee;?>"> </input>
					</td>
					<td>	
					<label> Juste Année 
						<input type="checkbox" name="fOeu_JusteAnnee" <?php if ($Oeu_JusteAnnee==1){echo 'checked=\"checked\"';}?> id="LblJusteAnnee"></input>
					</label>
				</td></tr>
				<tr><td colspan="2">
			<!-- COMPOSITEUR -->
					<label> Compositeur 
						<Select name="fOeu_Compositeur"><?php fct_select_compositeur($mysqli,$Oeu_Compositeur); ?>	</select>
					</label>
				</td></tr>
			<!--TODO INSTRUMENT -->	
				<tr><td>
				<label> Opus
					<select name="fOeu_TypeOpus"><?php fct_select_prm_opus($mysqli,$Oeu_TypeOpus); ?></select>
				</label>
				</td><td>
					<input type="text" name="fOeu_Opus" value="<?php echo $Oeu_Opus; ?>"></input>
				</td>
				<tr><td colspan="2">
				<label> Ref catalogue 
					<input type="text" name="fOeu_RefCatalogue" value="<?php echo $Oeu_RefCatalogue;?>"></input>
				</label>
				</td></tr>
				<tr><td>
					<Select name="fOeu_Type"><?php fct_select_prm_typeoeuvre($mysqli,$Oeu_Type); ?></select>
				</td>
				<td>
					<Select name="fOeu_Niveau"><?php fct_select_prm_niveau($mysqli,$Oeu_Niveau); ?></select>
					<?php 
						if ($Zach_Verif>0) {
							echo "<button Name=\"Valider\"> Val </button>";
							echo "<button Name=\"Init\" formaction=\"Detail_Oeuvre_V3.php?arg1=TRD\"> Init </button>";
						}
					?>
				</td></tr>
                </form>
				</table>
                <table name="Composition">
					<tr>
						<td> Ordre </td>
						<td> Titre </td>
						<td> SousTitre </td>
						<td> Ton </td>
						<td colspan="2"> Mod </td>
					</tr>

	<?php // on va lire toute la composition de l'oeuvre
		$LigneCours=0;
		if ($OeuvreCours!=0){
		// lire la composition de l'oeuvre
			$requete=$Qry_vComposition." WHERE Cms_Oeuvre=".$OeuvreCours.";";
			$resultat=mysqli_query($mysqli,$requete);
			if ($resultat){
				if (mysqli_num_rows($resultat)>=1){ 
					while($Enrgt = mysqli_fetch_assoc($resultat)){
						if ($LigneCours<$Enrgt['Cms_Ordre']) {$LigneCours=$Enrgt['Cms_Ordre'];}
						echo "<tr><td>".$Enrgt['Cms_Ordre']."</td>";
						echo "<td>".$Enrgt['Cms_Titre_Mvt']."</td>";
						echo "<td>".$Enrgt['Cms_Titre']."</td>";
						echo "<td>".$Enrgt['Tonalite']."</td>";
						echo "<td>".$Enrgt['Mode']."</td>";
						if ($Zach_Verif>0) {
							echo "<td>".$Enrgt['Mode']."</td>";
							echo "<td><a href=\"Detail_Oeuvre_V3.php?Param=".$Enrgt['Cms_Id']."\">Vers</a></td>";
						} else {
							echo "<td colspan=\"2\">".$Enrgt['Mode']."</td>";
						}
						echo "</tr>";
					}
				}
				mysqli_free_result($resultat);
			}
		}
		if ($Zach_Verif>0){
		?>
				<tr>
					<form name="SaisieLigne" action="Detail_Oeuvre_V3.php?arg1=COMP" method="post">
						<td><input type="Text" name="fCms_Ordre" value="<?php if ($Cms_Id==0) { $LigneCours++; echo $LigneCours ; } else {echo $Cms_Ordre;}?>"></input>
							<input type="hidden" name="fCms_Id" value="<?php echo $Cms_Id;?>"></input>
						</td>
						<td><input type="text" name="fCms_Titre_Mvt" value="<?php echo $Cms_TitreMvt;?>"/></td>
						<td><input type="text" name="fCms_Titre" value="<?php echo $Cms_Titre;?>"/></td>
						<td><select name="fCms_Ton"><?php fct_select_prm_tonalite($mysqli,$Cms_Ton); ?></select></td>
						<td><select name="fCms_Mode"><?php fct_select_prm_mode($mysqli,$Cms_Mode); ?></select></td>
						<td><input type="image" src="/img/soumettre.jpg" alt="Soumettre"/></td>
					</form>
				</tr>
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
                    