START TRANSACTION;
CREATE TABLE `#__prj_scores` (
  `id` int(11) NOT NULL,
  `dat` date NOT NULL COMMENT 'Дата выставления',
  `contractID` int(11) NOT NULL COMMENT 'ID Договора',
  `number` varchar(25) NOT NULL COMMENT 'Внутренний номер',
  `amount` float NOT NULL COMMENT 'Сумма платежа',
  `state` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Платежи';
ALTER TABLE `#__prj_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`);
ALTER TABLE `#__prj_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_scores`
  ADD CONSTRAINT `#__prj_scores_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
