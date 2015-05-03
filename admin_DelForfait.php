<?php
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mrbs_sql.inc.php";

$grr_script_name = "admin_DelForfait.php";
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
$idForfait = 0;
if (isset($_POST['zicos'])) $adminUser = $_POST['zicos'];
else {	
	echo '<p> pas d\'utilisateur s&eacute;lectionn&eacute;</p>';
	exit();
}  
if (isset($_POST['idForfait'])) $idForfait = $_POST['idForfait'];

else {	
		echo '<p> nombre d\'heures du forfait ind&eacute;termin&eacute;es</p>';
		exit();
}   

//renvoi de useradmin
echo '<form action="reportAdminUser.php" name="myForm" method="post">
	<input type="hidden" name="zicos" value="'. $adminUser . '" \>
	</form>';


$req = 'delete from grr_forfait_credit where ID=' .  $idForfait ;
	

$msg = 'message a afficher par pop pup';	
if (grr_sql_command($req) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  
	else {$msg= 'suppression du forfait reussi';}
affiche_pop_up($msg,"admin");

//recharge de la page de depart

echo '<script type="text/javascript">document.myForm.submit();</script>';
      


//fin de la page
include "include/trailer.inc.php";
  
?>