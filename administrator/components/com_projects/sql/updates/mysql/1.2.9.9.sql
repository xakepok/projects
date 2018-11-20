ALTER TABLE `#__prc_items`
  ADD `is_factor` TINYINT NOT NULL DEFAULT '0'
COMMENT 'Есть ли скидка'
  AFTER `column_3`;
