START TRANSACTION;

CREATE TABLE `#__prj_exp_history` (
  `id` int(11) NOT NULL,
  `dat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время',
  `contractID` int(11) NOT NULL COMMENT 'ID сделки',
  `managerID` int(11) NOT NULL COMMENT 'ID менеджера',
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='История взаимодействия менеджеров и сделок';
ALTER TABLE `#__prj_exp_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `managerID` (`managerID`);
ALTER TABLE `#__prj_exp_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_exp_history`
  ADD CONSTRAINT `#__prj_exp_history_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_exp_history_ibfk_2` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `#__prj_exp` ADD `tip` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Форма собственности' AFTER `regID`;
ALTER TABLE `#__prj_exp` CHANGE `tip` `tip` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Форма собственности';
ALTER TABLE `#__prj_exp` ADD `checked_out` INT NOT NULL DEFAULT '0' AFTER `title_en`;
ALTER TABLE `#__prj_exp` ADD `checked_out_time` INT NULL DEFAULT NULL AFTER `checked_out`;
ALTER TABLE `#__prj_exp` CHANGE `checked_out_time` `checked_out_time` DATETIME NULL DEFAULT NULL;
ALTER TABLE `#__prj_contracts` ADD `parentID` INT NULL DEFAULT NULL COMMENT 'Родитель для соэкспонентов и демоцентра' AFTER `expID`;
ALTER TABLE `#__prj_contracts` ADD FOREIGN KEY (`parentID`) REFERENCES `#__prj_exp`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
