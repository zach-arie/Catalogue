<?php
//class FctGen {
//fctGeneral.function.php
	function fct_select_general($mysqli,$requete,$VarValeur,$VarRetour,$ValSelected){
	//Requete     : Requete Parametrage à lancer
	//VarValeur   : Nom du champs à mettre dans la 'value' du select
	//VarRetour   : Valeur à afficher dans la zone de liste
	//ValSelected : valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$resultat=mysqli_query($mysqli,$requete);
		$VerifVarValeur=strlen($VarValeur);
		$ChampVerif='Prm_Type_Ordre';
		if ($VarValeur=='Prm_Type_Id'){$ChampVerif='Prm_Type_Id';}
		if (mysqli_num_rows($resultat)>=1){ 
			while($Enrgt = mysqli_fetch_assoc($resultat)){
				if ($ValSelected==$Enrgt[$ChampVerif]){
					$ChaineSelected=" selected";
				} else {
					$ChaineSelected="";
				}
				if ($VerifVarValeur>0){
					echo '<option value="'.$Enrgt['Prm_Type_Ordre'].";".$Enrgt[$VarValeur].'"'.
                                                $ChaineSelected.">".htmlentities($Enrgt[$VarRetour])."</option>";
				} else {
					echo '<option value="'.$Enrgt['Prm_Type_Ordre'].'"'.
                                                $ChaineSelected.">".htmlentities($Enrgt[$VarRetour])."</option>";
				}
			}
		}
		mysqli_free_result($resultat);
	}
	function fct_select_prm_tonalite($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='TON' ORDER BY Prm_Char1;";
		fct_select_general($mysqli,$requete,'Prm_Char1','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_mode($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='MOD' ORDER BY Prm_Char1 ";
		fct_select_general($mysqli,$requete,'Prm_Char1','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_opus($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='OPS' ORDER BY Prm_Char1 ";
		fct_select_general($mysqli,$requete,'','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_niveau($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='NIV' ORDER BY Prm_Char1 ";
		fct_select_general($mysqli,$requete,'','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_TypeOeuvre($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='OTY' ORDER BY Prm_Char1 ";
		fct_select_general($mysqli,$requete,'','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_parametrage($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Id qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Ordre=0 ORDER BY Prm_Char1;";
		fct_select_general($mysqli,$requete,'Prm_Type_Id','Prm_Char1',$ValSelected);
    } 
	function fct_select_prm_nationalite($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='NAT' ORDER BY Prm_Char1;";
		fct_select_general($mysqli,$requete,'Prm_Char1','Prm_Char1',$ValSelected);
    } 	
		function fct_select_prm_pays($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='NAT' ORDER BY Prm_Char2;";
		fct_select_general($mysqli,$requete,'Prm_Char2','Prm_Char2',$ValSelected);
    } 
	function fct_select_prm_epoque($mysqli,$ValSelected){
	//ValSelected, valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Parametrage WHERE Prm_Type_Id='EPQ' ORDER BY Prm_Char1;";
		fct_select_general($mysqli,$requete,'Prm_Char1','Prm_Char1',$ValSelected);
    }
	
	function fct_select_compositeur($mysqli,$ValSelected){
	//Requete     : Requete Parametrage à lancer
	//VarValeur   : Nom du champs à mettre dans la 'value' du select
	//VarRetour   : Valeur à afficher dans la zone de liste
	//ValSelected : valeur de Prm_Type_Ordre qu'il faut sélectionner dans la liste finale
		$requete="SELECT * FROM Compositeur;";
		$resultat=mysqli_query($mysqli,$requete);
		if (mysqli_num_rows($resultat)>=1){ 
			while($Enrgt = mysqli_fetch_assoc($resultat)){
				if ($ValSelected==$Enrgt['Cmp_Id']){
					$ChaineSelected="selected";
				} else {
					$ChaineSelected="";
				}
				echo '<option value="'.$Enrgt['Cmp_Id'].'"'.$ChaineSelected.">".htmlentities($Enrgt['Cmp_Nom'])
                                        ." ".htmlentities($Enrgt['Cmp_Prenom'])."</option>";
			}
		} else {
                    echo "<option> Anomalie Chargement</option>";
                }
		mysqli_free_result($resultat);
	}
	function fct_definir_ordre_tri($Valeur){
	// défini le prochain ordre de tri
		$Valeur++;
		if ($Valeur>2){ $Valeur=0;}
		return $Valeur;
	}
?>        