<!-- Menu général des pages -->
<nav class="EMenu">
	<h1 class="TitreMenu">Catalogue	<?php if ($Zach_Verif!=0){echo "<br>(".$Zach_User.")";}?></h1>
	<ul>
		<li><a href="/Catalogue/Page_Oeuvre.php">Oeuvre</a></li>
		<li><a href="#">Editions</a></li>
		<li><a href="#">Bibliotheque</a></li>
	</ul>
	<h1 class="TitreMenu">General</h1>
	<ul>
		<li><a href="/Catalogue/Page_Compositeur.php">Compositeurs</a></li>
		<li><a href="/Catalogue/Page_Editeur.php">Editeurs</a></li>
		<li><a href="#">Bibliotheque</a></li>
	</ul>
	<h1 class="TitreMenu">Parametrages</h1>
	<ul>
		<li><a href="/Catalogue/Page_Parametrage.php">Parametrages</a></li>
		<li><a href="#">Bibliotheque</a></li>
		<li><a href="/Catalogue/General/BDD/Identification.php">Identification</a></li>
	</ul>
</nav>

