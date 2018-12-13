ALTER TABLE `#__prj_exp`
  ADD `comment` TEXT NULL DEFAULT NULL COMMENT 'Примечание' AFTER `title_en`;
ALTER TABLE `#__prj_exp_contacts`
  ADD `indexcode_fact` VARCHAR(6) NULL DEFAULT NULL COMMENT 'Фактический индекс' AFTER `indexcode`;
ALTER TABLE `#__prj_exp_contacts`
  ADD `phone_1_comment` TEXT NULL DEFAULT NULL COMMENT 'Комментарий к тел. 1' AFTER `phone_2` ,
  ADD `phone_2_comment` TEXT NULL DEFAULT NULL COMMENT 'Комментарий к тел. 2' AFTER `phone_1_comment`;
ALTER TABLE `#__prj_exp`
  ADD `regID_fact` INT NULL DEFAULT NULL COMMENT 'Фактический город' AFTER `regID` ,
  ADD INDEX (`regID_fact`);
ALTER TABLE `#__prj_exp`
  CHANGE `regID_fact` `regID_fact` INT(11) UNSIGNED NULL DEFAULT NULL COMMENT 'Фактический город';
ALTER TABLE `#__prj_exp`
  ADD FOREIGN KEY (`regID_fact`) REFERENCES `#__grph_cities` (
                                                              `id`
    ) ON DELETE RESTRICT ON UPDATE RESTRICT;
UPDATE `#__prj_exp` SET `tip` = NULL WHERE `tip` = '';