-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Serveur: nicolas.montarnal.sql.free.fr
-- Généré le : Dim 11 Décembre 2011 à 20:26
-- Version du serveur: 5.0.83
-- Version de PHP: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `nicolas_montarnal`
--

-- --------------------------------------------------------

--
-- Structure de la table `grr_area`
--

CREATE TABLE IF NOT EXISTS `grr_area` (
  `id` int(11) NOT NULL auto_increment,
  `area_name` varchar(30) NOT NULL default '',
  `access` char(1) NOT NULL default '',
  `order_display` smallint(6) NOT NULL default '0',
  `ip_adr` varchar(15) NOT NULL default '',
  `morningstarts_area` smallint(6) NOT NULL default '0',
  `eveningends_area` smallint(6) NOT NULL default '0',
  `duree_max_resa_area` int(11) NOT NULL default '-1',
  `resolution_area` int(11) NOT NULL default '0',
  `eveningends_minutes_area` smallint(6) NOT NULL default '0',
  `weekstarts_area` smallint(6) NOT NULL default '0',
  `twentyfourhour_format_area` smallint(6) NOT NULL default '0',
  `calendar_default_values` char(1) NOT NULL default 'y',
  `enable_periods` char(1) NOT NULL default 'n',
  `display_days` varchar(7) NOT NULL default 'yyyyyyy',
  `id_type_par_defaut` int(11) NOT NULL default '-1',
  `duree_par_defaut_reservation_area` int(11) NOT NULL default '0',
  `max_booking` smallint(6) NOT NULL default '-1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `grr_area`
--

INSERT INTO `grr_area` (`id`, `area_name`, `access`, `order_display`, `ip_adr`, `morningstarts_area`, `eveningends_area`, `duree_max_resa_area`, `resolution_area`, `eveningends_minutes_area`, `weekstarts_area`, `twentyfourhour_format_area`, `calendar_default_values`, `enable_periods`, `display_days`, `id_type_par_defaut`, `duree_par_defaut_reservation_area`, `max_booking`) VALUES
(1, 'Nom Organisation', 'a', 0, '', 9, 23, 180, 1800, 59, 1, 1, 'n', 'n', 'yyyyyyy', 1, 3600, 2);

-- --------------------------------------------------------

--
-- Structure de la table `grr_area_periodes`
--

CREATE TABLE IF NOT EXISTS `grr_area_periodes` (
  `id_area` int(11) NOT NULL default '0',
  `num_periode` smallint(6) NOT NULL default '0',
  `nom_periode` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_area`,`num_periode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_area_periodes`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_calendar`
--

CREATE TABLE IF NOT EXISTS `grr_calendar` (
  `DAY` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_calendar`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_calendrier_jours_cycle`
--

CREATE TABLE IF NOT EXISTS `grr_calendrier_jours_cycle` (
  `DAY` int(11) NOT NULL default '0',
  `Jours` varchar(20) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_calendrier_jours_cycle`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_entry`
--

CREATE TABLE IF NOT EXISTS `grr_entry` (
  `id` int(11) NOT NULL auto_increment,
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `entry_type` int(11) NOT NULL default '0',
  `repeat_id` int(11) NOT NULL default '0',
  `room_id` int(11) NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL default '',
  `beneficiaire_ext` varchar(200) NOT NULL default '',
  `beneficiaire` varchar(100) NOT NULL default '',
  `name` varchar(80) NOT NULL default '',
  `type` char(2) NOT NULL default 'A',
  `description` text,
  `statut_entry` char(1) NOT NULL default '-',
  `option_reservation` int(11) NOT NULL default '0',
  `overload_desc` text,
  `moderate` tinyint(1) default '0',
  `jours` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idxStartTime` (`start_time`),
  KEY `idxEndTime` (`end_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1266 ;

--
-- Contenu de la table `grr_entry`
--

-- --------------------------------------------------------

--
-- Structure de la table `grr_entry_moderate`
--

CREATE TABLE IF NOT EXISTS `grr_entry_moderate` (
  `id` int(11) NOT NULL auto_increment,
  `login_moderateur` varchar(40) NOT NULL default '',
  `motivation_moderation` text NOT NULL,
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `entry_type` int(11) NOT NULL default '0',
  `repeat_id` int(11) NOT NULL default '0',
  `room_id` int(11) NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL default '',
  `beneficiaire_ext` varchar(200) NOT NULL default '',
  `beneficiaire` varchar(100) NOT NULL default '',
  `name` varchar(80) NOT NULL default '',
  `type` char(2) default NULL,
  `description` text,
  `statut_entry` char(1) NOT NULL default '-',
  `option_reservation` int(11) NOT NULL default '0',
  `overload_desc` text,
  `moderate` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `idxStartTime` (`start_time`),
  KEY `idxEndTime` (`end_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1262 ;

--
-- Contenu de la table `grr_entry_moderate`
--

-- --------------------------------------------------------

--
-- Structure de la table `grr_forfait_archive`
--

CREATE TABLE IF NOT EXISTS `grr_forfait_archive` (
  `ID` int(11) NOT NULL auto_increment,
  `USER_ID` varchar(40) NOT NULL,
  `USER_DO` varchar(40) NOT NULL default ' ',
  `QT_CREDIT` decimal(11,1) NOT NULL,
  `BLDT` datetime NOT NULL,
  `WHY` tinytext,
  PRIMARY KEY  (`ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `BLDT` (`BLDT`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

--
-- Contenu de la table `grr_forfait_archive`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_forfait_credit`
--

CREATE TABLE IF NOT EXISTS `grr_forfait_credit` (
  `id` int(11) NOT NULL auto_increment,
  `USER_ID` varchar(40) NOT NULL,
  `USER_DO` varchar(40) NOT NULL default ' ',
  `QT` int(11) NOT NULL,
  `BLDT` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `USER_ID` (`USER_ID`),
  KEY `BLDT` (`BLDT`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=124 ;

--
-- Contenu de la table `grr_forfait_credit`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_mailuser_room`
--

CREATE TABLE IF NOT EXISTS `grr_j_mailuser_room` (
  `login` varchar(40) NOT NULL default '',
  `id_room` int(11) NOT NULL default '0',
  PRIMARY KEY  (`login`,`id_room`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_mailuser_room`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_site_area`
--

CREATE TABLE IF NOT EXISTS `grr_j_site_area` (
  `id_site` int(11) NOT NULL default '0',
  `id_area` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_site`,`id_area`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_site_area`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_type_area`
--

CREATE TABLE IF NOT EXISTS `grr_j_type_area` (
  `id_type` int(11) NOT NULL default '0',
  `id_area` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_type_area`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_useradmin_area`
--

CREATE TABLE IF NOT EXISTS `grr_j_useradmin_area` (
  `login` varchar(40) NOT NULL default '',
  `id_area` int(11) NOT NULL default '0',
  PRIMARY KEY  (`login`,`id_area`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_useradmin_area`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_useradmin_site`
--

CREATE TABLE IF NOT EXISTS `grr_j_useradmin_site` (
  `login` varchar(40) NOT NULL default '',
  `id_site` int(11) NOT NULL default '0',
  PRIMARY KEY  (`login`,`id_site`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_useradmin_site`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_user_area`
--

CREATE TABLE IF NOT EXISTS `grr_j_user_area` (
  `login` varchar(40) NOT NULL default '',
  `id_area` int(11) NOT NULL default '0',
  PRIMARY KEY  (`login`,`id_area`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_user_area`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_j_user_room`
--

CREATE TABLE IF NOT EXISTS `grr_j_user_room` (
  `login` varchar(40) NOT NULL default '',
  `id_room` int(11) NOT NULL default '0',
  PRIMARY KEY  (`login`,`id_room`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_j_user_room`
--

INSERT INTO `grr_j_user_room` (`login`, `id_room`) VALUES
('CATHERINE', 1),
('STEVE', 1);

-- --------------------------------------------------------

--
-- Structure de la table `grr_log`
--

CREATE TABLE IF NOT EXISTS `grr_log` (
  `LOGIN` varchar(40) NOT NULL default '',
  `START` datetime NOT NULL default '0000-00-00 00:00:00',
  `SESSION_ID` varchar(64) NOT NULL default '',
  `REMOTE_ADDR` varchar(16) NOT NULL default '',
  `USER_AGENT` varchar(255) NOT NULL default '',
  `REFERER` varchar(255) NOT NULL default '',
  `AUTOCLOSE` enum('0','1') NOT NULL default '0',
  `END` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`SESSION_ID`,`START`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_log`
--

-- --------------------------------------------------------

--
-- Structure de la table `grr_overload`
--

CREATE TABLE IF NOT EXISTS `grr_overload` (
  `id` int(11) NOT NULL auto_increment,
  `id_area` int(11) NOT NULL,
  `fieldname` varchar(25) NOT NULL default '',
  `fieldtype` varchar(25) NOT NULL default '',
  `fieldlist` text NOT NULL,
  `obligatoire` char(1) NOT NULL default 'n',
  `affichage` char(1) NOT NULL default 'n',
  `confidentiel` char(1) NOT NULL default 'n',
  `overload_mail` char(1) NOT NULL default 'n',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `grr_overload`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_repeat`
--

CREATE TABLE IF NOT EXISTS `grr_repeat` (
  `id` int(11) NOT NULL auto_increment,
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `rep_type` int(11) NOT NULL default '0',
  `end_date` int(11) NOT NULL default '0',
  `rep_opt` varchar(32) NOT NULL default '',
  `room_id` int(11) NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL default '',
  `beneficiaire_ext` varchar(200) NOT NULL default '',
  `beneficiaire` varchar(100) NOT NULL default '',
  `name` varchar(80) NOT NULL default '',
  `type` char(2) NOT NULL default 'A',
  `description` text,
  `rep_num_weeks` tinyint(4) default '0',
  `overload_desc` text,
  `jours` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `grr_repeat`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_room`
--

CREATE TABLE IF NOT EXISTS `grr_room` (
  `id` int(11) NOT NULL auto_increment,
  `area_id` int(11) NOT NULL default '0',
  `room_name` varchar(60) NOT NULL default '',
  `description` varchar(60) NOT NULL default '',
  `capacity` int(11) NOT NULL default '0',
  `max_booking` smallint(6) NOT NULL default '-1',
  `statut_room` char(1) NOT NULL default '1',
  `show_fic_room` char(1) NOT NULL default 'n',
  `picture_room` varchar(50) NOT NULL default '',
  `comment_room` text NOT NULL,
  `delais_max_resa_room` smallint(6) NOT NULL default '-1',
  `delais_min_resa_room` smallint(6) NOT NULL default '0',
  `allow_action_in_past` char(1) NOT NULL default 'n',
  `dont_allow_modify` char(1) NOT NULL default 'n',
  `order_display` smallint(6) NOT NULL default '0',
  `delais_option_reservation` smallint(6) NOT NULL default '0',
  `type_affichage_reser` smallint(6) NOT NULL default '0',
  `moderate` tinyint(1) default '0',
  `qui_peut_reserver_pour` char(1) NOT NULL default '5',
  `active_ressource_empruntee` char(1) NOT NULL default 'y',
  `who_can_see` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `grr_room`
--

INSERT INTO `grr_room` (`id`, `area_id`, `room_name`, `description`, `capacity`, `max_booking`, `statut_room`, `show_fic_room`, `picture_room`, `comment_room`, `delais_max_resa_room`, `delais_min_resa_room`, `allow_action_in_past`, `dont_allow_modify`, `order_display`, `delais_option_reservation`, `type_affichage_reser`, `moderate`, `qui_peut_reserver_pour`, `active_ressource_empruntee`, `who_can_see`) VALUES
(1, 1, 'Salle de répétition', 'c''est là où on fait la musique', 10, 2, '1', 'n', '', '', 30, 360, 'n', 'n', 0, 0, 0, 1, '3', 'n', 0);

-- --------------------------------------------------------

--
-- Structure de la table `grr_setting`
--

CREATE TABLE IF NOT EXISTS `grr_setting` (
  `NAME` varchar(32) NOT NULL default '',
  `VALUE` text NOT NULL,
  PRIMARY KEY  (`NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_setting`
--

INSERT INTO `grr_setting` (`NAME`, `VALUE`) VALUES
('sessionMaxLength', '30'),
('automatic_mail', 'yes'),
('company', 'Nom de la structure'),
('webmaster_name', 'nom du webmestre'),
('webmaster_email', 'adresse.mail.du@webmest.fr'),
('technical_support_email', 'nicolas.montarnal@free.fr'),
('grr_url', 'http://127.0.0.1/dev/grr/'),
('disable_login', 'yes'),
('begin_bookings', '1243807200'),
('end_bookings', '1569794400'),
('title_home_page', 'Gestion et Réservation de Ressources'),
('message_home_page', 'En raison du caractère personnel du contenu, ce site est soumis à des restrictions utilisateurs. Pour accéder aux outils de réservation, identifiez-vous :'),
('version', '1.9.6'),
('versionRC', ''),
('default_language', 'fr'),
('url_disconnect', ''),
('allow_users_modify_profil', '2'),
('allow_users_modify_email', '2'),
('allow_users_modify_mdp', '2'),
('maj194_champs_additionnels', '1'),
('maj195_champ_rep_type_grr_repeat', '1'),
('display_info_bulle', '1'),
('display_full_description', '1'),
('pview_new_windows', '1'),
('default_report_days', '30'),
('authentification_obli', '1'),
('use_fckeditor', '1'),
('visu_fiche_description', '2'),
('allow_search_level', '1'),
('allow_user_delete_after_begin', '0'),
('allow_gestionnaire_modify_del', '1'),
('javascript_info_disabled', '0'),
('javascript_info_admin_disabled', '0'),
('pass_leng', '2'),
('jour_debut_Jours/Cycles', '1'),
('nombre_jours_Jours/Cycles', '1'),
('UserAllRoomsMaxBooking', '2'),
('jours_cycles_actif', 'Non'),
('area_list_format', 'select'),
('longueur_liste_ressources_max', '20'),
('grr_mail_Password', ''),
('grr_mail_method', 'mail'),
('grr_mail_smtp', ''),
('grr_mail_Bcc', 'n'),
('grr_mail_Username', ''),
('verif_reservation_auto', '0'),
('ConvertLdapUtf8toIso', 'y'),
('ActiveModeDiagnostic', 'n'),
('ldap_champ_recherche', 'uid'),
('ldap_champ_nom', 'sn'),
('ldap_champ_prenom', 'givenname'),
('ldap_champ_email', 'mail'),
('gestion_lien_aide', 'ext'),
('lien_aide', ''),
('display_short_description', '1'),
('remplissage_description_breve', '2'),
('acces_fiche_reservation', '2'),
('display_level_email', '2'),
('nb_calendar', '2'),
('maj196_qui_peut_reserver_pour', '1'),
('default_site', '-1'),
('default_room', '1'),
('envoyer_email_avec_formulaire', 'no'),
('message_accueil', ''),
('use_grr_url', 'n'),
('default_css', 'forestier'),
('default_area', '1'),
('grr_mail_from', 'noreply@bocal.site.fr'),
('grr_mail_fromname', 'bocal'),
('motdepasse_verif_auto_grr', ''),
('motdepasse_backup', ''),
('date_verify_reservation', '1323558000'),
('logo', 'logo.png'),
('date_verify_reservation2', '1323558000');

-- --------------------------------------------------------

--
-- Structure de la table `grr_site`
--

CREATE TABLE IF NOT EXISTS `grr_site` (
  `id` int(11) NOT NULL auto_increment,
  `sitecode` varchar(10) default NULL,
  `sitename` varchar(50) NOT NULL default '',
  `adresse_ligne1` varchar(38) default NULL,
  `adresse_ligne2` varchar(38) default NULL,
  `adresse_ligne3` varchar(38) default NULL,
  `cp` varchar(5) default NULL,
  `ville` varchar(50) default NULL,
  `pays` varchar(50) default NULL,
  `tel` varchar(25) default NULL,
  `fax` varchar(25) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `grr_site`
--


-- --------------------------------------------------------

--
-- Structure de la table `grr_type_area`
--

CREATE TABLE IF NOT EXISTS `grr_type_area` (
  `id` int(11) NOT NULL auto_increment,
  `type_name` varchar(30) NOT NULL default '',
  `order_display` smallint(6) NOT NULL default '0',
  `couleur` smallint(6) NOT NULL default '0',
  `type_letter` char(2) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `grr_type_area`
--

INSERT INTO `grr_type_area` (`id`, `type_name`, `order_display`, `couleur`, `type_letter`) VALUES
(1, 'Repèt', 1, 1, 'A');

-- --------------------------------------------------------

--
-- Structure de la table `grr_utilisateurs`
--

CREATE TABLE IF NOT EXISTS `grr_utilisateurs` (
  `login` varchar(40) NOT NULL default '',
  `nom` varchar(30) NOT NULL default '',
  `prenom` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `statut` varchar(30) NOT NULL default '',
  `etat` varchar(20) NOT NULL default '',
  `default_site` smallint(6) NOT NULL default '0',
  `default_area` smallint(6) NOT NULL default '0',
  `default_room` smallint(6) NOT NULL default '0',
  `default_style` varchar(50) NOT NULL default '',
  `default_list_type` varchar(50) NOT NULL default '',
  `default_language` char(3) NOT NULL default '',
  `source` varchar(10) NOT NULL default 'local',
  `couleur` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `grr_utilisateurs` 
-- insertion administrateur, mot de passe : 'azerty'
INSERT INTO grr_utilisateurs VALUES ('ADMINISTRATEUR', 'Administrateur', 'grr', 'ab4f63f9ac65152575886860dde480a1', 'admin@labas.fr', 'administrateur', 'actif', 0, 0, 0, '', '', '','local',0);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
