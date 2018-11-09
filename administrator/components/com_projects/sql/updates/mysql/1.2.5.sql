START TRANSACTION;
TRUNCATE TABLE `stxq0_prj_todos`;
ALTER TABLE `stxq0_prj_todos`
  ADD `dat_open` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
COMMENT 'Дата создания задания'
  AFTER `dat`,
  ADD `dat_close` TIMESTAMP NULL DEFAULT NULL
COMMENT 'Дата завершения задания'
  AFTER `dat_open`;
ALTER TABLE `stxq0_prj_todos`
  CHANGE `dat` `dat` DATE NOT NULL
COMMENT 'Дата, на которую назначено задание';
ALTER TABLE `stxq0_prj_todos`
  ADD `managerID` INT NOT NULL
COMMENT 'ID ответственного менеджера'
  AFTER `contractID`,
  ADD INDEX (`managerID`);
ALTER TABLE `stxq0_prj_todos`
  ADD FOREIGN KEY (`managerID`) REFERENCES `stxq0_users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE ;
COMMIT;
