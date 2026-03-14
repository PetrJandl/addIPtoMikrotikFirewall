--
-- Table structure for table `IMAPwhiteList`
--

DROP TABLE IF EXISTS `IMAPwhiteList`;
CREATE TABLE `IMAPwhiteList` (
  `ip` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `network` varchar(32) COLLATE utf8_czech_ci DEFAULT NULL,
  `rangeBegin` varchar(15) COLLATE utf8_czech_ci DEFAULT NULL,
  `rangeEnd` varchar(15) COLLATE utf8_czech_ci DEFAULT NULL,
  `comment` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Triggers `IMAPwhiteList`
--
DROP TRIGGER IF EXISTS `insert`;
DELIMITER $$
CREATE TRIGGER `insert` BEFORE INSERT ON `IMAPwhiteList` FOR EACH ROW BEGIN

SET NEW.changed = NOW();

END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `last_update_row`;
DELIMITER $$
CREATE TRIGGER `last_update_row` BEFORE UPDATE ON `IMAPwhiteList` FOR EACH ROW BEGIN

SET NEW.changed = NOW();

END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `IMAPwhiteList`
--
ALTER TABLE `IMAPwhiteList`
  ADD PRIMARY KEY (`ip`),
  ADD UNIQUE KEY `ip` (`ip`);

