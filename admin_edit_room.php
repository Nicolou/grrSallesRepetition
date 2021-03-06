<?php
/**
 * admin_edit_room.php
 * Interface de cr�ation/modification
 * des sites, domaines et des ressources de l'application GRR
 * Derni�re modification : $Date: 2009-09-29 18:02:56 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @author    Marc-Henri PAMISEUX <marcori@users.sourceforge.net>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @copyright Copyright 2008 Marc-Henri PAMISEUX
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   admin
 * @version   $Id: admin_edit_room.php,v 1.15 2009-09-29 18:02:56 grr Exp $
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
 * $Log: admin_edit_room.php,v $
 * Revision 1.15  2009-09-29 18:02:56  grr
 * *** empty log message ***
 *
 * Revision 1.14  2009-06-04 15:30:17  grr
 * *** empty log message ***
 *
 * Revision 1.13  2009-04-14 12:59:17  grr
 * *** empty log message ***
 *
 * Revision 1.12  2009-04-09 14:52:31  grr
 * *** empty log message ***
 *
 * Revision 1.11  2009-03-24 13:30:07  grr
 * *** empty log message ***
 *
 * Revision 1.10  2009-02-27 13:28:19  grr
 * *** empty log message ***
 *
 * Revision 1.9  2009-01-20 07:19:17  grr
 * *** empty log message ***
 *
 * Revision 1.8  2008-11-16 22:00:58  grr
 * *** empty log message ***
 *
 * Revision 1.7  2008-11-13 21:32:51  grr
 * *** empty log message ***
 *
 * Revision 1.6  2008-11-11 22:01:14  grr
 * *** empty log message ***
 *
 * Revision 1.5  2008-11-06 21:57:34  grr
 * *** empty log message ***
 *
 *
 */
include "include/admin.inc.php";
$grr_script_name = "admin_edit_room.php";
$ok = NULL;
if (getSettingValue("module_multisite") == "Oui")
    $id_site = isset($_POST["id_site"]) ? $_POST["id_site"] : (isset($_GET["id_site"]) ? $_GET["id_site"] : -1);
$add_area = isset($_POST["add_area"]) ? $_POST["add_area"] : (isset($_GET["add_area"]) ? $_GET["add_area"] : NULL);
$area_id = isset($_POST["area_id"]) ? $_POST["area_id"] : (isset($_GET["area_id"]) ? $_GET["area_id"] : NULL);
$retour_page = isset($_POST["retour_page"]) ? $_POST["retour_page"] : (isset($_GET["retour_page"]) ? $_GET["retour_page"] : NULL);
$room = isset($_POST["room"]) ? $_POST["room"] : (isset($_GET["room"]) ? $_GET["room"] : NULL);
$id_area = isset($_POST["id_area"]) ? $_POST["id_area"] : (isset($_GET["id_area"]) ? $_GET["id_area"] : NULL);
$change_area = isset($_POST["change_area"]) ? $_POST["change_area"] : NULL;
$area_name = isset($_POST["area_name"]) ? $_POST["area_name"] : NULL;
$access = isset($_POST["access"]) ? $_POST["access"] : NULL;
$ip_adr = isset($_POST["ip_adr"]) ? $_POST["ip_adr"] : NULL;
$room_name = isset($_POST["room_name"]) ? $_POST["room_name"] : NULL;
$description = isset($_POST["description"]) ? $_POST["description"] : NULL;
$capacity = isset($_POST["capacity"]) ? $_POST["capacity"] : NULL;
$duree_max_resa_area1  = isset($_POST["duree_max_resa_area1"]) ? $_POST["duree_max_resa_area1"] : NULL;
$duree_max_resa_area2  = isset($_POST["duree_max_resa_area2"]) ? $_POST["duree_max_resa_area2"] : NULL;
$delais_max_resa_room  = isset($_POST["delais_max_resa_room"]) ? $_POST["delais_max_resa_room"] : NULL;
$delais_min_resa_room  = isset($_POST["delais_min_resa_room"]) ? $_POST["delais_min_resa_room"] : NULL;
$delais_option_reservation  = isset($_POST["delais_option_reservation"]) ? $_POST["delais_option_reservation"] : NULL;
$allow_action_in_past  = isset($_POST["allow_action_in_past"]) ? $_POST["allow_action_in_past"] : NULL;
$dont_allow_modify  = isset($_POST["dont_allow_modify"]) ? $_POST["dont_allow_modify"] : NULL;
$qui_peut_reserver_pour  = isset($_POST["qui_peut_reserver_pour"]) ? $_POST["qui_peut_reserver_pour"] : NULL;
$who_can_see  = isset($_POST["who_can_see"]) ? $_POST["who_can_see"] : NULL;
$max_booking = isset($_POST["max_booking"]) ? $_POST["max_booking"] : NULL;
settype($max_booking,"integer");
if ($max_booking<-1) $max_booking = -1;

$statut_room = isset($_POST["statut_room"]) ? "0" : "1";
$show_fic_room = isset($_POST["show_fic_room"]) ? "y" : "n";
if (isset($_POST["active_ressource_empruntee"])) {
    $active_ressource_empruntee = 'y';
} else {
    $active_ressource_empruntee = 'n';
    // toutes les r�servations sont consid�r�es comme restitu�e
    grr_sql_query("update ".TABLE_PREFIX."_entry set statut_entry = '-' where room_id = '".$room."'");
}
$picture_room = isset($_POST["picture_room"]) ? $_POST["picture_room"] : NULL;
$comment_room = isset($_POST["comment_room"]) ? $_POST["comment_room"] : NULL;
$change_done = isset($_POST["change_done"]) ? $_POST["change_done"] : NULL;
$area_order = isset($_POST["area_order"]) ? $_POST["area_order"] : NULL;
$room_order = isset($_POST["room_order"]) ? $_POST["room_order"] : NULL;
$change_room = isset($_POST["change_room"]) ? $_POST["change_room"] : NULL;
$number_periodes = isset($_POST["number_periodes"]) ? $_POST["number_periodes"] : NULL;
$type_affichage_reser = isset($_POST["type_affichage_reser"]) ? $_POST["type_affichage_reser"] : NULL;
$retour_resa_obli = isset($_POST["retour_resa_obli"]) ? $_POST["retour_resa_obli"] : NULL;
$moderate = isset($_POST['moderate']) ? $_POST["moderate"] : NULL;
if ($moderate == 'on') $moderate = 1;
else $moderate = 0;
settype($type_affichage_reser,"integer");

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);

if (isset($_POST["change_room_and_back"])) {
    $change_room = "yes";
    $change_done = "yes";
}

if (isset($_POST["change_area_and_back"])) {
    $change_area = "yes";
    $change_done = "yes";
}


// m�morisation du chemin de retour
if (!isset($retour_page)) {
    $retour_page = $back;
    // on nettoie la chaine :
    $long_chaine_a_supprimer = strlen(strstr($retour_page,"&amp;msg=")); // longueur de la chaine � partir de la premi�re occurence de &amp;msg=
	if ($long_chaine_a_supprimer==0) $long_chaine_a_supprimer = strlen(strstr($retour_page,"?msg="));
    $long = strlen($retour_page) - $long_chaine_a_supprimer;
    $retour_page = substr($retour_page,0,$long);
}
$day   = date("d");
$month = date("m");
$year  = date("Y");

// modification d'une resource : admin ou gestionnaire
if (authGetUserLevel(getUserName(),-1) < 6)
{
    if (isset($room))
      {
        // Il s'agit d'une modif de ressource
        if (((authGetUserLevel(getUserName(),$room) < 3))  or (!verif_acces_ressource(getUserName(), $room))) {
            showAccessDenied($day, $month, $year, '',$back);
            exit();
        }
    } else {
        if (isset($area_id)) {
            // On v�rifie que le domaine $area_id existe
            $test = grr_sql_query1("select id from ".TABLE_PREFIX."_area where id='".$area_id."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, '',$back);
                exit();
            }
            // Il s'agit de l'ajout d'une ressource
            // On v�rifie que l'utilisateur a le droit d'ajouter des ressources
            if ((authGetUserLevel(getUserName(),$area_id,'area') < 4)) {
                showAccessDenied($day, $month, $year, '',$back);
               exit();
            }
        } else if (isset($id_area)) {
            // On v�rifie que le domaine $area existe
            $test = grr_sql_query1("select id from ".TABLE_PREFIX."_area where id='".$id_area."'");
            if ($test == -1) {
                showAccessDenied($day, $month, $year, '',$back);
                exit();
            }
            // Il s'agit de la modif d'un domaine
            if ((authGetUserLevel(getUserName(),$id_area,'area') < 4)) {
                showAccessDenied($day, $month, $year, '',$back);
               exit();
            }
        }
    }
}

$msg ='';

// Gestion des ressources
if ((!empty($room)) or (isset($area_id))) {

    // Enregistrement d'une ressource
    if (isset($change_room))
    {
        if (isset($_POST['sup_img'])) {
            $dest = './images/';
            $ok1 = false;
            if ($f = @fopen("$dest/.test", "w")) {
                @fputs($f, '<'.'?php $ok1 = true; ?'.'>');
                @fclose($f);
                include("$dest/.test");
            }
            if (!$ok1) {
                $msg .= "L\'image n\'a pas pu �tre supprim�e : probl�me d\'�criture sur le r�pertoire. Veuillez signaler ce probl�me � l\'administrateur du serveur.\\n";
                $ok = 'no';
            } else {
                if (@file_exists($dest."img_".$room.".jpg")) unlink($dest."img_".$room.".jpg");
                if (@file_exists($dest."img_".$room.".png")) unlink($dest."img_".$room.".png");
                if (@file_exists($dest."img_".$room.".gif")) unlink($dest."img_".$room.".gif");

                $picture_room = "";
            }
        }
        if (empty($capacity)) $capacity = 0;
        if ($capacity<0) $capacity = 0;
        settype($delais_max_resa_room,"integer");
        if ($delais_max_resa_room<0) $delais_max_resa_room = -1;
        settype($delais_min_resa_room,"integer");
        if ($delais_min_resa_room<0) $delais_min_resa_room = 0;
        settype($delais_option_reservation,"integer");
        if ($delais_option_reservation<0) $delais_option_reservation = 0;
        if ($allow_action_in_past == '') $allow_action_in_past = 'n';
        if ($dont_allow_modify == '') $dont_allow_modify = 'n';
        if (isset($room)) {
            $sql = "UPDATE ".TABLE_PREFIX."_room SET
            room_name='".protect_data_sql($room_name)."',
            description='".protect_data_sql($description)."', ";
            if ($picture_room != '') $sql .= "picture_room='".protect_data_sql($picture_room)."', ";
            $sql .= "comment_room='".protect_data_sql(corriger_caracteres($comment_room))."',
            show_fic_room='".$show_fic_room."',
            active_ressource_empruntee = '".$active_ressource_empruntee."',
            capacity='".$capacity."',
            delais_max_resa_room='".$delais_max_resa_room."',
            delais_min_resa_room='".$delais_min_resa_room."',
            delais_option_reservation='".$delais_option_reservation."',
            allow_action_in_past='".$allow_action_in_past."',
            dont_allow_modify='".$dont_allow_modify."',
            qui_peut_reserver_pour = '".$qui_peut_reserver_pour."',
            who_can_see = '".$who_can_see."',
            order_display='".protect_data_sql($area_order)."',
            type_affichage_reser='".$type_affichage_reser."',
            max_booking='".$max_booking."',
            moderate='".$moderate."',
            statut_room='".$statut_room."'
            WHERE id=$room";
            if (grr_sql_command($sql) < 0) {
                fatal_error(0, get_vocab('update_room_failed') . grr_sql_error());
                $ok = 'no';
            }
        } else {
            $sql = "insert into ".TABLE_PREFIX."_room
            SET room_name='".protect_data_sql($room_name)."',
            area_id='".$area_id."',
            description='".protect_data_sql($description)."',
            picture_room='".protect_data_sql($picture_room)."',
            comment_room='".protect_data_sql(corriger_caracteres($comment_room))."',
            show_fic_room='".$show_fic_room."',
            active_ressource_empruntee = '".$active_ressource_empruntee."',
            capacity='".$capacity."',
            delais_max_resa_room='".$delais_max_resa_room."',
            delais_min_resa_room='".$delais_min_resa_room."',
            delais_option_reservation='".$delais_option_reservation."',
            allow_action_in_past='".$allow_action_in_past."',
            dont_allow_modify='".$dont_allow_modify."',
            qui_peut_reserver_pour = '".$qui_peut_reserver_pour."',
            who_can_see = '".$who_can_see."',
            order_display='".protect_data_sql($area_order)."',
            type_affichage_reser='".$type_affichage_reser."',
            max_booking='".$max_booking."',
            moderate='".$moderate."',
            statut_room='".$statut_room."'";
            if (grr_sql_command($sql) < 0) fatal_error(1, "<p>" . grr_sql_error());
            $room = mysql_insert_id();
        }

		#Si room_name est vide on le change maintenant que l'on a l'id room
		if ($room_name == '') {
			$room_name = get_vocab("room")." ".$room;
			grr_sql_command("UPDATE ".TABLE_PREFIX."_room SET room_name='".protect_data_sql($room_name)."' WHERE id=$room");
		}

        $doc_file = isset($_FILES["doc_file"]) ? $_FILES["doc_file"] : NULL;
        if (preg_match("`\.([^.]+)$`", $doc_file['name'], $match)) {
            $ext = strtolower($match[1]);
            if ($ext!='jpg' and $ext!='png'and $ext!='gif') {
                $msg .= "L\'image n\'a pas pu �tre enregistr�e : les seules extentions autoris�es sont gif, png et jpg.\\n";
                $ok = 'no';
            } else {
                $dest = './images/';
                $ok1 = false;
                if ($f = @fopen("$dest/.test", "w")) {
                    @fputs($f, '<'.'?php $ok1 = true; ?'.'>');
                    @fclose($f);
                    include("$dest/.test");
                }
                if (!$ok1) {
                    $msg .= "L\'image n\'a pas pu �tre enregistr�e : probl�me d\'�criture sur le r�pertoire IMAGES. Veuillez signaler ce probl�me � l\'administrateur du serveur.\\n";
                    $ok = 'no';
                } else {
                    $ok1 = @copy($doc_file['tmp_name'], $dest.$doc_file['name']);
                    if (!$ok1) $ok1 = @move_uploaded_file($doc_file['tmp_name'], $dest.$doc_file['name']);
                    if (!$ok1) {
                        $msg .= "L\'image n\'a pas pu �tre enregistr�e : probl�me de transfert. Le fichier n\'a pas pu �tre transf�r� sur le r�pertoire IMAGES. Veuillez signaler ce probl�me � l\'administrateur du serveur.\\n";
                        $ok = 'no';
                    } else {
                        $tab = explode(".", $doc_file['name']);
                        $ext = strtolower($tab[1]);

                        if (@file_exists($dest."img_".$room.".".$ext)) @unlink($dest."img_".$room.".".$ext);
                        rename($dest.$doc_file['name'],$dest."img_".$room.".".$ext);
                        @chmod($dest."img_".$room.".".$ext, 0666);
                        $picture_room = "img_".$room.".".$ext;
                        $sql_picture = "UPDATE ".TABLE_PREFIX."_room SET picture_room='".protect_data_sql($picture_room)."' WHERE id=".$room;
                        if (grr_sql_command($sql_picture) < 0) {
                            fatal_error(0, get_vocab('update_room_failed') . grr_sql_error());
                            $ok = 'no';
                        }
                    }
               }
           }
        } else if ($doc_file['name'] != '') {
           $msg .= "L\'image n\'a pas pu �tre enregistr�e : le fichier image s�lectionn� n'est pas valide !\\n";
           $ok = 'no';
        }
        $msg .= get_vocab("message_records");

    }
    // Si pas de probl�me, retour � la page d'accueil apr�s enregistrement
    if ((isset($change_done)) and (!isset($ok))) {
        if ($msg != '') {
            $_SESSION['displ_msg'] = 'yes';
            if (strpos($retour_page, ".php?") == "") $param = "?msg=".$msg; else $param = "&msg=".$msg;
        } else
            $param = '';

        Header("Location: ".$retour_page.$param);
        exit();
    }

    # print the page header
    print_header("","","","",$type="with_session", $page="admin");
    affiche_pop_up($msg,"admin");
    echo "<div class=\"page_sans_col_gauche\">";

    // affichage du formulaire
    if (isset($room)) {
        // Il s'agit d'une modification d'une ressource
        $res = grr_sql_query("SELECT * FROM ".TABLE_PREFIX."_room WHERE id=$room");
        if (! $res) fatal_error(0, get_vocab('error_room') . $room . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        $temp = grr_sql_query1("select area_id from ".TABLE_PREFIX."_room where id='".$room."'");
        $area_name = grr_sql_query1("select area_name from ".TABLE_PREFIX."_area where id='".$temp."'");
        echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br />".get_vocab("editroom")."</h2>\n";
    } else {
        // Il s'agit de l'enregistrement d'une nouvelle ressource
        $row['picture_room'] = '';
        $row["id"] = '';
        $row["room_name"]= '';
        $row["description"] = '';
        $row['comment_room'] = '';
        $row["capacity"]   = '';
        $row["delais_max_resa_room"] = -1;
        $row["delais_min_resa_room"] = 0;
        $row["delais_option_reservation"] = 0;
        $row["allow_action_in_past"] = 'n';
        $row["dont_allow_modify"] = 'n';
        $row["qui_peut_reserver_pour"] = 6;
        $row["who_can_see"] = 0;
        $row["order_display"]  = 0;
        $row["type_affichage_reser"]  = 0;
        $row["max_booking"] = -1;
        $row['statut_room'] = '';
        $row['moderate'] = '';
        $row['show_fic_room'] = '';
        $row['active_ressource_empruntee'] = 'n';
        $area_name = grr_sql_query1("select area_name from ".TABLE_PREFIX."_area where id='".$area_id."'");
        echo "<h2>".get_vocab("match_area").get_vocab('deux_points')." ".$area_name."<br />".get_vocab("addroom")."</h2>\n";

    }
    ?>
    <form enctype="multipart/form-data" action="admin_edit_room.php" method="post">

    <?php
    echo "<div>";
    if ($row["id"] != '') echo "<input type=\"hidden\" name=\"room\" value=\"".$row["id"]."\" />\n";
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\" />\n";
    if (isset($area_id)) echo "<input type=\"hidden\" name=\"area_id\" value=\"".$area_id."\" />\n";
    echo "</div>";
    $nom_picture = '';
    if ($row['picture_room'] != '') $nom_picture = "./images/".$row['picture_room'];

    if (getSettingValue("use_fckeditor") == 1) {
        // lancement de FCKeditor
        include("./fckeditor/fckeditor.php") ;
        $oFCKeditor = new FCKeditor('comment_room') ;
        $oFCKeditor->BasePath = './fckeditor/' ;
        $oFCKeditor->Config['DefaultLanguage']  = 'fr' ;
        $oFCKeditor->Height = '300' ;
        $oFCKeditor->Config['CustomConfigurationsPath'] = '../fckconfig_grr.js';
        $oFCKeditor->ToolbarSet = 'Basic_Grr';
        $oFCKeditor->Value = $row['comment_room'] ;
    }
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
    echo "<tr><td>".get_vocab("name").get_vocab("deux_points")."</td><td style=\"width:30%;\">\n";
    // seul l'administrateur peut modifier le nom de la ressource
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=4) or (authGetUserLevel(getUserName(),$room) >=4)) {
        echo "<input type=\"text\" name=\"room_name\" size=\"40\" value=\"".htmlspecialchars($row["room_name"])."\" />\n";
    } else {
        echo "<input type=\"hidden\" name=\"room_name\" value=\"".htmlspecialchars($row["room_name"])."\" />\n";
        echo "<b>".htmlspecialchars($row["room_name"])."</b>\n";
    }
    echo "</td></tr>\n";
    // Description
    echo "<tr><td>".get_vocab("description")."</td><td><input type=\"text\" name=\"description\"  size=\"40\" value=\"".htmlspecialchars($row["description"])."\" /></td></tr>\n";
    // Ordre d'affichage du domaine
    echo "<tr><td>".get_vocab("order_display").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"area_order\" size=\"1\" value=\"".htmlspecialchars($row["order_display"])."\" /></td>\n";
    echo "</tr>\n";
    // Qui peut voir cette ressource
    echo "<tr><td colspan=\"2\">".get_vocab("qui_peut_voir_ressource")."<br />\n";
    echo "<select name=\"who_can_see\" size=\"1\">\n
    <option value=\"0\" ";
    if ($row["who_can_see"]==0) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description0")."</option>\n
    <option value=\"1\" ";
    if ($row["who_can_see"]==1) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description1")."</option>\n
    <option value=\"2\" ";
    if ($row["who_can_see"]==2) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description2")."</option>\n
    <option value=\"3\" ";
    if ($row["who_can_see"]==3) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description3")."</option>\n
    <option value=\"4\" ";
    if ($row["who_can_see"]==4) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description4")."</option>\n";
    if (getSettingValue("module_multisite") != "Oui") {
        echo "<option value=\"5\" ";
        if ($row["who_can_see"]==5) echo " selected=\"selected\" ";
        echo ">".get_vocab("visu_fiche_description5")."</option>\n";
    };
    echo "<option value=\"6\" ";
    if ($row["who_can_see"]==6) echo " selected=\"selected\" ";
    echo ">".get_vocab("visu_fiche_description6")."</option>\n
    </select></td></tr>\n";

	// D�clarer ressource indisponible
    echo "<tr><td>".get_vocab("declarer_ressource_indisponible")."<br /><i>".get_vocab("explain_max_booking")."</i></td>
    <td><input type=\"checkbox\" name=\"statut_room\" ";
    if ($row['statut_room'] == "0") echo " checked=\"checked\" ";
    echo "/></td></tr>\n";
    // Afficher la fiche de pr�sentation de la ressource
    echo "<tr><td>".get_vocab("montrer_fiche_pr�sentation_ressource")."</td>
    <td><input type=\"checkbox\" name=\"show_fic_room\" ";
    if ($row['show_fic_room'] == "y") echo " checked=\"checked\" ";
	echo "/><a href='javascript:centrerpopup(\"view_room.php?id_room=$room\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")' title=\"".get_vocab("fiche_ressource")."\">
	   <img src=\"img_grr/details.png\"  alt=\"d�tails\" class=\"image\"  /></a></td></tr>\n";
    // Choix de l'image de la ressource
    echo "<tr><td>".get_vocab("choisir_image_ressource")."</td>
    <td><input type=\"file\" name=\"doc_file\" size=\"30\" /></td></tr>\n";
	echo "<tr><td>".get_vocab("supprimer_image_ressource").get_vocab("deux_points");
	if (@file_exists($nom_picture)) {
    		echo "<b>$nom_picture</b></td><td><input type=\"checkbox\" name=\"sup_img\" /></td></tr>";}
		else{
			echo "<b>".get_vocab("nobody")."</b></td><td><input type=\"checkbox\" disabled=\"disabled\" name=\"sup_img\" /></td></tr>";
    }
    // Description compl�te
    echo "<tr><td colspan=\"2\">".get_vocab("description complete");
    if (getSettingValue("use_fckeditor") != 1)
        echo " ".get_vocab("description complete2");
    echo get_vocab("deux_points")."<br />";
    if (getSettingValue("use_fckeditor") == 1) {
        $oFCKeditor->Create() ;
    } else {
        echo "<textarea name=\"comment_room\" rows=\"8\" cols=\"120\" >".$row['comment_room']."</textarea>";
    }
    echo "</td></tr></table>\n";

    echo "<h3>".get_vocab("configuration_ressource")."</h3>\n";

    // Type d'affichage : dur�e ou heure/date de fin de r�servation
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">\n";
    echo "<tr><td>".get_vocab("type_affichage_reservation").get_vocab("deux_points")."</td>\n";
    echo "<td>";
    echo "<label><input type=\"radio\" name=\"type_affichage_reser\" value=\"0\" ";
    if (($row["type_affichage_reser"]) == 0) echo " checked=\"checked\" ";
    echo "/>";
    echo get_vocab("affichage_reservation_duree");
    echo "</label><br /><label><input type=\"radio\" name=\"type_affichage_reser\" value=\"1\" ";
    if (($row["type_affichage_reser"]) == 1) echo " checked=\"checked\" ";
    echo "/>";
    echo get_vocab("affichage_reservation_date_heure");
    echo "</label></td>\n";
    echo "</tr>\n";

    // Capacit�
    echo "<tr><td>".get_vocab("capacity").": </td><td><input type=\"text\" name=\"capacity\" size=\"1\" value=\"".$row["capacity"]."\" /></td></tr>\n";
    // seul les administrateurs de la ressource peuvent modifier le nombre max de r�servation par utilisateur
    if ((authGetUserLevel(getUserName(),$area_id,"area") >=4) or (authGetUserLevel(getUserName(),$room) >=4)) {
        echo "<tr><td>".get_vocab("max_booking")." ";
        echo grr_help("aide_grr_max_reservation");
        echo "</td><td><input type=\"text\" name=\"max_booking\" size=\"1\" value=\"".$row["max_booking"]."\" /></td></tr>";

    } else if ($row["max_booking"] != "-1") {
        echo "<tr><td>".get_vocab("msg_max_booking").get_vocab("deux_points")."</td><td>
        <input type=\"hidden\" name=\"max_booking\" value=\"".$row["max_booking"]."\" />
        <b>".htmlspecialchars($row["max_booking"])."</b>
        </td></tr>";
    }
    // L'utilisateur ne peut pas r�server au-del� d'un certain temps
    echo "<tr><td>".get_vocab("delais_max_resa_room").": </td><td><input type=\"text\" name=\"delais_max_resa_room\" size=\"1\" value=\"".$row["delais_max_resa_room"]."\" /></td></tr>\n";
    // L'utilisateur ne peut pas r�server en-dessous d'un certain temps
    echo "<tr><td>".get_vocab("delais_min_resa_room").": ";
    echo "</td><td><input type=\"text\" name=\"delais_min_resa_room\" size=\"5\" value=\"".$row["delais_min_resa_room"]."\" /></td></tr>\n";
    // L'utilisateur peut poser poser une option de r�servation
    echo "<tr><td>".get_vocab("msg_option_de_reservation")." ".grr_help("aide_grr_reservation_sous_reserve")."</td>
    <td><input type=\"text\" name=\"delais_option_reservation\" size=\"5\" value=\"".$row["delais_option_reservation"]."\" /></td></tr>\n";

    // Les demandes de r�servations sont mod�r�s
    echo "<tr><td>".get_vocab("msg_moderation_reservation").get_vocab("deux_points");
    echo grr_help("aide_grr_moderation");
    echo "</td>" .
      "<td><input type='checkbox' name='moderate' ";
    if ($row['moderate']) echo 'checked="checked"';
    echo " /></td></tr>\n";

    // L'utilisateur peut r�server dans le pass�
    echo "<tr><td>".get_vocab("allow_action_in_past")."<br /><i>".get_vocab("allow_action_in_past_explain")."</i></td><td><input type=\"checkbox\" name=\"allow_action_in_past\" value=\"y\" ";
    if ($row["allow_action_in_past"] == 'y') echo " checked=\"checked\"";
    echo " /></td></tr>\n";

    // L'utilisateur ne peut pas modifier ou supprimer ses propres r�servations
    echo "<tr><td>".get_vocab("dont_allow_modify")."</td><td><input type=\"checkbox\" name=\"dont_allow_modify\" value=\"y\" ";
    if ($row["dont_allow_modify"] == 'y') echo " checked=\"checked\"";
    echo " /></td></tr>\n";

    // Quels utilisateurs ont le droit de r�server cette ressource au nom d'un autre utilisateur ?
    echo "<tr><td>".get_vocab("qui peut reserver pour autre utilisateur")."</td><td>
    <select name=\"qui_peut_reserver_pour\" size=\"1\">\n
    <option value=\"5\" ";
    if ($row["qui_peut_reserver_pour"]==6) echo " selected=\"selected\" ";
    echo ">".get_vocab("personne")."</option>\n
    <option value=\"4\" ";
    if ($row["qui_peut_reserver_pour"]==4) echo " selected=\"selected\" ";
    echo ">".get_vocab("les administrateurs restreints")."</option>\n
    <option value=\"3\" ";
    if ($row["qui_peut_reserver_pour"]==3) echo " selected=\"selected\" ";
    echo ">".get_vocab("les gestionnaires de la ressource")."</option>\n
    <option value=\"2\" ";
    if ($row["qui_peut_reserver_pour"]==2) echo " selected=\"selected\" ";
    echo ">".get_vocab("tous les utilisateurs")."</option>\n
    </select></td></tr>\n";

    // Activer la fonctionalit� "ressource emprunt�e/restitu�e"
    echo "<tr><td>".get_vocab("activer fonctionalit� ressource empruntee restituee").grr_help("aide_grr_ressource_empruntee")."</td>
    <td><input type=\"checkbox\" name=\"active_ressource_empruntee\" ";
    if ($row['active_ressource_empruntee'] == "y") echo " checked=\"checked\" ";
    echo "/></td></tr>\n";
    echo "</table>\n";
    echo "<div style=\"text-align:center;\"><br />\n";
    echo "<input type=\"submit\" name=\"change_room\"  value=\"".get_vocab("save")."\" />\n";
    echo "<input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\" />";
    echo "<input type=\"submit\" name=\"change_room_and_back\" value=\"".get_vocab("save_and_back")."\" />";
    if (@file_exists($nom_picture) && $nom_picture) {
        echo "<br /><br /><b>".get_vocab("Image de la ressource").get_vocab("deux_points")."</b><br /><img src=\"".$nom_picture."\" alt=\"logo\" />";
    } else {
        echo "<br /><br /><b>".get_vocab("Pas image disponible")."</b>";
    }
    ?>
    </div>
    </form>
    </div>
<?php

}
// Ajout ou modification d'un domaine
if ((!empty($id_area)) or (isset($add_area)))
{
  if (isset($change_area)) {
    // Affectation � un site : si aucun site n'a �t� affect�
    if ((getSettingValue("module_multisite") == "Oui") and ($id_site==-1)) {
      // On affiche un message d'avertissement
      ?>
      <script type="text/javascript">
      alert("<?php echo get_vocab('choose_a_site'); ?>");
      </script>
      <?php
      // On emp�che le retour � la page admin_room
      unset($change_done);
    } else {
      // Un site a �t� affect�, on peut continuer
      // la valeur par d�faut ne peut �tre inf�riure au plus petit bloc r�servable
      if ($_POST['duree_par_defaut_reservation_area'] < $_POST['resolution_area']) $_POST['duree_par_defaut_reservation_area'] = $_POST['resolution_area'];
        // la valeur par d�faut doit �tre un multiple du plus petit bloc r�servable
        $_POST['duree_par_defaut_reservation_area']= intval($_POST['duree_par_defaut_reservation_area']/$_POST['resolution_area'])*$_POST['resolution_area'];
      // Dur�e maximale de r�servation
      if (isset($_POST['enable_periods'])) {
        if ($_POST['enable_periods'] == 'y')
          $duree_max_resa_area = $duree_max_resa_area2*1440;
        else {
          $duree_max_resa_area = $duree_max_resa_area1;
          if ($duree_max_resa_area >= 0)
              $duree_max_resa_area = max ($duree_max_resa_area, $_POST['resolution_area']/60, $_POST['duree_par_defaut_reservation_area']/60);
        }
        settype($duree_max_resa_area,"integer");
        if ($duree_max_resa_area<0) $duree_max_resa_area = -1;
      }

      $display_days = "";
      for ($i = 0; $i < 7; $i++) {
          if (isset($_POST['display_day'][$i]))
              $display_days .= "y";
          else
              $display_days .= "n";
      }
      if ($display_days != "nnnnnnn") {
        while(!isset($_POST['display_day'][$_POST['weekstarts_area']])) {
          $_POST['weekstarts_area']++;
        }
      }
      if ($_POST['morningstarts_area'] > $_POST['eveningends_area'])
          $_POST['eveningends_area'] = $_POST['morningstarts_area'];
      if ($access) {$access='r';} else {$access='a';}
      if (isset($id_area)) {
        // s'il y a changement de type de cr�neaux, on efface les r�servations du domaines
        $old_enable_periods = grr_sql_query1("select enable_periods from ".TABLE_PREFIX."_area WHERE id='".$id_area."'");
        if ($old_enable_periods != $_POST['enable_periods']) {
          $del = grr_sql_query("DELETE ".TABLE_PREFIX."_entry FROM ".TABLE_PREFIX."_entry, ".TABLE_PREFIX."_room, ".TABLE_PREFIX."_area WHERE
          ".TABLE_PREFIX."_entry.room_id = ".TABLE_PREFIX."_room.id and
          ".TABLE_PREFIX."_room.area_id = ".TABLE_PREFIX."_area.id and
          ".TABLE_PREFIX."_area.id = '".$id_area."'");
          $del = grr_sql_query("DELETE ".TABLE_PREFIX."_repeat FROM ".TABLE_PREFIX."_repeat, ".TABLE_PREFIX."_room, ".TABLE_PREFIX."_area WHERE
          ".TABLE_PREFIX."_repeat.room_id = ".TABLE_PREFIX."_room.id and
          ".TABLE_PREFIX."_room.area_id = ".TABLE_PREFIX."_area.id and
          ".TABLE_PREFIX."_area.id = '".$id_area."'");
        }
        $sql = "UPDATE ".TABLE_PREFIX."_area SET
        area_name='".protect_data_sql($area_name)."',
        access='".protect_data_sql($access)."',
        order_display='".protect_data_sql($area_order)."',
        ip_adr='".protect_data_sql($ip_adr)."',
        calendar_default_values = 'n',
        duree_max_resa_area = '".protect_data_sql($duree_max_resa_area)."',
        morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
        eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
        resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
        duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
        eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
        weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
        enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
        twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
        max_booking='".$max_booking."',
        display_days = '".$display_days."'
        WHERE id=$id_area";
        if (grr_sql_command($sql) < 0) {
            fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
            $ok = 'no';
        }
      } else {
        $sql = "INSERT INTO ".TABLE_PREFIX."_area SET
        area_name='".protect_data_sql($area_name)."',
        access='".protect_data_sql($access)."',
        order_display='".protect_data_sql($area_order)."',
        ip_adr='".protect_data_sql($ip_adr)."',
        calendar_default_values = 'n',
        duree_max_resa_area = '".protect_data_sql($duree_max_resa_area)."',
        morningstarts_area = '".protect_data_sql($_POST['morningstarts_area'])."',
        eveningends_area = '".protect_data_sql($_POST['eveningends_area'])."',
        resolution_area = '".protect_data_sql($_POST['resolution_area'])."',
        duree_par_defaut_reservation_area = '".protect_data_sql($_POST['duree_par_defaut_reservation_area'])."',
        eveningends_minutes_area = '".protect_data_sql($_POST['eveningends_minutes_area'])."',
        weekstarts_area = '".protect_data_sql($_POST['weekstarts_area'])."',
        enable_periods = '".protect_data_sql($_POST['enable_periods'])."',
        twentyfourhour_format_area = '".protect_data_sql($_POST['twentyfourhour_format_area'])."',
        display_days = '".$display_days."',
        max_booking='".$max_booking."',
        id_type_par_defaut = '-1'
        ";
        if (grr_sql_command($sql) < 0) fatal_error(1, "<p>" . grr_sql_error());
          $id_area = grr_sql_insert_id("".TABLE_PREFIX."_area", "id");
      }
      // Affectation � un site
      if (getSettingValue("module_multisite") == "Oui") {
        $sql = "delete from ".TABLE_PREFIX."_j_site_area where id_area='".$id_area."'";
        if (grr_sql_command($sql) < 0) fatal_error(0, "<p>".grr_sql_error()."</p>");
        $sql = "INSERT INTO ".TABLE_PREFIX."_j_site_area SET id_site='".$id_site."', id_area='".$id_area."'";
        if (grr_sql_command($sql) < 0) fatal_error(0, "<p>".grr_sql_error()."</p>");
      }

		  #Si area_name est vide on le change maintenant que l'on a l'id area
		  if ($area_name == '') {
			  $area_name = get_vocab("match_area")." ".$id_area;
			  grr_sql_command("UPDATE ".TABLE_PREFIX."_area SET area_name='".protect_data_sql($area_name)."' WHERE id=$id_area");
		  }
		  #on cr�e ou recr�e ".TABLE_PREFIX."_area_periodes pour le domaine
		  if (protect_data_sql($_POST['enable_periods'])=='y') {
			  if (isset($number_periodes)) {
          settype($number_periodes,"integer");
          if ($number_periodes < 1) $number_periodes = 1;
          $del_periode = grr_sql_query("delete from ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."'");
          #on efface le mod�le par d�faut avec area=0
          $del_periode = grr_sql_query("delete from ".TABLE_PREFIX."_area_periodes where id_area='0'");
          $i = 0;
          $num = 0;
          while ($i < $number_periodes) {
				    $temp = "periode_".$i;
				    if (isset($_POST[$temp])) {
						  $nom_periode = corriger_caracteres($_POST[$temp]);
						  $reg_periode = grr_sql_query("insert into ".TABLE_PREFIX."_area_periodes set
              id_area='".$id_area."',
              num_periode='".$num."',
              nom_periode='".protect_data_sql($nom_periode)."'
              ");
              #on cr�e un mod�le par d�faut avec area=0
              $reg_periode = grr_sql_query("insert into ".TABLE_PREFIX."_area_periodes set
              id_area='0',
              num_periode='".$num."',
              nom_periode='".protect_data_sql($nom_periode)."'");
              $num++;
            }
            $i++;
          }
			  }
		  }
      $msg = get_vocab("message_records");
    }
  }
  if ($access=='a') {
    $sql = "DELETE FROM ".TABLE_PREFIX."_j_user_area WHERE id_area='$id_area'";
    if (grr_sql_command($sql) < 0)
      fatal_error(0, get_vocab('update_area_failed') . grr_sql_error());
  }
  if ((isset($change_done)) and (!isset($ok))) {
    if ($msg != '') {
      $_SESSION['displ_msg'] = 'yes';
      if (strpos($retour_page, ".php?") == "") $param = "?msg=".$msg; else $param = "&msg=".$msg;
    } else
      $param = '';
    Header("Location: ".$retour_page.$param);
    exit();
  }
  # print the page header
  print_header("","","","",$type="with_session", $page="admin");
  affiche_pop_up($msg,"admin");
  $avertissement = get_vocab("avertissement_change_type");
  ?>
  <script type="text/javascript">
  function bascule()
    {
    menu_1 = document.getElementById('menu1');
    menu_2 = document.getElementById('menu2');
    if (document.getElementById('main').enable_periods[0].checked)
    {
        menu_1.style.display = "";
        menu_2.style.display = "none";
    }
    if (document.getElementById('main').enable_periods[1].checked)
    {
        menu_1.style.display = "none";
        menu_2.style.display = "";
   }
   alert("<?php echo $avertissement; ?>");
    }

	function aff_creneaux()
    {
		nb_cr = document.getElementById('nb_per');
		if (isNaN(Number(nb_cr.value))) nb_cr.value=1;
		if (nb_cr.value>50) nb_cr.value=50;
		if (nb_cr.value<1) nb_cr.value=1;
		for (var i=1; i<=nb_cr.value; i++) {
			document.getElementById('c'+i).style.display='';
		}
		for (var i; i<=50; i++) {
			document.getElementById('c'+i).style.display='none';
		}
		return false;
    }
    </script>

  <?php
  echo "<div class=\"page_sans_col_gauche\">";
  if (isset($id_area)) {
        $res = grr_sql_query("SELECT * FROM ".TABLE_PREFIX."_area WHERE id=$id_area");
        if (! $res) fatal_error(0, get_vocab('error_area') . $id_area . get_vocab('not_found'));
        $row = grr_sql_row_keyed($res, 0);
        grr_sql_free($res);
        echo "<h2>".get_vocab("editarea")."</h2>";
        if ($row["calendar_default_values"] == 'y') {
            $row["morningstarts_area"] = $morningstarts;
            $row["eveningends_area"] = $eveningends;
            $row["resolution_area"] = $resolution;
            $row["duree_par_defaut_reservation_area"] = $duree_par_defaut_reservation_area;
            $row["duree_max_resa_area"] = $duree_max_resa;
            $row["eveningends_minutes_area"] = $eveningends_minutes;
            $row["weekstarts_area"] = $weekstarts;
            $row["twentyfourhour_format_area"] = $twentyfourhour_format;
            $row["display_days"] = $display_days;
        }
        if ($row["enable_periods"] != 'y') $row["enable_periods"] = 'n';

        if (getSettingValue("module_multisite") == "Oui")
            $id_site=grr_sql_query1("select id_site from ".TABLE_PREFIX."_j_site_area where id_area='".$id_area."'");
    }
      else
    {
        $row["id"] = '';
        $row["area_name"] = '';
        $row["order_display"]  = '';
        $row["access"] = '';
        $row["ip_adr"] = '';
        $row["morningstarts_area"] = $morningstarts;
        $row["eveningends_area"] = $eveningends;
        $row["resolution_area"] = $resolution;
        $row["duree_par_defaut_reservation_area"] = $resolution;
        $row["duree_max_resa_area"] = $duree_max_resa;
        $row["eveningends_minutes_area"] = $eveningends_minutes;
        $row["weekstarts_area"] = $weekstarts;
        $row["twentyfourhour_format_area"] = $twentyfourhour_format;
        $row["enable_periods"] = 'n';
        $row["display_days"] = "yyyyyyy";
        $row['max_booking'] = '-1';
        echo "<h2>".get_vocab('addarea')."</h2>";
    }
    ?>
    <form action="admin_edit_room.php" method="post" id="main">
    <?php
    echo "<div>";
    if (isset($retour_page)) echo "<input type=\"hidden\" name=\"retour_page\" value=\"".$retour_page."\" />";
    if ($row['id'] != '') echo "<input type=\"hidden\" name=\"id_area\" value=\"".$row["id"]."\" />";
    if (isset($add_area)) echo "<input type=\"hidden\" name=\"add_area\" value=\"".$add_area."\" />\n";
    echo "</div>";

    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\"><tr>";
    // Nom du domaine
    echo "<td>".get_vocab("name").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><input type=\"text\" name=\"area_name\" size=\"40\" value=\"".htmlspecialchars($row["area_name"])."\" /></td>\n";
    echo "</tr><tr>\n";
    // Ordre d'affichage du domaine
    echo "<td>".get_vocab("order_display").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"area_order\" size=\"1\" value=\"".htmlspecialchars($row["order_display"])."\" /></td>\n";
    echo "</tr><tr>\n";
    // Acc�s restreint ou non ?
    echo "<td>".get_vocab("access").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"checkbox\" name=\"access\"";
    if ($row["access"] == 'r') echo "checked=\"checked\"";
    echo " /></td>\n";
    echo "</tr>";

    // Site
    if (getSettingValue("module_multisite") == "Oui") {
      // Affiche une liste d�roulante des sites;
      if(authGetUserLevel(getUserName(),-1,'area') >= 6)
        $sql = "SELECT id,sitecode,sitename
        FROM ".TABLE_PREFIX."_site
        ORDER BY sitename ASC";
      else
        $sql = "SELECT id,sitecode,sitename
        FROM ".TABLE_PREFIX."_site s,  ".TABLE_PREFIX."_j_useradmin_site u
        WHERE s.id=u.id_site and u.login='".getUserName()."'
        ORDER BY s.sitename ASC";
      $res = grr_sql_query($sql);
      $nb_site = grr_sql_count($res);
      echo "<tr><td>".get_vocab('site').get_vocab('deux_points')."</td>\n";
      if ($nb_site > 1) {
        echo "<td><select name=\"id_site\" >\n
           <option value=\"-1\">".get_vocab('choose_a_site')."</option>\n";
        for ($enr = 0; ($row1 = grr_sql_row($res, $enr)); $enr++) {
          echo "<option value=\"".$row1[0]."\"";
          if ($id_site == $row1[0])
            echo ' selected="selected"';
          echo '>'.htmlspecialchars($row1[2]);
          echo '</option>'."\n";
        }
        grr_sql_free($res);
        echo "</select></td></tr>";
      } else {
        // un seul site
        $row1 = grr_sql_row($res, 0);
        echo "<td>".$row1[2]."<input type=\"hidden\" name=\"id_site\" value=\"".$id_site."\" /></td></tr>\n";
      }
    }

    // Adresse IP client :
    if (OPTION_IP_ADR==1) {
        echo "<tr>\n";
        echo "<td>".get_vocab("ip_adr").get_vocab("deux_points")."</td>";
        echo "<td><input type=\"text\" name=\"ip_adr\" value=\"".htmlspecialchars($row["ip_adr"])."\" /></td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td colspan=\"2\">".get_vocab("ip_adr_explain")."</td>\n";

        echo "</tr>\n";
    }
    echo "</table>";
    // Configuration des plages horaires ...
    echo "<h3>".get_vocab("configuration_plages_horaires").grr_help("Configuration_affichage","conf_planning")."</h3>";


    // D�but de la semaine: 0 pour dimanche, 1 pou lundi, etc.
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    echo "<tr>\n";
    echo "<td>".get_vocab("weekstarts_area").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><select name=\"weekstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 7) {
        $tmp=mktime(0,0,0,10,2+$k,2005);
        echo "<option value=\"".$k."\" ";
        if ($k == $row['weekstarts_area']) echo " selected=\"selected\"";
        echo ">".utf8_strftime("%A", $tmp)."</option>\n";
        $k++;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // D�finition des jours de la semaine � afficher sur les plannings et calendriers
    echo "<tr>\n";
    echo "<td>".get_vocab("cocher_jours_a_afficher")."</td>\n";
    echo "<td>\n";
    for ($i = 0; $i < 7; $i++)
    {
      echo "<label><input name=\"display_day[".$i."]\" type=\"checkbox\"";
      if (substr($row["display_days"],$i,1) == 'y') echo " checked=\"checked\"";
      echo " />" . day_name($i) . "</label><br />\n";
    }
        echo "</td>\n";
	echo "</tr></table>";

	echo "<h3>".get_vocab("type_de_creneaux").grr_help("Configuration_affichage","conf_planning")."</h3>";
	echo "<table>";

    //echo "<p style=\"text-align:left;\"><b>ATTENTION :</b> Les deux types de configuration des cr�neaux sont incompatibles entre eux : un changement du type de cr�neaux entra�ne donc, apr�s validation, un <b>effacement de toutes les r�servations  de ce domaine</b></p>.";
    echo "<tr><td colspan=\"2\"><label><input type=\"radio\" name=\"enable_periods\" value=\"n\" onclick=\"bascule()\" ";
    if ($row["enable_periods"] == 'n') echo "checked=\"checked\"";
    echo " />".get_vocab("creneaux_de_reservation_temps")."</label><br />";
    echo "<label><input type=\"radio\" name=\"enable_periods\" value=\"y\" onclick=\"bascule()\" ";
    if ($row["enable_periods"] == 'y') echo "checked=\"checked\"";
    echo " />".get_vocab("creneaux_de_reservation_pre_definis")."</label></td></tr></table>";

    //Les cr�neaux de r�servation sont bas�s sur des intitul�s pr�-d�finis.
    $sql_periode = grr_sql_query("SELECT num_periode, nom_periode FROM ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."' order by num_periode");
    $num_periodes = grr_sql_count($sql_periode);
    if (!isset($number_periodes))
        if ($num_periodes == 0)
            $number_periodes = 10;
        else
            $number_periodes = $num_periodes;

    if ($row["enable_periods"] == 'y')
        echo "<table id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    else
        echo "<table style=\"display:none\" id=\"menu2\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    echo "<tr><td>".get_vocab("nombre_de_creneaux").get_vocab("deux_points")."</td>";
    echo "<td style=\"width:30%;\"><input type=\"text\" id=\"nb_per\" name=\"number_periodes\" size=\"1\" onkeypress=\"if (event.keyCode==13) return aff_creneaux()\" value=\"$number_periodes\" />
	<a href=\"#Per\" onclick=\"javascript:return(aff_creneaux())\">".get_vocab("goto")."</a>\n";

    echo "</td></tr>\n<tr><td colspan=\"2\">";
    $i = 0;
    while ($i < 50) {
        $nom_periode = grr_sql_query1("select nom_periode FROM ".TABLE_PREFIX."_area_periodes where id_area='".$id_area."' and num_periode= '".$i."'");
        if ($nom_periode == -1) $nom_periode = "";
        echo "<table style=\"display:none\" id=\"c".($i+1)."\"><tr><td>".get_vocab("intitule_creneau").($i+1).get_vocab("deux_points")."</td>";
        echo "<td style=\"width:30%;\"><input type=\"text\" name=\"periode_".$i."\" value=\"".htmlentities($nom_periode)."\" size=\"20\" /></td></tr></table>\n";
        $i++;
    }
        // L'utilisateur ne peut r�server qu'une dur�e limit�e (-1 d�sactiv�e), exprim�e en jours
    if ($row["duree_max_resa_area"] > 0)
        $nb_jour = max(round($row["duree_max_resa_area"]/1440,0),1);
    else
        $nb_jour = -1;
    echo "</td></tr>\n<tr><td>".get_vocab("duree_max_resa_area2").grr_help("Configuration_affichage","duree_max_reser").get_vocab("deux_points");
    echo "\n</td><td><input type=\"text\" name=\"duree_max_resa_area2\" size=\"5\" value=\"".$nb_jour."\" /></td></tr>\n";
    echo "</table>";

    // Cas ou les cr�neaux de r�servations sont bas�s sur le temps
    if ($row["enable_periods"] == 'n')
        echo "<table id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    else
        echo "<table style=\"display:none\" id=\"menu1\" border=\"1\" cellspacing=\"1\" cellpadding=\"6\">";
    // Heure de d�but de r�servation
    echo "<tr>";
    echo "<td>".get_vocab("morningstarts_area").get_vocab("deux_points")."</td>\n";
    echo "<td style=\"width:30%;\"><select name=\"morningstarts_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['morningstarts_area']) echo " selected=\"selected\"";
        echo ">".$k."</option>\n";
        $k++;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // Heure de fin de r�servation
    echo "<tr>\n";
    echo "<td>".get_vocab("eveningends_area").get_vocab("deux_points")."</td>\n";
    echo "<td><select name=\"eveningends_area\" size=\"1\">\n";
    $k = 0;
    while ($k < 24) {
        echo "<option value=\"".$k."\" ";
        if ($k == $row['eveningends_area']) echo " selected=\"selected\"";
        echo ">".$k."</option>\n";
        $k++;
    }
    echo "</select></td>\n";
    echo "</tr>";

    // Minutes � ajouter � l'heure $eveningends pour avoir la fin r�elle d'une journ�e.
    echo "<tr>\n";
    echo "<td>".get_vocab("eveningends_minutes_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"eveningends_minutes_area\" size=\"5\" value=\"".htmlspecialchars($row["eveningends_minutes_area"])."\" /></td>\n";
    echo "</tr>";

    // Resolution - quel bloc peut �tre r�serv�, en secondes
    echo "<tr>\n";
    echo "<td>".get_vocab("resolution_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"resolution_area\" size=\"5\" value=\"".htmlspecialchars($row["resolution_area"])."\" /></td>\n";
    echo "</tr>";

    // Valeur par d�faut de la dur�e d'une r�servation
    echo "<tr>\n";
    echo "<td>".get_vocab("duree_par_defaut_reservation_area").get_vocab("deux_points")."</td>\n";
    echo "<td><input type=\"text\" name=\"duree_par_defaut_reservation_area\" size=\"5\" value=\"".htmlspecialchars($row["duree_par_defaut_reservation_area"])."\" /></td>\n";
    echo "</tr>";


    // Format d'affichage du temps : valeur 0 pour un affichage ��12 heures�� et valeur 1 pour un affichage  ��24 heure��.
    echo "<tr>\n";
    echo "<td>".get_vocab("twentyfourhour_format_area").get_vocab("deux_points")."</td>\n";
    echo "<td>\n";
    echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"0\" ";
    if ($row['twentyfourhour_format_area'] == 0) echo " checked=\"checked\"";
    echo " />".get_vocab("twentyfourhour_format_12")."</label>\n<br />";
    echo "<label><input type=\"radio\" name=\"twentyfourhour_format_area\" value=\"1\" ";
    if ($row['twentyfourhour_format_area'] == 1) echo " checked=\"checked\"";
    echo " />".get_vocab("twentyfourhour_format_24")."</label>\n";
    echo "</td>\n";
    echo "</tr>\n";

    // L'utilisateur ne peut r�server qu'une dur�e limit�e (-1 d�sactiv�e), exprim�e en minutes
    echo "<tr>\n<td>".get_vocab("duree_max_resa_area").grr_help("Configuration_affichage","duree_max_reser").get_vocab("deux_points");
    echo "</td>\n<td><input type=\"text\" name=\"duree_max_resa_area1\" size=\"5\" value=\"".$row["duree_max_resa_area"]."\" /></td></tr>\n";
    echo "</table>";
    echo "<table>";
        // Nombre max de r�servation par domaine
    echo "<tr><td>".get_vocab("max_booking")." -  ".get_vocab("all_rooms_of_area").get_vocab("deux_points");
    echo "</td><td><input type=\"text\" name=\"max_booking\" value=\"".$row['max_booking']."\" /></td>\n";
    echo "</tr></table>";

    echo "<div style=\"text-align:center;\">\n";
    echo "<input type=\"submit\" name=\"change_area\" value=\"".get_vocab("save")."\" />\n";
    echo "<input type=\"submit\" name=\"change_done\" value=\"".get_vocab("back")."\" />";
    echo "<input type=\"submit\" name=\"change_area_and_back\" value=\"".get_vocab("save_and_back")."\" />";
    echo "</div></form>";

    echo "<script type=\"text/javascript\">";
    echo "aff_creneaux();";
    echo "</script>";
    echo "</div>";

 } ?>
</body>
</html>