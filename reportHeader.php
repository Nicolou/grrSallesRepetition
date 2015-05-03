<?php
/**
 * reportHeader.php

 *
 *
 */

include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
include "include/mrbs_sql.inc.php";

$grr_script_name = "report_master.php";
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


global $vocab, $search_str, $grrSettings, $clock_file, $desactive_VerifNomPrenomUser, $grr_script_name;
global $use_prototype, $use_tooltip_js, $desactive_bandeau_sup, $id_site;

$day   = date("d");
$month = date("m");
$year  = date("Y");

if (!(isset($search_str))) $search_str = get_vocab("search_for");
if (empty($search_str)) $search_str = "";


echo begin_page('report_master');

 echo '

   <table width="100%" border="0">
    <tr>
      <td class="border_banner">
 ';
 
  $nom_picture = "./images/".getSettingValue("logo");
  if ((getSettingValue("logo")!='') and (@file_exists($nom_picture)))
   echo "<td class=\"banner\"><img src=\"".$nom_picture."\" class=\"image\" alt=\"logo\" /></td>\n";
   echo "<td class=\"banner\">\n";
    echo "&nbsp;<a TARGET=\"_top\" href=\"".page_accueil('yes')."day=$day&amp;year=$year&amp;month=$month\">".get_vocab("welcome")."</a>";
    echo " - <b>".getSettingValue("company")."</b>";
   
      $parametres_url = '';
        $_SESSION['chemin_retour'] = '';
        if (isset($_SERVER['QUERY_STRING']) and ($_SERVER['QUERY_STRING'] != '')) {
            // Il y a des paramètres à passer
            $parametres_url = htmlspecialchars($_SERVER['QUERY_STRING'])."&amp;";
            $_SESSION['chemin_retour'] = traite_grr_url($grr_script_name)."?". $_SERVER['QUERY_STRING'];
        }
           
      $disconnect_link = false;
      if (!((getSettingValue("cacher_lien_deconnecter")=='y') and (isset($_SESSION['est_authentifie_sso'])))) {
         // on n'affiche pas le lien logout dans le cas d'un utilisateur LCS connecté.
         $disconnect_link = true;
         if (getSettingValue("authentification_obli") == 1) {
             echo "<br />&nbsp;<a TARGET=\"_top\" href=\"./logout.php?auto=0\" >".get_vocab('disconnect')."</a>";
         } else {
             echo "<br />&nbsp;<a TARGET=\"_top\" href=\"./logout.php?auto=0&amp;redirect_page_accueil=yes\" >".get_vocab('disconnect')."</a>";
         }
      }
      if ((getSettingValue("Url_portail_sso")!='') and (isset($_SESSION['est_authentifie_sso']))) {
          if ($disconnect_link)
             echo "&nbsp;-&nbsp;";
          else
             echo "<br />&nbsp;";
          echo('<a TARGET=\"_top\" href="'.getSettingValue("Url_portail_sso").'">'.get_vocab("Portail_accueil").'</a>');
       }
       // Cas d'une authentification LASSO
       if ((getSettingValue('sso_statut') == 'lasso_visiteur') or (getSettingValue('sso_statut') == 'lasso_utilisateur')) {
         echo "<br />&nbsp;";
         if ($_SESSION['lasso_nameid'] == NULL)
           echo "<a TARGET=\"_top\" href=\"lasso/federate.php\">".get_vocab('lasso_federate_this_account')."</a>";
         else
           echo "<a TARGET=\"_top\" href=\"lasso/defederate.php\">".get_vocab('lasso_defederate_this_account')."</a>";
         }
    
 echo '
     </td>
		<td>
			t&eacute;l&eacute;charger les stats
			<form action="genereStat.php" method="post"><button type="submit" name="excel" value="go"><IMG alt="excel" src="./images/excel.jpg"></img></button></form>
		</td>  
  	</tr>
 </table>
    ';

if (isset($_POST['excel'])) {
	if ($_POST['excel'] == 'go') {
		echo '<p>poster</p>';
	}
}

echo '</body>';
?>