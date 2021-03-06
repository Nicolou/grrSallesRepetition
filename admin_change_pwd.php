<?php
/**
 * admin_change_pwd.php
 * Interface de changement du mot de passe pour les administrateurs
 * Ce script fait partie de l'application GRR
 * Derni�re modification : $Date: 2009-06-04 15:30:17 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: admin_change_pwd.php,v 1.8 2009-06-04 15:30:17 grr Exp $
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
 * $Log: admin_change_pwd.php,v $
 * Revision 1.8  2009-06-04 15:30:17  grr
 * *** empty log message ***
 *
 * Revision 1.7  2009-04-14 12:59:17  grr
 * *** empty log message ***
 *
 * Revision 1.6  2009-02-27 13:28:19  grr
 * *** empty log message ***
 *
 * Revision 1.5  2008-11-16 22:00:58  grr
 * *** empty log message ***
 *
 * Revision 1.4  2008-11-11 22:01:14  grr
 * *** empty log message ***
 *
 *
 */

include "include/admin.inc.php";
$grr_script_name = "admin_change_pwd.php";

$back = '';
if (isset($_SERVER['HTTP_REFERER'])) $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
if(authGetUserLevel(getUserName(),-1) < 6)
{
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    showAccessDenied($day, $month, $year, '',$back);
    exit();
}
// Restriction dans le cas d'une d�mo
VerifyModeDemo();

unset($user_login);
$user_login = isset($_POST["user_login"]) ? $_POST["user_login"] : ($user_login = isset($_GET["user_login"]) ? $_GET["user_login"] : NULL);
$valid = isset($_POST["valid"]) ? $_POST["valid"] : NULL;

$msg='';

if ($valid == "yes") {
    unset($reg_password1);
    $reg_password1 = $_POST["reg_password1"];
    unset($reg_password2);
    $reg_password2 = $_POST["reg_password2"];

    $reg_password_c = md5($reg_password1);
    if (($reg_password1 != $reg_password2) or (strlen($reg_password1) < $pass_leng)) {
        $msg = get_vocab("passwd_error");
    } else {
        $sql = "UPDATE ".TABLE_PREFIX."_utilisateurs SET password='" . protect_data_sql($reg_password_c)."' WHERE login='$user_login'";
        if (grr_sql_command($sql) < 0)
            {fatal_error(0, get_vocab('update_pwd_failed') . grr_sql_error());
        } else {
            $msg = get_vocab('update_pwd_succeed');
        }
    }
}

// On appelle les informations de l'utilisateur
if (isset($user_login) and ($user_login!='')) {
    $sql = "SELECT nom,prenom, source FROM ".TABLE_PREFIX."_utilisateurs WHERE login='$user_login'";
    $res = grr_sql_query($sql);
    if ($res) {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $user_nom = $row[0];
        $user_prenom = $row[1];
        $user_source = $row[2];
        }
    }
}

if (($user_source != 'local') and ($user_source != ''))
{
    showAccessDenied($day, $month, $year, '',$back);
    exit();
}


# print the page header
print_header("","","","",$type="with_session", $page="admin");
affiche_pop_up($msg,"admin");

?>

<p>| <a href="admin_user_modify.php?user_login=<?php echo $user_login; ?>"><?php echo get_vocab("back");?></a> |</p>

<?php
echo "<h3>".get_vocab("pwd_change")."</h3>\n";
if ($user_login != getUserName()) {
    echo "<form action=\"admin_change_pwd.php\" method='post'>\n<div>";
    echo get_vocab("login")." : $user_login";
    echo "\n<br />".get_vocab("last_name").get_vocab("deux_points").$user_nom."&nbsp;&nbsp;&nbsp;".get_vocab("first_name").get_vocab("deux_points").$user_prenom;
    echo "\n<br />".get_vocab("pwd_msg_warning");
    echo "\n<br /><br />".get_vocab("new_pwd1").get_vocab("deux_points")."<input type=\"password\" name=\"reg_password1\" value=\"\" size=\"20\" />";
    echo "\n<br />".get_vocab("new_pwd2").get_vocab("deux_points")."<input type=\"password\" name=\"reg_password2\" value=\"\" size=\"20\" />";
    echo "\n<input type=\"hidden\" name=\"valid\" value=\"yes\" />";
    echo "\n<input type=\"hidden\" name=\"user_login\" value=\"$user_login\" />";
    echo "\n<br /><input type=\"submit\" value=\"".get_vocab("save")."\" /></div></form>";
} else {
    echo "<\ndiv><br />".get_vocab("pwd_msg_warning2")."</div>";
}

?>
</body>
</html>