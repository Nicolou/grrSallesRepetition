# grrSallesRepetition
logiciel web LAMP pour la gestion de réservation de créneaux horaire pour des salles de répétition musicales.

Ce logiciel est basé sur la version 1.9.7 du GRR : http://grr.devome.com
A été ajouté en plus la gestion des 'groupes de musique' et la notion de 'forfais'. Cela permet de donc de géré les forfais en fonction des horaires pratiqués par les groupes ainsi que leur 'crédit'.


Installation:
pour fonctionner GRR à besoin :
 - d'être héberger sur un serveur web
 - d'avoir accès à une base de données MySql
 - d'avoir accès à PHP avec les modules de bases.


1.paramétrage de la base de données SQL :

	- Si ce n'est as déjà fait (certains FAI impose une seule base de données) , créer une base de données MySql.
	- créer un utilisateur ayant tout les droit sur cette base de données
renseigner le fichier include/connect.inc.php avec les paramètre de connexion.
	
exécuter le fichier de script tables.my.sql pour la création des tables

	mysql --user nico --password 'databasename' < tables.my.sql > restore.out





