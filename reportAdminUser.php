<?php
/**
 * reportAdminUser.php
 * interface afficheant un rapport des réservations
 * Ce script fait partie de l'application GRR
 * Dernière modification : $Date: 2009-09-29 18:02:57 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: report_master.php,v 1.15 2009-09-29 18:02:57 grr Exp $
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
 * $Log: report_master.php,v $
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

$grr_script_name = "reportAdminUser.php";
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
echo begin_page('report_master');


$adminUser = "NotSet";
if (isset($_POST['zicos'])) $adminUser = $_POST['zicos'];
else {
	echo '<p> pas d\'utilisateur s&eacute;lectionn&eacute;</p>';
	exit();
}

// on continue

echo '
<H3>Gestion du groupe '. $adminUser  .'</h3>
<p>nombre d`heures restantes: ' . getCreditNumberHourForfait($adminUser) . '</p>
<p>
</p>
<!-- liste des répéttiions faites !-->
<table width="400" border="3" cellspacing="4">
	<tbody><b>Liste des r&eacute;p&eacute;titons effectu&eacute;es</b></tbody>    
    <tr>
      <td>
      	<b>quand</b>
      </td>
      <td>
      	<b>nbr d\'heure</b>
      </td>
     </tr>
     ';
$res = getRowsListRepetitionsFaites($adminUser,'le %d.%m.%Y   a %H:%i');
if ($res) {
	for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
		echo '<tr><td>' . $row[0] . '</td><td>' .  round($row[1],1)  . '</td></tr>';
	}
}
echo '</table>';

echo '
<p>
</p>
<!-- liste des forfait pris !-->
<table width="400" border="3" cellspacing="4">
	<tbody><b>Liste des forfaits</b></tbody>    
    <tr>
      <td>
      	<b>quand</b>
      </td>
      <td>
      	<b>nbr d\'heures<br>du forfait</b>
      </td>
      <td>
      	<b><form method="post" action="admin_AddForfait.php">
			<INPUT TYPE="hidden" name="zicos" VALUE="'. $adminUser . '" />   
			<INPUT TYPE="submit" VALUE="Ajouter" title="ajouter un forfait" />   	
      	</form></b>
      </td>
     </tr>
     ';
$res = getRowsListForfaitPris($adminUser);
if ($res) {
	for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
		echo '<tr><td>' . $row[0] . '</td><td>' .  round($row[1],1)  . '</td>
		<td>
      	<b><form method="post" action="admin_DelForfait.php">
			<INPUT TYPE="hidden" name="idForfait" VALUE="'. $row[2] . '" />   
			<INPUT TYPE="hidden" name="zicos" VALUE="'. $adminUser . '" />   
			<INPUT TYPE="submit" VALUE="Suppr." title="supprimer le forfait" />   	
      	</form></b>
      </td>		
		</tr>';
	}
}
echo '</table>';


echo '
<p>
</p>
<!-- liste des réajustements (ou inventaires) de forfait !-->
<table width="600" border="3" cellspacing="4">
	<tbody><b>Liste des r&eacute;ajustements(ou inventaires) de forfaits</b></tbody>    
    <tr>
      <td>
      	<b>quand</b>
      </td>
      <td>
      	<b>nbr d\'heures<br>restantes</b>
      </td>
      <td>
      	<b>description de la mise &agrave; jour</b>
      </td>
      <td>
      	<b><form method="post" action="admin_AddInvForfait.php">
			<INPUT TYPE="hidden" name="zicos" VALUE="'. $adminUser . '" />   
			<INPUT TYPE="submit" VALUE="Ajouter" title="ajouter un réajustement" />   	
      	</form></b>
      </td>
     </tr>
     ';
$res = getRowsListForfaitPointage($adminUser);
if ($res) {
	for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
		echo '<tr><td>' . $row[0] . '</td><td>' .  round($row[1],1)  . '</td><td>' .  $row[2]  . '</td>
		<td>
      	<b><form method="post" action="admin_DelInvForfait.php">
			<INPUT TYPE="hidden" name="idForfait" VALUE="'. $row[3] . '" />   
			<INPUT TYPE="hidden" name="zicos" VALUE="'. $adminUser . '" />   
			<INPUT TYPE="submit" VALUE="Suppr." title="supprimer le forfait" />   	
      	</form></b>
      </td>		
		</tr>';
	}
}
echo '</table>';

//fin de la page
include "include/trailer.inc.php";


?>