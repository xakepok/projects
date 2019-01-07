ALTER TABLE `#__prj_catalog`
  DROP FOREIGN KEY `#__prj_catalog_ibfk_1`;
ALTER TABLE `#__prj_catalog`
  DROP `itemID`;
ALTER TABLE `#__prj_contract_items`
  ADD `catalogID` INT NULL DEFAULT NULL COMMENT 'ID стенда из каталога' AFTER `itemID` ,
  ADD INDEX (`catalogID`);
ALTER TABLE `#__prj_contract_items`
  ADD FOREIGN KEY (`catalogID`) REFERENCES `#__prj_catalog` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;