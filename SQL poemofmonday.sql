-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 10 mai 2018 à 15:46
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `poemsofmonday`
--
CREATE DATABASE IF NOT EXISTS `poemsofmonday` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `poemsofmonday`;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `id_author` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `name_article` varchar(255) NOT NULL,
  `main_article` text NOT NULL,
  `publish_date` timestamp NOT NULL,
  `img_article` int(1) NOT NULL,
  `note_art` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_article`),
  KEY `id_author` (`id_author`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `id_author`, `theme`, `name_article`, `main_article`, `publish_date`, `img_article`, `note_art`) VALUES
(1, 1, '3', 'Mer du nord', 'A quoi jouait-il cet enfant ?\r\nPersonne n\'en sut jamais rien\r\nOn le laissait seul dans un coin\r\nAvec un peu de sable blanc\r\n\r\nOn remarquait bien, certains jours,\r\nQu\'il arquait les bras tels des ailes\r\nEt qu\'il regardait loin, tr&egrave;s loin,\r\nComme du sommet d\'une tour.\r\n\r\nMais o&ugrave; s\'en allait-il ainsi \r\nAlors qu\'on le croyait assis ?\r\nLui-m&ecirc;me le sut-il jamais ?\r\n\r\nD&egrave;s qu\'il refermait les paupi&egrave;res,\r\nIl regagnait le grand palais\r\nD\'o&ugrave; il voyait toute la mer.', '2018-05-10 14:36:17', 1, 20),
(2, 1, '2', 'LA FILLETTE ET LE POEME', 'Le po&egrave;me, qu\'est-ce que c\'est ?\r\nM\'a demand&eacute; une fillette :\r\nDes pluies lissant leurs longues tresses,\r\nLe ciel frappant &agrave; mes volets,\r\nUn pommier tout seul dans un champ\r\nComme une cage de plein vent,\r\nLe visage triste et lass&eacute;\r\nD\'une lune blanche et glac&eacute;e,\r\nUn vol d\'oiseaux en libert&eacute;,\r\nUne odeur, un cri, une cl&eacute; ?&quot;\r\n\r\nEt je ne savais que r&eacute;pondre\r\nJeu de soleil ou ruse d\'ombre ? -\r\nComment aurais-je su mieux qu\'elle\r\nSi la po&eacute;sie a des ailes\r\nOu court &agrave; pied les champs du monde', '2018-05-10 14:48:17', 0, 20),
(3, 1, '1', 'IL OFFRAIT DU COEUR', 'Donc, il offrait du coeur\r\nAvec un tel sourire\r\nQu\'on s\'empressait d\'ailleurs\r\nEn tous lieux de le dire.\r\n\r\nOn en voulait partout,\r\nMais on finit pourtant\r\nPar se demander o&ugrave;\r\nIl en trouvait autant.\r\n\r\nEt il riait dans l\'ombre.\r\nC\'&eacute;tait son propre coeur\r\nVaste comme le monde\r\nQu\'il offrait &agrave; la ronde,\r\n\r\nOffrait pour un sourire\r\nQui r&eacute;pondait au sien,\r\nOffrait rien que pour dire\r\nAux gens : Portez vous bien!', '2018-05-10 14:49:34', 1, NULL),
(4, 1, '2', 'L&rsquo;ARTISTE', 'Il voulut peindre une rivi&egrave;re ; \r\nElle coula hors du tableau.\r\n\r\nIl peignit une pie gri&egrave;che ; \r\nElle s&rsquo;envola aussit&ocirc;t.  \r\n\r\nIl dessina une dorade ; \r\nD&rsquo;un bond, elle brisa le cadre.  \r\n\r\nIl peignit ensuite une &eacute;toile ; \r\nElle mit le feu &agrave; la toile.  \r\n\r\nAlors, il peignit une porte \r\nAu milieu m&ecirc;me du tableau.  \r\n\r\nElle s&rsquo;ouvrit sur d&rsquo;autres portes, \r\nEt il entra dans le ch&acirc;teau.', '2018-05-10 14:50:53', 1, NULL),
(5, 3, '2', 'L\' &eacute;tranger', 'L\' &eacute;tranger\r\nQui aimes-tu le mieux, homme &eacute;nigmatique, dis ? Ton p&egrave;re, ta m&egrave;re, ta soeur ou ton fr&egrave;re ?\r\nJe n\'ai ni p&egrave;re, ni m&egrave;re, ni soeur, ni fr&egrave;re.\r\nTes amis ?\r\nVous vous servez l&agrave; d\'une parole dont le sens m\'est rest&eacute; jusqu\'&agrave; ce jour inconnu.\r\nTa patrie ?\r\nJ\'ignore sous quelle latitude elle est situ&eacute;e.\r\nLa beaut&eacute; ?\r\nJe l\'aimerais volontiers, d&eacute;esse et immortelle.\r\nL\'or ?\r\nJe le hais comme vous ha&iuml;ssez Dieu.\r\nEh! qu\'aimes-tu donc, extraordinaire &eacute;tranger ?\r\nJ\'aime les nuages... les nuages qui passent... l&agrave;-bas... l&agrave;-bas... les merveilleux nuages !', '2018-05-10 14:59:02', 0, 18),
(6, 3, '1', 'Pour moi...', 'Quand les cloches du soir, dans leur lente vol&eacute;e, \r\nFeront descendre l\'heure au fond de la vall&eacute;e ; \r\nQuand tu n\'auras d\'amis, ni d\'amours pr&egrave;s de toi, \r\nPense &agrave; moi ! pense &agrave; moi !\r\n\r\nCar les cloches du soir avec leur voix sonore \r\nA ton c&oelig;ur solitaire iront parler encore ; \r\nEt l\'air fera vibrer ces mots autour de toi : \r\nAime-moi ! aime-moi !\r\n\r\nSi les cloches du soir &eacute;veillent tes alarmes, \r\nDemande au temps &eacute;mu qui passe entre nos larmes : \r\nLe temps dira toujours qu\'il n\'a trouv&eacute; que toi, \r\nPr&egrave;s de moi ! pr&egrave;s de moi !\r\n\r\nQuand les cloches du soir, si tristes dans l\'absence, \r\nTinteront sur mon c&oelig;ur ivre de ta pr&eacute;sence : \r\nAh ! c\'est le chant du ciel qui sonnera pour toi, \r\nEt pour moi ! et pour moi !', '2018-05-10 15:01:44', 0, NULL),
(7, 3, '2', 'Nous', 'L\'absent qu\'on n\'osait plus attendre est revenu. \r\nSans bruit il a pouss&eacute; la porte. \r\nSon chien, aveugle et sourd, au flair l\'a reconnu, \r\nEt par la grande cour l\'escorte.\r\n\r\nL\'enfant blond d\'autrefois est un homme aujourd\'hui. \r\nPar del&agrave; l\'Equateur sa trentaine est sonn&eacute;e, \r\nEt voil&agrave; bien dix ans qu\'on n\'a rien su de lui. \r\nPar les soleils de mer sa peau rude est tann&eacute;e.\r\n\r\nDu vieux perron de pierre il monte l\'escalier. \r\nLes fleurs d\'un ch&egrave;vrefeuille antique \r\nVersent, comme autrefois, leur baume hospitalier \r\nAu seuil de la maison rustique.\r\n\r\nIl h&eacute;site, il a peur, quand son pied touche au seuil. \r\nC\'est un pressentiment fun&egrave;bre qui l\'arr&ecirc;te : \r\nQui va-t-il retrouver ? les siens portant son deuil, \r\nOu des &ecirc;tres nouveaux dont le c&oelig;ur est en f&ecirc;te ?\r\n\r\nOn l\'aper&ccedil;oit d\'abord : &mdash; &laquo; Quel est cet &eacute;tranger \r\nQui chez les autres se hasarde \r\nSans &eacute;veiller la cloche, et semble interroger \r\nSi gravement ceux qu\'il regarde ? &raquo;\r\n\r\nServantes et valets ne le connaissent pas, \r\nMais la ma&icirc;tresse, assise et pr&egrave;s du feu courb&eacute;e, \r\nSe l&egrave;ve toute droite et lui tend ses deux bras. \r\nEn &eacute;touffant un cri de m&egrave;re elle est tomb&eacute;e.', '2018-05-10 15:04:09', 1, NULL),
(8, 4, '3', 'Sonnet', 'B&eacute;ant, je regardais du seuil d\'une chaumi&egrave;re \r\nDe grands sites muets, mobiles et changeants, \r\nQui, sous de frais glacis d\'ambre, d\'or et d\'argent, \r\nVivaient un infini d\'espace et de lumi&egrave;re.\r\n\r\nC\'&eacute;taient des fleuves blancs, des montagnes mystiques, \r\nDes rocs p&acirc;m&eacute;s de gloire et de solennit&eacute;, \r\nDes chaos engendrant de leur obscurit&eacute; \r\nDes &eacute;blouissements de for&ecirc;ts &eacute;lastiques.\r\n\r\nJe contemplais, noy&eacute; d\'extase, oubliant tout, \r\nLorsqu\'ainsi qu\'une rose &eacute;norme, tout &agrave; coup, \r\nLa Lune, y surgissant, fleurit ces paysages.\r\n\r\nUn tel charme &agrave; ce point m\'avait donc captiv&eacute; \r\nQue j\'avais bu des yeux, comme un aspect r&ecirc;v&eacute;, \r\nLa simple vision du ciel et des nuages !', '2018-05-10 15:09:20', 1, NULL),
(9, 4, '3', 'Je ne demande pas autre chose aux for&ecirc;ts', 'Je ne demande pas autre chose aux for&ecirc;ts \r\nQue de faire silence autour des antres frais \r\nEt de ne pas troubler la chanson des fauvettes. \r\nJe veux entendre aller et venir les navettes \r\nDe Pan, noir tisserand que nous entrevoyons \r\nEt qui file, en tordant l\'eau, le vent, les rayons, \r\nCe grand r&eacute;seau, la vie, immense et sombre toile \r\nO&ugrave; brille et tremble en bas la fleur, en haut l\'&eacute;toile.', '2018-05-10 15:10:36', 1, NULL),
(10, 5, '2', 'D&eacute;charge', 'Je me retrouve dehors, comme presque chaque soir,\r\nEn courant de la honte qui salit ma chemise noire\r\nDans l\'obscurit&eacute; nocturne, j\'essaie de me cacher\r\nDes esprits qui rigolent dans mon dos d&eacute;tourn&eacute;\r\n\r\nMais le temps n\'est pas &eacute;ternel, on r&ecirc;ve tous de l&rsquo;arr&ecirc;ter\r\nEn sachant que c\'est impossible on continue d\'y r&ecirc;ver.\r\nLe soleil se l&egrave;ve et avec lui l\'espoir\r\nDe r&eacute;aliser les r&ecirc;ves qu\'on &eacute;l&egrave;ve les soirs.\r\n\r\nC\'est dur de changer la vie, c\'est dur de changer le destin\r\nSauf si on le construit nous m&ecirc;mes, avec nos propres mains.\r\nJ\'ai peur de perdre - j\'ai peur de gagner\r\nLes d&eacute;sirs que je r&ecirc;ve depuis une &eacute;ternit&eacute;\r\n\r\nMais rester sans faire aucun geste n\'est pas une solution\r\nIl faut le meilleur pour devenir champion.\r\nSi tu saurais combien de fois j\'ai voulu abandonner\r\nMais &agrave; ce moment l&agrave;, \r\nUne voix me chuchote : \r\n&laquo; Essaie encore une fois &raquo;\r\n\r\nJe suis sur la sc&egrave;ne, comme presque chaque jour,\r\nEn allant vers la victoire que je lis sur chaque mur\r\nDans la lumi&egrave;re du jour j\'essaie de me montrer\r\nAux yeux des gens qui rigolent avec moi d\'un sujet.', '2018-05-10 15:13:26', 1, NULL),
(11, 6, '2', 'Ce qui dure', 'Le pr&eacute;sent se fait vide et triste, \r\n&Ocirc; mon amie, autour de nous ; \r\nCombien peu de pass&eacute; subsiste ! \r\nEt ceux qui restent changent tous.\r\n\r\nNous ne voyons plus sans envie \r\nLes yeux de vingt ans resplendir, \r\nEt combien sont d&eacute;j&agrave; sans vie \r\nDes yeux qui nous ont vus grandir !\r\n\r\nQue de jeunesse emporte l\'heure, \r\nQui n\'en rapporte jamais rien ! \r\nPourtant quelque chose demeure : \r\nJe t\'aime avec mon c&oelig;ur ancien,\r\n\r\nMon vrai c&oelig;ur, celui qui s\'attache \r\nEt souffre depuis qu\'il est n&eacute;, \r\nMon c&oelig;ur d\'enfant, le c&oelig;ur sans tache \r\nQue ma m&egrave;re m\'avait donn&eacute; ;\r\n\r\nCe c&oelig;ur o&ugrave; plus rien ne p&eacute;n&egrave;tre, \r\nD\'o&ugrave; plus rien d&eacute;sormais ne sort ; \r\nJe t\'aime avec ce que mon &ecirc;tre \r\nA de plus fort contre la mort ;\r\n\r\nEt, s\'il peut braver la mort m&ecirc;me, \r\nSi le meilleur de l\'homme est tel \r\nQue rien n\'en p&eacute;risse, je t\'aime \r\nAvec ce que j\'ai d\'immortel.', '2018-05-10 15:22:32', 0, NULL),
(12, 6, '2', 'Romance', 'Dansez, fillettes du village, \r\nChantez vos doux refrains d\'amour : \r\nTrop vite, h&eacute;las ! un ciel d\'orage \r\nVient obscurcir le plus beau jour.\r\n\r\nEn vous voyant, je me rappelle \r\nEt mes plaisirs et mes succ&egrave;s ; \r\nComme vous, j\'&eacute;tais jeune et belle, \r\nEt, comme vous, je le savais. \r\nSoudain ma blonde chevelure \r\nMe montra quelques cheveux blancs... \r\nJ\'ai vu, comme dans la nature, \r\nL\'hiver succ&eacute;der au printemps.\r\n\r\nDansez, fillettes du village, \r\nChantez vos doux refrains d\'amour ; \r\nTrop vite, h&eacute;las ! un ciel d\'orage \r\nVient obscurcir le plus beau jour.\r\n\r\nNa&iuml;ve et sans exp&eacute;rience, \r\nD\'amour je crus les doux serments, \r\nEt j\'aimais avec confiance... \r\nOn croit au bonheur &agrave; quinze ans !\r\nUne fleur, par Julien cueillie, \r\n&Eacute;tait le gage de sa foi ; \r\nMais, avant qu\'elle f&ucirc;t fl&eacute;trie, \r\nL\'ingrat ne pensait plus &agrave; moi !\r\n\r\nDansez, fillettes du Village, \r\nChantez vos doux refrains d\'amour ; \r\nTrop vite, h&eacute;las ! un ciel d\'orage \r\nVient obscurcir le plus beau jour.\r\n\r\n&Agrave; vingt ans, un ami fid&egrave;le \r\nAdoucit mon premier chagrin ; \r\nJ\'&eacute;tais triste, mais j\'&eacute;tais belle, \r\nIl m\'offrit son c&oelig;ur et sa main. \r\nTrop t&ocirc;t pour nous vint la vieillesse ; \r\nNous nous aimions, nous &eacute;tions vieux... \r\nLa mort rompit notre tendresse... \r\nMon ami fut le plus heureux !\r\n\r\nDansez, fillettes du village, \r\nChantez vos doux refrains d\'amour ; \r\nTrop vite, h&eacute;las ! un ciel d\'orage \r\nVient obscurcir le plus beau jour.\r\n\r\nPour moi, n\'arr&ecirc;tez pas la danse ; \r\nLe ciel est pur, je suis au port, \r\nAux bruyants plaisirs de l\'enfance \r\nLa grand-m&egrave;re sourit encor. \r\nQue cette larme que j\'efface \r\nN\'attriste pas vos jeunes c&oelig;urs : \r\nLe soleil brille sur la glace, \r\nL\'hiver conserve quelques fleurs.\r\n\r\nDansez, fillettes du village, \r\nChantez vos doux refrains d\'amour, \r\nEt, sous un ciel exempt d\'orage, \r\nEmbellissez mon dernier jour !', '2018-05-10 15:23:31', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `article_note`
--

DROP TABLE IF EXISTS `article_note`;
CREATE TABLE IF NOT EXISTS `article_note` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL,
  `id_rater` int(11) NOT NULL,
  `value_note` int(11) NOT NULL,
  PRIMARY KEY (`id_note`),
  KEY `id_article` (`id_article`),
  KEY `id_article_2` (`id_article`),
  KEY `id_rater` (`id_rater`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article_note`
--

INSERT INTO `article_note` (`id_note`, `id_article`, `id_rater`, `value_note`) VALUES
(1, 1, 1, 20),
(2, 5, 3, 18),
(3, 2, 5, 20);

-- --------------------------------------------------------

--
-- Structure de la table `article_theme`
--

DROP TABLE IF EXISTS `article_theme`;
CREATE TABLE IF NOT EXISTS `article_theme` (
  `id_theme` int(11) NOT NULL AUTO_INCREMENT,
  `name_theme` varchar(255) NOT NULL,
  PRIMARY KEY (`id_theme`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article_theme`
--

INSERT INTO `article_theme` (`id_theme`, `name_theme`) VALUES
(1, 'Amour'),
(2, 'Vie'),
(3, 'Nature'),
(4, 'Amitié'),
(5, 'Ange'),
(6, 'Angoisse'),
(7, 'Art'),
(8, 'Liberté'),
(9, 'Musique'),
(10, 'Paix'),
(11, 'Nostalgie'),
(12, 'Patrie'),
(13, 'Regrets'),
(14, 'Avenir'),
(15, 'Jalousie'),
(16, 'Etérnité'),
(17, 'Travail'),
(18, 'Destin');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id_comment` int(11) NOT NULL AUTO_INCREMENT,
  `id_article` int(11) NOT NULL,
  `id_poster` int(11) NOT NULL,
  `main_comment` text NOT NULL,
  `comment_date` datetime NOT NULL,
  `like_comment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_comment`),
  KEY `id_article` (`id_article`),
  KEY `id_poster` (`id_poster`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id_comment`, `id_article`, `id_poster`, `main_comment`, `comment_date`, `like_comment`) VALUES
(1, 1, 1, 'J\'esp&egrave;re que vous appr&eacute;ciez cette premi&egrave;re po&eacute;sie  sur notre plateforme.', '2018-05-10 16:45:11', 1),
(2, 5, 3, 'Merci pour vos encouragements!! :D', '2018-05-10 16:59:34', 0),
(3, 7, 5, 'J\'adore ;)', '2018-05-10 17:14:27', 1),
(6, 7, 4, 'Continue ainsi!', '2018-05-10 17:15:19', 1);

-- --------------------------------------------------------

--
-- Structure de la table `comment_like`
--

DROP TABLE IF EXISTS `comment_like`;
CREATE TABLE IF NOT EXISTS `comment_like` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `id_comment` int(11) NOT NULL,
  `id_liker` int(11) NOT NULL,
  `value_like` int(1) NOT NULL,
  PRIMARY KEY (`id_like`),
  KEY `id_liker` (`id_liker`),
  KEY `id_comment` (`id_comment`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment_like`
--

INSERT INTO `comment_like` (`id_like`, `id_comment`, `id_liker`, `value_like`) VALUES
(1, 1, 1, 1),
(2, 6, 5, 1),
(3, 3, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `id_follow` int(11) NOT NULL AUTO_INCREMENT,
  `id_followed` int(11) NOT NULL,
  `id_following` int(11) NOT NULL,
  `follow_date` date NOT NULL,
  PRIMARY KEY (`id_follow`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `follow`
--

INSERT INTO `follow` (`id_follow`, `id_followed`, `id_following`, `follow_date`) VALUES
(2, 2, 6, '2018-04-17'),
(3, 6, 2, '2018-04-10'),
(5, 2, 1, '2018-04-17');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL,
  `message` text NOT NULL,
  `message_date` datetime NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_sender` (`id_sender`),
  KEY `id_receiver` (`id_receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `plainte_message`
--

DROP TABLE IF EXISTS `plainte_message`;
CREATE TABLE IF NOT EXISTS `plainte_message` (
  `id_sender` int(11) NOT NULL,
  `id_receiver` int(11) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8 NOT NULL,
  `id_topic` int(11) NOT NULL,
  `date_message` datetime NOT NULL,
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `plainte_topic`
--

DROP TABLE IF EXISTS `plainte_topic`;
CREATE TABLE IF NOT EXISTS `plainte_topic` (
  `id_sender` int(11) NOT NULL,
  `objet` text NOT NULL,
  `message` text NOT NULL,
  `date_creation` datetime NOT NULL,
  `id_topic` int(11) NOT NULL AUTO_INCREMENT,
  `solved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_topic`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `recovery`
--

DROP TABLE IF EXISTS `recovery`;
CREATE TABLE IF NOT EXISTS `recovery` (
  `id_recovery` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `date_recovery` datetime DEFAULT NULL,
  PRIMARY KEY (`id_recovery`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `lname` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `nname` varchar(255) NOT NULL,
  `sex` int(1) NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.jpg',
  `date_inscription` date NOT NULL,
  `confirm_key` int(11) NOT NULL,
  `confirm` int(1) NOT NULL DEFAULT '0',
  `grade` int(11) NOT NULL DEFAULT '0',
  `bio` text,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `lname`, `fname`, `nname`, `sex`, `date_naissance`, `email`, `pw`, `avatar`, `date_inscription`, `confirm_key`, `confirm`, `grade`, `bio`) VALUES
(1, 'NomAdmin', 'PrenomAdmin', 'PseudoAdmin', 0, '1987-04-06', 'administrateur@gmail.com', '$2y$10$.e50YtJpH6AUDeSFiLOF6.cYB1TCKyUwQrPm3aBfEoTu91cNHOjv2', '11525962147.png', '2018-05-10', 569239292, 0, 3, 'Ceci est la biographie d\'un administrateur.\r\nVeuillez nous contacter en cas de probl&egrave;me.\r\nNotre &eacute;quipe est toujours pr&ecirc;te &agrave; vous aider.'),
(2, 'NomModerateur', 'PrenomModerateur', 'PseudoModerateur', 0, '1999-10-10', 'moderateur@gmail.com', '$2y$10$RhQXeuvMVD.lkzmqIMLjvOcS05q8s3sqVgtLjJa8dn.vG74CJSDR.', 'default0.jpg', '2018-05-10', 812820968, 0, 2, NULL),
(3, 'NomAuteur', 'PrenomAuteur', 'PseudoAuteur', 0, '1997-10-20', 'auteur@gmail.com', '$2y$10$0uN06RkPeONvu0OS2ieXKeXtS6AL6c3PucMICASWJ92SWLIARKMua', 'default0.jpg', '2018-05-10', 583077144, 0, 1, NULL),
(4, 'NomUtilisateur', 'PrenomUtilisateur', 'PseudoUtilisateur', 1, '1996-08-20', 'utilisateur@gmail.com', '$2y$10$y64P4s1H/PC7FGlPcEJfpeWUSZY2NP/h23D7LZKe1w67dAMmCI3Ju', '41525964751.jpg', '2018-05-10', 602280207, 0, 0, 'Pour vivre heureux vivons cach&eacute;s.\r\nJe me cache, sur un nuage... c\'est le cloud!'),
(5, 'Petic', 'Lucian', 'lpetic77', 1, '1998-10-20', 'lucian.petic14@gmail.com', '$2y$10$CLrkw3d16b.4KTRKpmI1aeKBXLXphJWaSfX9Onu4F1HYJEV1B.qSK', '51525965245.jpg', '2018-05-10', 158657198, 0, 0, NULL),
(6, 'Dupon', 'Luca', 'lucifer@gmail.com', 1, '2001-12-10', 'lucifer@gmail.com', '$2y$10$KTk1w/Fh8pwHYIgvsMaerOhkbvAzRRba.Z3mRzpGOtHvXJ8xJAP2y', 'default1.jpg', '2018-05-10', 373065957, 0, 0, NULL),
(7, 'Kiki', 'Loca', 'kik_loca', 0, '1999-10-05', 'kikasaibi@gmail.com', '$2y$10$j2vUvlrVFnyDtsuWkzUEQeQai9gyimocdHMHtY5/RnGRiVElH94Im', 'default0.jpg', '2018-05-10', 473688462, 0, 0, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `article_note`
--
ALTER TABLE `article_note`
  ADD CONSTRAINT `article_note_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
