START TRANSACTION;
ALTER TABLE `stxq0_prc_items`
  ADD `unit_2` SET ('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd') NULL DEFAULT NULL
COMMENT 'Вторая единица измерения'
  AFTER `unit`;
ALTER TABLE `stxq0_prc_items`
  ADD `price_rub_2` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в рублях (колонка 2)'
  AFTER `price_eur`,
  ADD `price_usd_2` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в долларах (колонка 2)'
  AFTER `price_rub_2`,
  ADD `price_eur_2` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в евро (колонка 2)'
  AFTER `price_usd_2`,
  ADD `price_rub_3` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в рублях (колонка 3)'
  AFTER `price_eur_2`,
  ADD `price_usd_3` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в долларах (колонка 3)'
  AFTER `price_rub_3`,
  ADD `price_eur_3` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Цена в евро (колонка 3)'
  AFTER `price_usd_3`;
COMMIT;