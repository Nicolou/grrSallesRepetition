<?php
/**
 * view_room.php
 * Fiche ressource
 * Ce script fait partie de l'application GRR
 * Derni�re modification : $Date: 2009-06-04 15:30:17 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: view_room.php,v 1.11 2009-06-04 15:30:17 grr Exp $
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
 * $Log: view_room.php,v $
 * Revision 1.11  2009-06-04 15:30:17  grr
 * *** empty log message ***
 *
 * Revision 1.10  2009-04-14 12:59:17  grr
 * *** empty log message ***
 *
 * Revision 1.9  2009-04-09 14:52:31  grr
 * *** empty log message ***
 *
 * Revision 1.8  2009-02-27 22:05:03  grr
 * *** empty log message ***
 *
 * Revision 1.7  2009-02-27 13:28:20  grr
 * *** empty log message ***
 *
 * Revision 1.6  2009-01-20 07:19:17  grr
 * *** empty log message ***
 *
 * Revision 1.5  2008-11-16 22:00:59  grr
 * *** empty log message ***
 *
 *
 */
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include_once('include/misc.inc.php');
include "include/mrbs_sql.inc.php";
$grr_script_name = "view_room.php";
// Settings
require_once("./include/settings.inc.php");
//Chargement des valeurs de la table settingS
if (!loadSettings())
    die("Erreur chargement settings");

// Session related functions
require_once("./include/session.inc.php");
// Resume session
if ((!grr_resumeSession())and (getSettingValue("authentification_obli")==1)) {
    header("Location: ./logout.php?auto=1&url=$url");
    die();
};

// Param�tres langage
include "include/language.inc.php";

$id_room = isset($_GET["id_room"]) ? $_GET["id_room"] : NULL;
if (isset($id_room)) settype($id_room,"integer");
else
$print = "all";

if ((getSettingValue("authentification_obli")==0) and (getUserName()=='')) {
    $type_session = "no_session";
} else {
    $type_session = "with_session";
}

if(((authGetUserLevel(getUserName(),-1) < 1) and (getSettingValue("authentification_obli")==1)) or (!verif_acces_ressource(getUserName(), $id_room)))
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, '','');
    exit();
}

echo begin_page(get_vocab("mrbs").get_vocab("deux_points").getSettingValue("company"));

$res = grr_sql_query("SELECT * FROM ".TABLE_PREFIX."_room WHERE id=$id_room");
if (! $res) fatal_error(0, get_vocab('error_room') . $id_room . get_vocab('not_found'));

$row = grr_sql_row_keyed($res, 0);
grr_sql_free($res);

?>
<h3 style="text-align:center;"><?php echo get_vocab("room").get_vocab("deux_points")."&nbsp;".htmlspecialchars($row["room_name"]);

$id_area = mrbsGetRoomArea($id_room);
$area_name = grr_sql_query1("select area_name from ".TABLE_PREFIX."_area where id='".$id_area."'");
$area_access = grr_sql_query1("select access from ".TABLE_PREFIX."_area where id='".$id_area."'");
echo "<br />(".$area_name;
if ($area_access == 'r') echo " - ".get_vocab("access");
echo ")";
echo "</h3>";

if ($row['statut_room'] == "0")
    echo "<h2 style=\"text-align:center;\"><span class=\"avertissement\">".get_vocab("ressource_temporairement_indisponible")."</span></h2>";

echo "<h3>".get_vocab("description")."</h3>\n";
echo "<div>".htmlspecialchars($row["description"])."&nbsp;</div>\n";
if ($row["comment_room"] != '') {
    echo "<h3>".get_vocab("match_descr")."</h3>\n";
    echo "<div>".$row["comment_room"]."</div>\n";
}
if ($row["capacity"] != '0') {
    echo "<h3>".get_vocab("capacity_2")."</h3>\n";
    echo "<p>".$row["capacity"]."</p>\n";
}

if ($row["max_booking"] != "-1")
        echo "<p>".get_vocab("msg_max_booking").get_vocab("deux_points").$row["max_booking"]."</p>";
// Limitation par domaine
$max_booking_per_area = grr_sql_query1("SELECT max_booking FROM ".TABLE_PREFIX."_area WHERE id = '".protect_data_sql($id_area)."'");
if ($max_booking_per_area >= 0)
    echo "<p>".get_vocab("msg_max_booking_area").get_vocab("deux_points").$max_booking_per_area."</p>";


if ($row["delais_max_resa_room"] != "-1")
        echo "<p>".get_vocab("delais_max_resa_room_2")." <b>".$row["delais_max_resa_room"]."</b></p>";
if ($row["delais_min_resa_room"] != "0")
        echo "<p>".get_vocab("delais_min_resa_room_2")." <b>".$row["delais_min_resa_room"]."</b></p>";

$nom_picture = '';
if ($row['picture_room'] != '') $nom_picture = "./images/".$row['picture_room'];
echo "<div style=\"text-align:center; margin-top:30px\"><b>";
if (@file_exists($nom_picture) && $nom_picture) {
   echo get_vocab("Image de la ressource").": </b><br /><img src=\"".$nom_picture."\" alt=\"logo\" />";
} else {
   echo get_vocab("Pas image disponible")."</b>";
}
echo "</div>";
include "include/trailer.inc.php";