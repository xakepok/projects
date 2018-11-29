ALTER TABLE `#__prj_stands`
  ADD `freeze`  TEXT NULL DEFAULT NULL COMMENT 'Фриз' AFTER `tip`,
  ADD `comment` TEXT NULL DEFAULT NULL COMMENT 'Примечание' AFTER `freeze`,
  ADD `status`  TINYINT NOT NULL DEFAULT '0' COMMENT 'Статус' AFTER `comment`,
  ADD `scheme`  TEXT NULL DEFAULT NULL COMMENT 'Путь к файлу схемы' AFTER `status`;
ALTER TABLE `#__prj_contracts`
  DROP `state`;
ALTER TABLE `#__prj_exp_contacts`
  CHANGE `addr_legal_ru` `addr_legal_street` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Юридический адрес улица';
ALTER TABLE `#__prj_exp_contacts`
  CHANGE `addr_legal_en` `addr_legal_home` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Юридический адрес дом';
ALTER TABLE `#__prj_exp_contacts`
  CHANGE `addr_fact` `addr_fact_street` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Фактический адрес улица';
ALTER TABLE `#__prj_exp_contacts`
  ADD `addr_fact_home` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Фактический адрес улица дом' AFTER `addr_fact_street`;
CREATE TABLE `#__prj_exp_persons`
(
  `id`           int(11) NOT NULL,
  `exbID`        int(11) NOT NULL COMMENT 'ID экспонента',
  `fio`          text COMMENT 'ФИО',
  `post`         text COMMENT 'Должность',
  `phone_work`   text COMMENT 'Рабочий телефон',
  `phone_mobile` text COMMENT 'Мобильный телефон',
  `email`        text COMMENT 'EMail'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Контактные лица экспонентов';
ALTER TABLE `#__prj_exp_persons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expID` (`exbID`);
ALTER TABLE `#__prj_exp_persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_exp_persons`
  ADD CONSTRAINT `#__prj_exp_persons_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
