<?php
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mrbs_sql.inc.php";

$grr_script_name = "admin_AddingInvForfait.php";
    #Paramètres de connection
require_once("./include/settings.inc.php");
    #Chargement des valeurs de la table settings
if (!loadSettings())
    die("Erreur chargement settings");

    #Fonction relative à la session
require_once("./include/session.inc.php");
    #Si il n'y a pas de session crée, on déconnecte l'utilisateur.
// Resume session
if ((!grr_resumeSession())and (getSettingValue("authentification_obli")==1)) {
    header("Location: ./logout.php?auto=1&url=$url");
    die();
};

// Paramètres langage
include "include/language.inc.php";

// On affiche le lien "format imprimable" en bas de la page
if (!isset($_GET['pview'])) $_GET['pview'] = 0; else $_GET['pview'] = 1;

    #Récupération des informations relatives au serveur.
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
    #Renseigne les droits de l'utilisateur, si les droits sont insufisants, l'utilisateur est avertit.


// vérifi ation du droit d'acces a cette page
if (!verif_access_search(getUserName()))
{
    showAccessDenied($day, $month, $year, '',$back);
    exit();
}
else {
			if ( authGetUserLevel(getUserName(),-1) < 3 ) { 	          
    			showAccessDenied($day, $month, $year, '',$back);
    			exit();
          } 
      }
//                -------------------------------------------------------- debut page
echo begin_page('AddingForfait');   
//verif des parametre passes

// debug
//foreach($_POST as $key=>$val) {
// echo $key.'=>'.$val.'<p>'; 
// }

$adminUser = "NotSet";
$heures = "NotSet";
$dateForfait = "NotSet";
$cause = "-";

if (isset($_POST['cause'])) $cause = $_POST['cause'];
if (isset($_POST['zicos'])) $adminUser = $_POST['zicos'];
else {	
	echo '<p> pas d\'utilisateur s&eacute;lectionn&eacute;</p>';
	exit();
}  
if (isset($_POST['heures'])) $heures = $_POST['heures'];

else {	
		echo '<p> nombre d\'heures ind&eacute;termin&eacute;es</p>';
		exit();
}   
if (isset($_POST['day'])) $day = $_POST['day'];
if (isset($_POST['month'])) $month = $_POST['month'];
if (isset($_POST['year'])) $year = $_POST['year'];
if (!isset($_POST['day']) or !isset($_POST['month']) or !isset($_POST['year'])) {	
	echo '<p> date de l\'inventaire non d&eacute;termin&eacute;e</p>';
	exit();
}  
$dateForfait = $year . '-' . $month . '-' . $day . ' ' . date('H:i:s');

echo '
<p> ajout de ' . $heures . '</p>
<p> pour le ' . $day . '/' . $month . '/' . $year . '</p>
<p> en faveur du groupe '. $adminUser . '</p>
';


//renvoi de useradmin
echo '<form action="reportAdminUser.php" name="myForm" method="post">
	<input type="hidden" name="zicos" value="'. $adminUser . '" \>
	</form>';


$req = 'insert into grr_forfait_archive set USER_ID=\'' . $adminUser . '\', USER_DO=\'' . getUserName() . '\' '
	. ', QT_CREDIT=' . $heures . ', BLDT=\'' . $dateForfait . '\', WHY=\'' . $cause . '\'';
	

$msg = 'message a afficher par pop pup';	
if (grr_sql_command($req) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  
	else {$msg= 'Ajout du forfait reussi';}
affiche_pop_up($msg,"admin");

//recharge de la page de depart

echo '<script type="text/javascript">document.myForm.submit();</script>';
      
//fin de la page
include "include/trailer.inc.php";
  
?>
