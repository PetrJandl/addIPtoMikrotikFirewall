--
-- Table structure for table `IMAPwhiteList`
--

DROP TABLE IF EXISTS `IMAPwhiteList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IMAPwhiteList` (
  `ip` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `network` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL,
  `rangeBegin` varchar(15) COLLATE utf8_czech_ci DEFAULT NULL,
  `rangeEnd` varchar(15) COLLATE utf8_czech_ci DEFAULT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

