<?php
/**
 * admin_AddForfait.php
 * interface afficheant un rapport des réservations
 * Ce script fait partie de l'application GRR
 * Dernière modification : $Date: 2009-09-29 18:02:57 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: report.php,v 1.15 2009-09-29 18:02:57 grr Exp $
 * @filesource
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GRR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GRR; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
/**
 * $Log: report.php,v $
 * Revision 1.15  2009-09-29 18:02:57  grr
 * *** empty log message ***
 *
 * Revision 1.14  2009-04-14 12:59:17  grr
 * *** empty log message ***
 *
 * Revision 1.13  2009-04-09 14:52:31  grr
 * *** empty log message ***
 *
 *
 *
 */

include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mrbs_sql.inc.php";

$grr_script_name = "admin_AddInvForfait.php";
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

$day   = date("d");
$month = date("m");
$year  = date("Y");

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


// Construction des identifiants de la ressource $room, du domaine $area, du site $id_site
//Definition_ressource_domaine_site();


//                -------------------------------------------------------- debut page
echo begin_page('add forfait');
echo '<SCRIPT LANGUAGE="JavaScript" SRC="./include/CalendarPopup.js"></SCRIPT>';
?>	
echo '<SCRIPT LANGUAGE="JavaScript">

function verif_form(id)
  { //Fonction prenant nu nombre dans une zone de texte : test si c'est un chiffre si oui le convertit en decimal(10e-2) sinon tante de le convertir en decimal
 	// efface le contenu de la zone de saisie si convertion impossible
 	// arrondit a 2 chiffres si il s'agit deja d'un decimal
 	var d=document.getElementById(id);
 	if (d.value!='')
	 	{
 		if (isNaN(d.value)==true)//si on tombe sur une virgule la valeur n'est pas considérée comme un nombre
 			{
 			Num=d.value.indexOf(',');
 			//on remplace la virgule par un point
 			Resultat=d.value.substring(0,Num)+'.'+d.value.substring(Num+1,d.value.length);d.value=Resultat;d.value=Math.round(d.value*100)/100;
 			if (isNaN(d.value)==true)
 				{
 					d.value='';
					alert('VOUS DEVEZ SAISIR UN NOMBRE DECIMAL OU ENTIER');
 					return false;
 			}
 		}

 		Temp=Math.round(d.value*10)/10;// on arrodi a 1 chiffre si decimal a plus de 1 chiffres
 		d.value=Temp;
	 	return true;
	 }
 } 

</SCRIPT>

<?php

$adminUser = "NotSet";
if (isset($_POST['zicos'])) $adminUser = $_POST['zicos'];
else {
	if (isset($_GET['zicos'])) $adminUser = $_GET['zicos'];	
	else	
		{		
		echo '<p> pas d\'utilisateur s&eacute;lectionn&eacute;</p>';
		exit();
		}
}

// on continue
echo '<H1>Ajout d\'un forfait pour ' . $adminUser . '</H1>
		<form id="myform" method="post" action="admin_AddingInvForfait.php" onSubmit="return verif_form(\'heures\')">
		<p>nombre d\'heures inventori&eacute;es : 
		<input type="text" name="heures" id="heures"\>
		</p>
		<p>&agrave; la date du :
		';	
genDateSelector("", $day, $month, $year,"");
echo '
		</p>		
		<p>raison de l\'inventaire : 
		<input type="text" name="cause" \> 
		</p>
		<input type="hidden" name="zicos" value = "'. $adminUser .'" \>
		<input type="submit" value="Valider" title="Ajouter le forfait" \>
		</form>
		';


//fin de la page
include "include/trailer.inc.php";
  
?>