CREATE TEMPORARY TABLE t1(
nom varchar( 100 ) ,
dateDebut date,
heureDebut time,
sens tinyint( 1 ) unsigned,
quantite decimal( 11, 1 ) ,
TYPE varchar( 15 )
);

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

INSERT INTO t1 
SELECT USER_ID as beneficiaire,
	 cast( BLDT AS date) AS datedebut,
	 cast( BLDT AS time) AS heuredebut,
	 1 AS sens,
	 QT,
	 "FORFAIT" AS TYPE
FROM grr_forfait_credit
;

INSERT INTO t1 
SELECT USER_ID as beneficiaire,
	 cast( BLDT AS date) AS datedebut,
	 cast( BLDT AS time) AS heuredebut,
	 1 AS sens,
	 QT_CREDIT,
	 "INVENTAIRE" AS TYPE
FROM grr_forfait_archive
;

select * from t1 order by datedebut DESC;
	