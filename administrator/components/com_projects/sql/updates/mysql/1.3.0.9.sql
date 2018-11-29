ALTER TABLE `#__prj_stands`
  ADD `freeze`  TEXT NULL DEFAULT NULL COMMENT 'Фриз' AFTER `tip`,
  ADD `comment` TEXT NULL DEFAULT NULL COMMENT 'Примечание' AFTER `freeze`,
  ADD `status`  TINYINT NOT NULL DEFAULT '0' COMMENT 'Статус' AFTER `comment`,
  ADD `scheme`  TEXT NULL DEFAULT NULL COMMENT 'Путь к файлу схемы' AFTER `status`;
ALTER TABLE `#__prj_contracts` DROP `state`;
ALTER TABLE `stxq0_prj_exp_contacts` CHANGE `addr_legal_ru` `addr_legal_street` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Юридический адрес улица';
ALTER TABLE `stxq0_prj_exp_contacts` CHANGE `addr_legal_en` `addr_legal_home` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Юридический адрес дом';
ALTER TABLE `stxq0_prj_exp_contacts` CHANGE `addr_fact` `addr_fact_street` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Фактический адрес улица';
ALTER TABLE `stxq0_prj_exp_contacts` ADD `addr_fact_home` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Фактический адрес улица дом' AFTER `addr_fact_street`;
