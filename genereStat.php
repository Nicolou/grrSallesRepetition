<?php
header('Content-type: application/vnd.ms-excel'); 
header('Content-disposition: attachment; filename="statBocal.csv"');

//libraisie de connexion 
include "include/connect.inc.php";
include "include/config.inc.php";
//include "include/misc.inc.php";
//include "include/functions.inc.php";
include "include/$dbsys.inc.php";
//include "include/mrbs_sql.inc.php";


$sql_1 = '
CREATE TEMPORARY TABLE t1(
nom varchar( 100 ) ,
dateDebut date,
heureDebut time,
sens tinyint( 1 ) unsigned,
quantite decimal( 11, 1 ) ,
TYPE varchar( 15 )
);
';
$sql_2 = '
INSERT INTO t1	
SELECT beneficiaire, 
	cast( from_unixtime( start_time ) AS date ) AS datedebut,
	cast( from_unixtime( start_time ) AS time ) AS heuredebut,
	2 AS sens, round( (end_time - start_time) /60 /60, 1 ) AS quantite,
	"RESA" AS TYPE
FROM grr_entry
WHERE moderate
IN ( 0, 2 )
AND room_id =1
;
';
$sql_3 = '
INSERT INTO t1 
SELECT USER_ID as beneficiaire,
	 cast( BLDT AS date) AS datedebut,
	 cast( BLDT AS time) AS heuredebut,
	 1 AS sens,
	 QT,
	 "FORFAIT" AS TYPE
FROM grr_forfait_credit
;
';
$sql_4 = '
INSERT INTO t1 
SELECT USER_ID as beneficiaire,
	 cast( BLDT AS date) AS datedebut,
	 cast( BLDT AS time) AS heuredebut,
	 1 AS sens,
	 QT_CREDIT,
	 "INVENTAIRE" AS TYPE
FROM grr_forfait_archive
;
';

$sql_5 = 'select * from t1 order by datedebut DESC;';


$csv = "NOM;DATE;HEURE;SENS;QUANTITE;TYPE\n"; 

mysql_query($sql_1);
mysql_query($sql_2);
mysql_query($sql_3);
mysql_query($sql_4);

$result = mysql_query($sql_5);

//ouverture du tableau
if ($result) {
	while ($row = mysql_fetch_row($result)) {
		$csv .= $row[0] . ";" . $row[1] . ";" .$row[2] . ";" .$row[3] . ";" . 
					number_format($row[4], 2, ',', ' ') . ";" .
					$row[5] . "\n"; 				
	}
}
 

print($csv); 
exit; 
 
?>