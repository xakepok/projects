ALTER TABLE `#__prj_contracts`
  ADD `managerID` INT NULL DEFAULT NULL
COMMENT 'Менеджер'
  AFTER `expID`,
  ADD INDEX (`managerID`);
ALTER TABLE `#__prj_contracts`
  ADD FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
CREATE TABLE `#__prj_contract_files` (
  `id` int(11) NOT NULL,
  `contractID` int(11) NOT NULL COMMENT 'ID сделки',
  `userID` int(11) NOT NULL COMMENT 'ID автора',
  `dat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время добавления',
  `path` text NOT NULL COMMENT 'Путь к файлу',
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Файлы сделок';
ALTER TABLE `#__prj_contract_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `userID` (`userID`);
ALTER TABLE `#__prj_contract_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_contract_files`
  ADD CONSTRAINT `#__prj_contract_files_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_contract_files_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
