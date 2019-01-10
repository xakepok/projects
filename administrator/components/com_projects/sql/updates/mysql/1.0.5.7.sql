ALTER TABLE `#__prj_stands`
  ADD `catalogID` INT NULL DEFAULT NULL COMMENT 'ID стенда из каталога' AFTER `id` ,
  ADD INDEX (`catalogID`);
ALTER TABLE `#__prj_stands`
  ADD `sq` FLOAT NOT NULL DEFAULT '0' COMMENT 'Площадь стенда' AFTER `contractID`;
ALTER TABLE `#__prj_stands`
  ADD FOREIGN KEY (`catalogID`) REFERENCES `#__prj_catalog` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
