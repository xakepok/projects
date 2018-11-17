START TRANSACTION;
ALTER TABLE `#__prj_contract_items`
  ADD `columnID` INT NOT NULL DEFAULT '1'
COMMENT 'ID колонки в прайсе'
  AFTER `itemID`;
ALTER TABLE `#__prc_items`
  ADD `price_rub` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в рублях (начальная)'
  AFTER `title_en`,
  ADD `price_usd` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в долларах (начальная)'
  AFTER `price_rub`,
  ADD `price_eur` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в евро (начальная)'
  AFTER `price_usd`;
UPDATE `#__prc_items`
SET `price_rub` = `price_rub_u1_c1`,
    `price_usd` = `price_usd_u1_c1`,
    `price_eur` = `price_eur_u1_c1`;
ALTER TABLE `#__prc_items`
  DROP `price_rub_u1_c1`,
  DROP `price_usd_u1_c1`,
  DROP `price_eur_u1_c1`,
  DROP `price_rub_u2_c1`,
  DROP `price_usd_u2_c1`,
  DROP `price_eur_u2_c1`,
  DROP `price_rub_u1_c2`,
  DROP `price_usd_u1_c2`,
  DROP `price_eur_u1_c2`,
  DROP `price_rub_u2_c2`,
  DROP `price_usd_u2_c2`,
  DROP `price_eur_u2_c2`,
  DROP `price_rub_u1_c3`,
  DROP `price_usd_u1_c3`,
  DROP `price_eur_u1_c3`,
  DROP `price_rub_u2_c3`,
  DROP `price_usd_u2_c3`,
  DROP `price_eur_u2_c3`;
ALTER TABLE `#__prc_items`
  ADD `column_1` FLOAT NOT NULL DEFAULT '1'
COMMENT 'Коэффициент наценки в колонке 1'
  AFTER `price_eur`,
  ADD `column_2` FLOAT NOT NULL DEFAULT '1.5'
COMMENT 'Коэффициент наценки в колонке 2'
  AFTER `column_1`,
  ADD `column_3` FLOAT NOT NULL DEFAULT '2'
COMMENT 'Коэффициент наценки в колонке 3'
  AFTER `column_2`;
ALTER TABLE `#__prj_contract_items`
  DROP `factor2`;
ALTER TABLE `#__prc_items`
  ADD `is_markup` TINYINT NOT NULL DEFAULT '0'
COMMENT 'Есть ли наценка за позиционирование'
  AFTER `column_3`;
ALTER TABLE `#__prj_contract_items`
  DROP INDEX `contractID_2`;
COMMIT;