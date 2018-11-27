CREATE TABLE `#__prj_payments`
(
  `id`               int(11)    NOT NULL,
  `dat`              date       NOT NULL COMMENT 'Дата оплаты',
  `scoreID`          int(11)    NOT NULL COMMENT 'Номер счёта',
  `pp`               int(11)    NOT NULL COMMENT 'Номер платёжного поручения',
  `amount`           float      NOT NULL DEFAULT '0' COMMENT 'Сумма',
  `created_by`       int(11)    NOT NULL COMMENT 'Автор записи',
  `checked_out`      int(11)    NOT NULL,
  `checked_out_time` datetime   NOT NULL,
  `state`            tinyint(4) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Платежи';
ALTER TABLE `#__prj_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scoreID` (`scoreID`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `checked_out` (`checked_out`);
ALTER TABLE `#__prj_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_payments`
  ADD CONSTRAINT `#__prj_payments_ibfk_1` FOREIGN KEY (`scoreID`) REFERENCES `#__prj_scores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_payments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
