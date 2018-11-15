START TRANSACTION;
UPDATE `#__contracts`
set `managerID` = NULL,
    `groupID`   = NULL;
ALTER TABLE `#__contracts`
  DROP FOREIGN KEY `#__contracts_ibfk_4`;
ALTER TABLE `#__contracts`
  DROP FOREIGN KEY `#__contracts_ibfk_1`;
ALTER TABLE `#__contracts`
  DROP `groupID`,
  DROP `managerID`;
ALTER TABLE `#__projects`
  ADD `managerID` INT NULL DEFAULT NULL
COMMENT 'ID руководителя'
  AFTER `title_en`,
  ADD `groupID` INT UNSIGNED NULL DEFAULT NULL
COMMENT 'ID проектной группы'
  AFTER `managerID`,
  ADD INDEX (`managerID`),
  ADD INDEX (`groupID`);
ALTER TABLE `#__projects`
  ADD FOREIGN KEY (`groupID`) REFERENCES `_usergroups` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
ALTER TABLE `#__projects`
  ADD FOREIGN KEY (`managerID`) REFERENCES `_users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
COMMIT;