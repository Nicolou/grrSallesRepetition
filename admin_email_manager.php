<?php
/**
 * admin_email_manager.php
 * Interface de gestion des mails automatiques
 * Derni�re modification : $Date: 2009-04-14 12:59:17 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   admin
 * @version   $Id: admin_email_manager.php,v 1.11 2009-04-14 12:59:17 grr Exp $
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
 * $Log: admin_email_manager.php,v $
 * Revision 1.11  2009-04-14 12:59:17  grr
 * *** empty log message ***
 *
 * Revision 1.10  2009-04-09 14:52:31  grr
 * *** empty log message ***
 *
 * Revision 1.9  2009-02-27 13:28:19  grr
 * *** empty log message ***
 *
 *
 */

include "include/admin.inc.php";
$grr_script_name = "admin_email_manager.php";

$id_area = isset($_GET["id_area"]) ? $_GET["id_area"] : NULL;
$room = isset($_GET["room"]) ? $_GET["room"] : NULL;
if (isset($room)) settype($room,"integer");
if (!isset($id_area)) settype($id_area,"integer");

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
if(authGetUserLevel(getUserName(),-1,'area') < 4)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, '',$back);
    exit();
}

// tableau des ressources auxquelles l'utilisateur n'a pas acc�s
$tab_rooms_noaccess = verif_acces_ressource(getUserName(), 'all');

if (isset($_POST['mail1'])) {
  if (isset($_POST['send_always_mail_to_creator']))
      $temp = '1';
  else
      $temp = '0';
  if (!saveSetting("send_always_mail_to_creator", $temp)) {
        echo "Erreur lors de l'enregistrement de send_always_mail_to_creator !<br />";
        die();
  }
}


$reg_admin_login = isset($_GET["reg_admin_login"]) ? $_GET["reg_admin_login"] : NULL;
$action = isset($_GET["action"]) ? $_GET["action"] : NULL;
$msg='';

if ($reg_admin_login) {
    // On commence par v�rifier que le professeur n'est pas d�j� pr�sent dans cette liste.
    if ($room !=-1) {
        // Ressource
        // On v�rifie que la ressource $room existe
        $test = grr_sql_query1("select id from ".TABLE_PREFIX."_room where id='".$room."'");
        if ($test == -1) {
            showAccessDenied('', '', '', '',$back);
            exit();
        }
        if (in_array($room,$tab_rooms_noaccess)) {
            showAccessDenied('','','', '',$back);
            exit();
        }

        // La ressource existe : on v�rifie les privil�ges de l'utilisateur
        if(authGetUserLevel(getUserName(),$room) < 4)
        {
            showAccessDenied('','','', '',$back);
            exit();
        }

        $sql = "SELECT * FROM ".TABLE_PREFIX."_j_mailuser_room WHERE (login = '$reg_admin_login' and id_room = '$room')";
        $res = grr_sql_query($sql);
        $test = grr_sql_count($res);
        if ($test != "0") {
            $msg = get_vocab("warning_exist");
        } else {
            if ($reg_admin_login != '') {
                $sql = "INSERT INTO ".TABLE_PREFIX."_j_mailuser_room SET login= '$reg_admin_login', id_room = '$room'";
                if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());} else {$msg=get_vocab("add_user_succeed");}
            }
        }
    }
}

if ($action) {
    if ($action == "del_admin") {
        if(authGetUserLevel(getUserName(),$room) < 4)
        {
            showAccessDenied($day, $month, $year, '',$back);
            exit();
        }

        $sql = "DELETE FROM ".TABLE_PREFIX."_j_mailuser_room WHERE (login='".$_GET['login_admin']."' and id_room = '$room')";
        if (grr_sql_command($sql) < 0) {fatal_error(1, "<p>" . grr_sql_error());}  else {$msg=get_vocab("del_user_succeed");}
    }
}
# print the page header
print_header("","","","",$type="with_session", $page="admin");
// Affichage de la colonne de gauche
include "admin_col_gauche.php";

affiche_pop_up($msg,"admin");


if (empty($room)) $room = -1;

echo "<h2>".get_vocab('admin_email_manager.php').grr_help("aide_grr_mail_auto")."</h2>\n";
if (getSettingValue("automatic_mail") != 'yes')
    echo "<h3 class=\"avertissement\">".get_vocab("attention_mail_automatique_d�sactive")."</h3>";

echo get_vocab("explain_automatic_mail3")."<br /><br /><hr />\n";
echo "<form action=\"admin_email_manager.php\" method=\"post\">\n";
echo "<div><input type=\"checkbox\" name=\"send_always_mail_to_creator\" value=\"y\" ";
if (getSettingValue('send_always_mail_to_creator')=='1')
    echo ' checked="checked" ';
echo ' />'."\n";
echo get_vocab("explain_automatic_mail1");
echo "\n".'<br /><br /><div style="text-align:center;"><input type="submit" name="mail1" value="'.get_vocab('save').'" /></div></div></form><hr />';
echo get_vocab("explain_automatic_mail2")."<br />";
echo $msg;
# Table with areas, rooms.
echo "\n<table><tr>\n";
$this_area_name = "";
$this_room_name = "";
# Show all areas
echo "<td ><p><b>".get_vocab("areas")."</b></p>\n";
$out_html = "<form  id=\"area\" action=\"admin_email_manager.php\" method=\"post\">\n<div><select name=\"area\" onchange=\"area_go()\">\n";
$out_html .= "<option value=\"admin_email_manager.php?id_area=-1\">".get_vocab('select')."</option>\n";
    $sql = "select id, area_name from ".TABLE_PREFIX."_area order by area_name";
       $res = grr_sql_query($sql);
       if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
       {
        $selected = ($row[0] == $id_area) ? "selected=\"selected\"" : "";
        $link = "admin_email_manager.php?id_area=$row[0]";
        // On affiche uniquement les domaines administr�s par l'utilisateur
        if(authGetUserLevel(getUserName(),$row[0],'area') >= 4)
            $out_html .= "<option $selected value=\"$link\">" . htmlspecialchars($row[1])."</option>\n";
       }
    $out_html .= "</select></div>
    <script type=\"text/javascript\" >
    <!--
    function area_go()
    {
    box = document.getElementById(\"area\").area;
    destination = box.options[box.selectedIndex].value;
    if (destination) location.href = destination;
    }
    // -->
    </script>
    <noscript>
    <div><input type=\"submit\" value=\"Change\" /></div>
    </noscript>
    </form>";

echo $out_html;


$this_area_name = grr_sql_query1("select area_name from ".TABLE_PREFIX."_area where id=$id_area");
$this_room_name = grr_sql_query1("select room_name from ".TABLE_PREFIX."_room where id=$room");
$this_room_name_des = grr_sql_query1("select description from ".TABLE_PREFIX."_room where id=$room");
echo "</td>\n";

# Show all rooms in the current area
echo "<td><p><b>".get_vocab("rooms")."</b></p>";

# should we show a drop-down for the room list, or not?
$out_html = "<form id=\"room\" action=\"admin_email_manager.php\" method=\"post\">\n<div><select name=\"room\" onchange=\"room_go()\">\n";
$out_html .= "<option value=\"admin_email_manager.php?id_area=$id_area&amp;room=-1\">".get_vocab('select')."</option>\n";

    $sql = "select id, room_name, description from ".TABLE_PREFIX."_room where area_id=$id_area ";
    // on ne cherche pas parmi les ressources invisibles pour l'utilisateur
    foreach($tab_rooms_noaccess as $key){
      $sql .= " and id != $key ";
    };
    $sql .= "order by room_name";
       $res = grr_sql_query($sql);
       if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
       {
        if ($row[2]) {$temp = " (".htmlspecialchars($row[2]).")";} else {$temp="";}
        $selected = ($row[0] == $room) ? "selected=\"selected\"" : "";
        $link = "admin_email_manager.php?id_area=$id_area&amp;room=$row[0]";
        $out_html .= "<option $selected value=\"$link\">" . htmlspecialchars($row[1].$temp)."</option>\n";
       }
    $out_html .= "</select></div>\n
       <script type=\"text/javascript\" >
       <!--
       function room_go()
        {
        box = document.getElementById(\"room\").room;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
        }
        // -->
        </script>

        <noscript>
        <div><input type=\"submit\" value=\"Change\" /></div>
        </noscript>
        </form>";

echo $out_html;
echo "</td>\n";
echo "</tr></table>\n";

# Don't continue if this area has no rooms:
if ($id_area <= 0)
{
    echo "<h1>".get_vocab("no_area")."</h1>";
    // fin de l'affichage de la colonne de droite
    echo "</td></tr></table></body></html>";
    exit;
}
# Show area and room:
if ($this_room_name_des!='-1') {$this_room_name_des = " (".$this_room_name_des.")";} else {$this_room_name_des='';}
if ($room=='-1') {
    echo "<h1>".get_vocab("no_room")."</h1>";
    // fin de l'affichage de la colonne de droite
    echo "</td></tr></table></body></html>";
    exit;
} else {
    echo "<table border=\"1\" cellpadding=\"5\"><tr><td>";
    $sql = "SELECT u.login, u.nom, u.prenom FROM ".TABLE_PREFIX."_utilisateurs u, ".TABLE_PREFIX."_j_mailuser_room j WHERE (j.id_room='$room' and u.login=j.login)  order by u.nom, u.prenom";
    $res = grr_sql_query($sql);
    $nombre = grr_sql_count($res);
    if ($nombre!=0) echo "<h3>".get_vocab("mail_user_list")."</h3>";
    if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $login_admin = $row[0];
        $nom_admin = $row[1];
        $prenom_admin = $row[2];
        echo "<b>";
        echo "$nom_admin $prenom_admin</b> | <a href='admin_email_manager.php?action=del_admin&amp;login_admin=".urlencode($login_admin)."&amp;room=$room&amp;id_area=$id_area'>".get_vocab("delete")."</a><br />";
    }
    if ($nombre == 0) {
        echo "<h3><span class=\"avertissement\">".get_vocab("no_mail_user_list")."</span></h3>";
    }
}
?>
<h3><?php echo get_vocab("add_user_to_list"); ?></h3>
<form action="admin_email_manager.php" method='get'>
<div><select size="1" name="reg_admin_login">
<option value=''><?php echo get_vocab("nobody"); ?></option>
<?php
$sql = "SELECT DISTINCT login, nom, prenom FROM ".TABLE_PREFIX."_utilisateurs WHERE  (etat!='inactif' and email!='' and statut!='visiteur' ) order by nom, prenom";
$res = grr_sql_query($sql);
if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
    if (authUserAccesArea($row[0],$id_area) == 1) {
        echo "<option value='$row[0]'>$row[1]  $row[2] </option>";
    }
}
?>
</select>
<input type="hidden" name="add_admin" value="yes" />
<input type="hidden" name="id_area" value="<?php echo $id_area;?>" />
<input type="hidden" name="room" value="<?php echo $room;?>" />
<input type="submit" value="Enregistrer" /></div>
</form>
</td></tr></table>
<?php
// fin de l'affichage de la colonne de droite
echo "</td></tr></table>";
?>
</body>
</html>