            <?php
            //$mysqli = new mysqli('127.0.0.1', 'root', '', 'zach_catalogue');
            //$mysqli = new mysqli('127.0.0.1', 'root', '', 'zach_catalogue');
            //setcookie('pseudo', 'M@teo21', time() + 365*24*3600); a mettre en haut du code IMPERATIVEMENT
            $cnx_sql = false ;
            $serveurlocal=0;
            if (isset($_SERVER['REMOTE_ADDR'])){ 
                if ($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
                        $serveurlocal=1;
                }
            }
            if ($serveurlocal==1) {
                $MsgConnexion[1]="<p>Connection sur BDD locale";
                try {
                    //$mysqli = new mysqli('mysql:host=localhost;dbname=zach_catalogue;charset=utf8', 'root', '');
                    $mysqli = new mysqli('localhost', 'root', '', 'zach_catalogue');
                    if ($mysqli->connect_errno) {
                        echo "Errno: " . $mysqli->connect_errno . "\n";
						die("Error: " . $mysqli->connect_error . "\n");
                    } else {
                        $cnx_sql=true ;
                    }
                } catch(Exception $e) {
                    // En cas d'erreur, on affiche un message et on arrête tout
                    die('Erreur : '.$e->getMessage());
                }
            } else {
                $MsgConnexion[1]="<p>Connection sur BDD distante";
                try {
                    //$bdd = new PDO('mysql:host=sql.free.fr;dbname=zach_catalogue;charset=utf8', 'zach.catalogue@free.fr', 'lolo150570');
                    $mysqli = new mysqli('mysql.hostinger.fr', 'u315704242_zach', 'lolo150570', 'u315704242_catal');
                    if ($mysqli->connect_errno) {
                        echo "Errno: " . $mysqli->connect_errno . "\n";
                        die("Error: " . $mysqli->connect_error . "\n");
                    } else {
                        $cnx_sql=true ;
                    }
                } catch(Exception $e) {
                    // En cas d'erreur, on affiche un message et on arrête tout
                    die('Erreur : '.$e->getMessage());
                }
            }
			$Zach_Verif=0;
			if (isset($_SESSION['Zach_Id'])){
				$Zach_User=$_SESSION['Zach_User'];
				$Zach_Verif=strlen($_SESSION['Zach_User']);
			}
            $MsgConnexion[2]="<BR>Processus de connexion passé </p>";
            ?>

