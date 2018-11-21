ALTER TABLE `#__prj_contracts`
  ADD `managerID` INT NULL DEFAULT NULL
COMMENT 'Менеджер'
  AFTER `expID`,
  ADD INDEX (`managerID`);
ALTER TABLE `#__prj_contracts`
  ADD FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
