<?php

$Qry_vOeuvre="select O1.Oeu_Id AS Oeu_Id,SEL.Tot_Edn,O1.Oeu_Compositeur AS Oeu_Compositeur,O1.Oeu_Opus AS Oeu_Opus,O1.Oeu_Annee AS Oeu_Annee,
                     O1.Oeu_JusteAnnee AS Oeu_JusteAnnee,O1.Oeu_Type_Opus AS Oeu_Type_Opus,O1.Oeu_Titre AS Oeu_Titre,O1.Oeu_Ref_Catalogue AS Oeu_Ref_Catalogue,
					 O1.Oeu_Type AS Oeu_Type,O1.Oeu_Niveau AS Oeu_Niveau,if((O1.Oeu_JusteAnnee = 1),date_format(O1.Oeu_Annee,_utf8\'%Y\'),
					 date_format(O1.Oeu_Annee,_utf8\'%d/%m/%Y\')) AS Compo_Annee,CMP.Cmp_Nom AS Cmp_Nom,CMP.Cmp_Prenom AS Cmp_Prenom,
					 OPS.Prm_Char2 AS TypeOpus,OTY.Prm_Char1 AS `Type`,NIV.Prm_Char1 AS Niveau 
			  from 	Oeuvre O1 join Compositeur CMP on CMP.Cmp_Id = O1.Oeu_Compositeur
					left join (SELECT COUNT(Ctn_Edition) as Tot_Edn, Ctn_Oeuvre FROM Contenu_Edition CTN GROUP BY Ctn_Oeuvre) SEL on SEL.Ctn_Oeuvre=O1.Oeu_Id
					left join Parametrage OPS on ((O1.Oeu_Type_Opus = OPS.Prm_Type_Ordre) and (OPS.Prm_Type_Id = \"OPS\")) 
					left join Parametrage OTY on ((O1.Oeu_Type = OTY.Prm_Type_Ordre) and (OTY.Prm_Type_Id = \"OTY\")) 
					left join Parametrage NIV on ((O1.Oeu_Niveau = NIV.Prm_Type_Ordre) and (NIV.Prm_Type_Id = \"NIV\"))";

$Qry_vComposition = "select C1.Cms_Id AS Cms_Id,C1.Cms_Oeuvre AS Cms_Oeuvre,C1.Cms_Ordre AS Cms_Ordre,C1.Cms_Titre_Mvt AS Cms_Titre_Mvt,
                            C1.Cms_Ton AS Cms_Ton,C1.Cms_Mode AS Cms_Mode,C1.Cms_Annee AS Cms_Annee,C1.Cms_Titre AS Cms_Titre,
							C1.Cms_Catalogue AS Cms_Catalogue,C1.Cms_Niveau AS Cms_Niveau,C1.Cms_Forme AS Cms_Forme,
							C1.Cms_Structure AS Cms_Structure,MD.Prm_Char1 AS `Mode`,TON.Prm_Char1 AS Tonalite,NIV.Prm_Char1 AS Niveau,
							FRM.Prm_Char1 AS Forme,STC.Prm_Char1 AS Structure 
					 from Composition C1 left join Parametrage TON on((C1.Cms_Ton = TON.Prm_Type_Ordre) and (TON.Prm_Type_Id = \"TON\"))
					      left join Parametrage MD on ((C1.Cms_Mode = MD.Prm_Type_Ordre) and (MD.Prm_Type_Id = \"MOD\"))
						  left join Parametrage NIV on ((C1.Cms_Niveau = NIV.Prm_Type_Ordre) and (NIV.Prm_Type_Id = \"NIV\")) 
						  left join Parametrage FRM on ((C1.Cms_Forme = FRM.Prm_Type_Ordre) and (FRM.Prm_Type_Id = \"FRM\"))
						  left join Parametrage STC on ((C1.Cms_Structure = STC.Prm_Type_Ordre) and (STC.Prm_Type_Id = \"STC\"))";
