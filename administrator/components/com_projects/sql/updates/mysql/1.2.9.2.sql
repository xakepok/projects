START TRANSACTION;
ALTER TABLE `#__prj_contract_items`
  ADD `fixed` TINYINT NULL DEFAULT NULL
COMMENT 'Пункт закреплён и не доступен для изменений'
  AFTER `value2`;
ALTER TABLE `#__prj_contracts`
  ADD `amount` FLOAT NOT NULL DEFAULT '0'
COMMENT 'Рассчитанная сумма договора'
  AFTER `number`;
COMMIT;