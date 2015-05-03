<?php
/**
 * forfait.inc.php
 *
 *
 *
 *
*/


function getRowsListRepetitionsFaites($who,$formatStringDate){
#récupèreun tableau avec la liste des reservations effectuées
//creation de la requete
if (!isset($formatStringDate)) $formatStringDate = '%d.%m.%Y';
	$req = " SELECT FROM_UNIXTIME( `start_time` , '" . $formatStringDate . "') AS date,
					(`end_time` - `start_time`) /3600 AS duree
					FROM `grr_entry`
					WHERE `entry_type` =0
					AND `room_id` =1
					AND `option_reservation` = -1
					AND `moderate`
					IN ( 0, 2 )
					AND `beneficiaire` = '" . $who . "'
					AND `start_time` < UNIX_TIMESTAMP()
					ORDER BY `start_time` DESC";

	return grr_sql_query($req);
	}


function getRowsListForfaitPris($who){
#recupere la liste des forfait pris	
	$req = "select BLDT, QT, ID
			from `grr_forfait_credit`
			where USER_ID = '". $who ."'
			order by BLDT DESC ";
	return grr_sql_query($req);
}

function getRowsListForfaitPointage($who){
#recupere la liste des forfait pointes	
	$req = "select BLDT, QT_CREDIT, WHY, ID
			from `grr_forfait_archive`
			where USER_ID = '". $who ."'
			order by BLDT DESC ";
	return grr_sql_query($req);
}

//retourne le nombre d'heures faite depuis la date passée en paramètre jusqu'a aujourd'hui
function getNbHeuresFaitesDepuis($d,$who) {
#recupere le nombre d'heure effectué depuis la date d 
	$req = "SELECT sum((`end_time` - `start_time`) /3600) AS duree
					FROM `grr_entry`
					WHERE `entry_type` =0
					AND `room_id` =1
					AND `option_reservation` = -1
					AND `moderate`
					IN ( 0, 2 )
					AND `beneficiaire` = '" . $who . "'
					AND FROM_UNIXTIME(`start_time`) > '" . $d . "'
					AND `start_time` < " . time() . " 
					ORDER BY `start_time` DESC";

	
	return grr_sql_query1($req);				
}



function getCreditNumberHourForfait($who) {
#recupère le nombre d'heures restantes sur le forfait pour l'utilisateur passé ne parametre
	$req = "SELECT BLDT, QT_CREDIT FROM `grr_forfait_archive`
	where USER_ID = '" . $who . "'
	order by BLDT DESC
	LIMIT 1";
	
	//date depuis laquel il faudrait compter les répétitions effectuées
	$dateDepuis = '01/01/1753';
	// nb d'heures rapporter par le dernier inventaire
	$nbForfaitPointage = 0;		
	
	$rsql = grr_sql_query($req);
	if ($rsql) {
		$row = grr_sql_row($rsql ,0);
		if ($row) {		
			$dateDepuis = $row[0];
			$nbForfaitPointage = $row[1];}
		else {
			$dateDepuis = '01/01/1753';
			$nbForfaitPointage = 0;
		}	
	}
	
	$req = "SELECT SUM(QT) FROM `grr_forfait_credit`
				where USER_ID = '" . $who . "'
					and BLDT > '" . $dateDepuis . "'";
	//nombre dheures des forfait acheter depuis el dernier inventaire				
	$nbrForfaitEnplus = grr_sql_query1($req);
	// ??
	if ($nbrForfaitEnplus < 0) $nbrForfaitEnplus = 0;
	
	$nbrHeuresfaiteDepuis = getNbHeuresFaitesDepuis($dateDepuis, $who);
	//??
	if ($nbrHeuresfaiteDepuis < 0) $nbrHeuresfaiteDepuis = 0;
	
	$forfaitRestant = $nbForfaitPointage + $nbrForfaitEnplus - $nbrHeuresfaiteDepuis;
						
	return $forfaitRestant;
		

}

function getRowsListUsers() {
	$req = "select `login` FROM `grr_utilisateurs` where `statut` = 'utilisateur' order by `login`";
	return grr_sql_query($req);	
}

//  noms users, forfait restant, dernier repet
function getMainTable() {
	$t = array();
	$t[0][0]  ='rien';
	$t[0][1]  ='0';
	$t[0][2]  ='0';	
	$rsql = getRowsListUsers();

	if ($rsql) {
		$i = 0; while (($row = grr_sql_row($rsql, $i))) {			
				$t[$i][0] = $row[0];
				$t[$i][1] = getCreditNumberHourForfait($t[$i][0]);
				$rlstrepet = getRowsListRepetitionsFaites($t[$i][0],'%d.%m.%Y');
				if ($rlstrepet) {
					$row2 = grr_sql_row($rlstrepet,0);
					if ($row2) {
						$t[$i][2] = $row2[0];
					}
					else
					{
						$t[$i][2] = 'jamais';
					}
				}
				
				$i++;
		}
		
	}
	return $t;
}

// verifi que la limite de forfait en cours n'est pas inférieur à la valeur limite pour l'utilisateur passe en parametre.
// si c'est le cas, alors affcihe un message disant que la réservation ne peut etre effectuer car la limite de depassement dfe forfait a été atteinte
// et retourne vrai
// sinon retourne faux
function verif_depassement_forfait($who) {


	include "include/bocal.inc.php";
	
	$fr = getCreditNumberHourForfait($who);
	$mes = 'vous etes a ' . $fr . ' heures de forfait restant et la politique de gestion du site ne vous permet pas ' .
		'de reserver en ligne en dessous de ' . $nbr_min_forfait_restant . ' heures. ' .
		'Merci de contacter le bocal au ' . $tel_bocal . ' ou au ' . $tel_mairie . ' afin de debloquer la situation';			

		
	if ($fr >= $nbr_min_forfait_restant) return false;
	else {
		echo '<script type="text/javascript" >alert("' . $mes . '");history.go(-2);</script>';
		return true;
	}
}


// propose en telechargement u document de ttype ms-excel contenant les stats du site
function genereDocExcel() {



}

?>
