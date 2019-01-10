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
CREATE VIEW `#__prj_contract_stands` AS
SELECT `s`.`id`,
       `contractID`,
       `s`.`number`,
       `s`.`sq`,
       `s`.`show`
FROM `#__prj_stands` as `s`
       LEFT JOIN `#__prj_catalog` as `c` ON `c`.`id` = `s`.`catalogID`
WHERE `s`.`catalogID` IS NOT NULL
  AND `s`.`itemID` IS NOT NULL;