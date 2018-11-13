START TRANSACTION;
TRUNCATE `#__prj_contract_items`;
ALTER TABLE `#__prc_items`
  ADD `unit_2` SET ('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd') NULL DEFAULT NULL
COMMENT 'Вторая единица измерения'
  AFTER `unit`;
ALTER TABLE `#__prc_items`
  CHANGE `price_rub` `price_rub_u1_c1` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в рублях',
  CHANGE `price_usd` `price_usd_u1_c1` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в долларах',
  CHANGE `price_eur` `price_eur_u1_c1` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в евро';
ALTER TABLE `#__prc_items`
  ADD `price_rub_u2_c1` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_eur_u1_c1`,
  ADD `price_usd_u2_c1` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_rub_u2_c1`,
  ADD `price_eur_u2_c1` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_usd_u2_c1`;
ALTER TABLE `#__prc_items`
  ADD `price_rub_u1_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_eur_u2_c1`,
  ADD `price_usd_u1_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_rub_u1_c2`,
  ADD `price_eur_u1_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_usd_u1_c2`;
ALTER TABLE `#__prc_items`
  ADD `price_rub_u2_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_eur_u1_c2`,
  ADD `price_usd_u2_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_rub_u2_c2`,
  ADD `price_eur_u2_c2` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_usd_u2_c2`;
ALTER TABLE `#__prc_items`
  ADD `price_rub_u1_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_eur_u2_c2`,
  ADD `price_usd_u1_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_rub_u1_c3`,
  ADD `price_eur_u1_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_usd_u1_c3`;
ALTER TABLE `#__prc_items`
  ADD `price_rub_u2_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_eur_u1_c3`,
  ADD `price_usd_u2_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_rub_u2_c3`,
  ADD `price_eur_u2_c3` FLOAT NOT NULL DEFAULT '0'
  AFTER `price_usd_u2_c3`;
ALTER TABLE `#__prj_projects`
  ADD `columnID` TINYINT NOT NULL DEFAULT '1'
COMMENT 'Номер ценовой колонки'
  AFTER `priceID`;
ALTER TABLE `#__prj_contract_items`
  ADD `value2` FLOAT NULL DEFAULT NULL
COMMENT 'Значение из второй единицы измерения'
  AFTER `value`;
ALTER TABLE `#__prj_contract_items`
  ADD `factor` FLOAT NOT NULL DEFAULT '1'
COMMENT 'Множитель первой цены'
  AFTER `itemID`,
  ADD `factor2` FLOAT NOT NULL DEFAULT '1'
COMMENT 'Множитель второй цены'
  AFTER `factor`;
COMMIT;