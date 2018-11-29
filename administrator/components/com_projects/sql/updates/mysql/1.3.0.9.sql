ALTER TABLE `#__prj_stands`
  ADD `freeze`  TEXT NULL DEFAULT NULL COMMENT 'Фриз' AFTER `tip`,
  ADD `comment` TEXT NULL DEFAULT NULL COMMENT 'Примечание' AFTER `freeze`,
  ADD `status`  TINYINT NOT NULL DEFAULT '0' COMMENT 'Статус' AFTER `comment`,
  ADD `scheme`  TEXT NULL DEFAULT NULL COMMENT 'Путь к файлу схемы' AFTER `status`;
ALTER TABLE `#__prj_contracts` DROP `state`;
