START TRANSACTION;

CREATE TABLE `#__prj_exp` (
  `id`             int(11)    NOT NULL,
  `regID`          int(11)    NOT NULL
  COMMENT 'ID региона',
  `curatorID`      int(11)    NOT NULL
  COMMENT 'ID куратора из таблицы пользователей',
  `title_ru_full`  text COMMENT 'Полное название на русском',
  `title_ru_short` varchar(255)        DEFAULT NULL
  COMMENT 'Короткое название на русском',
  `title_en`       text COMMENT 'Название на английском',
  `state`          tinyint(4) NOT NULL DEFAULT '1'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'Экспоненты';

CREATE TABLE `#__prj_exp_bank` (
  `id`    int(11) NOT NULL,
  `exbID` int(11) NOT NULL
  COMMENT 'ID экспонента',
  `inn`   int(11) NOT NULL,
  `kpp`   int(11) NOT NULL,
  `rs`    int(11) NOT NULL,
  `ks`    int(11) NOT NULL,
  `bank`  text    NOT NULL,
  `bik`   int(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'Банковские реквизиты экспонентов';

ALTER TABLE `#__prj_exp_bank`
  CHANGE `inn` `inn` INT(11) NULL DEFAULT NULL
COMMENT 'ИНН',
  CHANGE `kpp` `kpp` INT(11) NULL DEFAULT NULL
COMMENT 'КПП',
  CHANGE `rs` `rs` INT(11) NULL DEFAULT NULL
COMMENT 'Расчётный счёт',
  CHANGE `ks` `ks` INT(11) NULL DEFAULT NULL
COMMENT 'Кор. счёт',
  CHANGE `bank` `bank` TEXT CHARACTER SET utf8
COLLATE utf8_general_ci NULL DEFAULT NULL
COMMENT 'Наименование банка',
  CHANGE `bik` `bik` INT(11) NULL DEFAULT NULL
COMMENT 'БИК';

ALTER TABLE `#__prj_exp_bank`
  CHANGE `inn` `inn` INT(11) NOT NULL
COMMENT 'ИНН',
  CHANGE `kpp` `kpp` INT(11) NOT NULL
COMMENT 'КПП',
  CHANGE `rs` `rs` INT(11) NOT NULL
COMMENT 'Расчётный счёт',
  CHANGE `ks` `ks` INT(11) NOT NULL
COMMENT 'Кор. счёт',
  CHANGE `bank` `bank` TEXT CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL
COMMENT 'Наименование банка',
  CHANGE `bik` `bik` INT(11) NOT NULL
COMMENT 'БИК';

CREATE TABLE `#__prj_exp_contacts` (
  `id` int(11) NOT NULL,
  `exbID` int(11) NOT NULL COMMENT 'ID экспоната',
  `addr_legal_ru` text COMMENT 'Юридический адрес по-русски',
  `addr_legal_en` text COMMENT 'Юридический адрес по-английски',
  `addr_fact` text COMMENT 'Фактический адрес',
  `phone_1` text COMMENT 'Телефон 1',
  `phone_2` text COMMENT 'Телефон 2',
  `fax` text COMMENT 'Fax',
  `email` text COMMENT 'Email',
  `site` text COMMENT 'Веб-сайт',
  `director_name` text COMMENT 'Руководитель',
  `director_post` text COMMENT 'Должность руководителя',
  `contact_person` text COMMENT 'Контактное лицо',
  `contact_data` text COMMENT 'Контактные данные'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Контакты экспонентов';

ALTER TABLE `#__prj_exp_bank` ADD UNIQUE(`exbID`);

ALTER TABLE `#__prj_exp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regID` (`regID`),
  ADD KEY `curatorID` (`curatorID`);

ALTER TABLE `#__prj_exp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_exp_bank`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `#__prj_exp_bank` ADD UNIQUE(`exbID`);

ALTER TABLE `#__prj_exp_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_exp_bank` ADD FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `#__prj_exp_contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exbID` (`exbID`);

ALTER TABLE `#__prj_exp_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `#__prj_exp_contacts`
  ADD CONSTRAINT `#__prj_exp_contacts_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

COMMIT;
