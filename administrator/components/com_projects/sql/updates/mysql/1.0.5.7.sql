ALTER TABLE `#__prj_stands`
  ADD `catalogID` INT NULL DEFAULT NULL COMMENT 'ID стенда из каталога' AFTER `id` ,
  ADD INDEX (`catalogID`);
ALTER TABLE `#__prj_stands`
  ADD `sq` FLOAT NOT NULL DEFAULT '0' COMMENT 'Площадь стенда' AFTER `contractID`;
ALTER TABLE `#__prj_stands`
  ADD FOREIGN KEY (`catalogID`) REFERENCES `#__prj_catalog` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `#__prj_stands`
  ADD `itemID` INT NULL DEFAULT NULL COMMENT 'ID пункта прайс-листа' AFTER `catalogID` ,
  ADD INDEX (`itemID`);
ALTER TABLE `#__prj_stands`
  ADD FOREIGN KEY (`itemID`) REFERENCES `#__prc_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
UPDATE `#__prj_stands`
SET `scheme` = NULL
WHERE `scheme` = '-1';
ALTER TABLE `#__prj_stands`
  ADD `show` TINYINT NOT NULL DEFAULT '1' COMMENT 'Показывать в списке стендов' AFTER `status` ,
  ADD INDEX (`show`);