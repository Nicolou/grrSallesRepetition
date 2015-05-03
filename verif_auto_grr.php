<?php
/**
 * verif_auto_grr.php
 * Ex�cution de taches automatiques
 * Ce script fait partie de l'application GRR
 * Derni�re modification : $Date: 2009-09-29 18:02:57 $
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @link      http://www.gnu.org/licenses/licenses.html
 * @package   root
 * @version   $Id: verif_auto_grr.php,v 1.4 2009-09-29 18:02:57 grr Exp $
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
 * $Log: verif_auto_grr.php,v $
 * Revision 1.4  2009-09-29 18:02:57  grr
 * *** empty log message ***
 *
 * Revision 1.3  2008-11-16 22:00:59  grr
 * *** empty log message ***
 *
 *
 */


// L'ex�cution de ce script requiert un mot de passe :
// Exemple : si le mot de passe est jamesbond007, vous devrez indiquer une URL du type :
// http://mon-site.fr/grr/verif_auto_grr.php?mdp=jamesbond007
// Le mot de passe  est d�fini dans l'interface en ligne de GRR (configuration g�n�rale -> Interactivit�)

// D�but du script

$titre = "GRR - Ex&eacute;cution de t&acirc;ches automatiques";
include "include/connect.inc.php";
include "include/config.inc.php";
include "include/misc.inc.php";
include "include/functions.inc.php";
include "include/$dbsys.inc.php";
//include "include/language.inc.php"; // Inutile d'inclure ce fichier.
$grr_script_name = "verif_auto_grr.php";
include("include/settings.inc.php");
if (!loadSettings())
    die("Erreur chargement settings");


if (!isset($_GET['mdp']))
    $_GET['mdp']=$argv[1];

if ((!isset($_GET['mdp'])) or ($_GET['mdp'] != getSettingValue("motdepasse_verif_auto_grr")) or (getSettingValue("motdepasse_verif_auto_grr")=='')) {
    echo begin_page($titre,$page="no_session");
    echo "<p>Le mot de passe fourni est invalide.</p>";
    echo "<p>".$_GET['mdp']."</p>";
    echo "<p>".getSettingValue("motdepasse_verif_auto_grr")."</p>";
    include "include/trailer.inc.php";
    die();
}

echo begin_page($titre,$page="no_session");
// On v�rifie une fois par jour si le d�lai de confirmation des r�servations est d�pass�
// Si oui, les r�servations concern�es sont supprim�es et un mail automatique est envoy�.
verify_confirm_reservation();

// On v�rifie une fois par jour que les ressources ont �t� rendue en fin de r�servation
// Si non, une notification email est envoy�e
verify_retard_reservation();
echo "<p>Le script a �t� ex�cut�.</p>";
include "include/trailer.inc.php";
?>