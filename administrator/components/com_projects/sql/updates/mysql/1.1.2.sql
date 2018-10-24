START TRANSACTION;

CREATE TABLE `#__prj_exp` (
  `id` int(11) NOT NULL,
  `regID` int(11) NOT NULL COMMENT 'ID региона',
  `curatorID` int(11) NOT NULL COMMENT 'ID куратора из таблицы пользователей',
  `title_ru_full` text COMMENT 'Полное название на русском',
  `title_ru_short` varchar(255) DEFAULT NULL COMMENT 'Короткое название на русском',
  `title_en` text COMMENT 'Название на английском',
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Экспоненты';

CREATE TABLE `#__prj_exp_bank` (
  `id` int(11) NOT NULL,
  `exbID` int(11) NOT NULL COMMENT 'ID экспонента',
  `inn` int(11) NOT NULL,
  `kpp` int(11) NOT NULL,
  `rs` int(11) NOT NULL,
  `ks` int(11) NOT NULL,
  `bank` text NOT NULL,
  `bik` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Банковские реквизиты экспонентов';


ALTER TABLE `#__prj_exp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regID` (`regID`),
  ADD KEY `curatorID` (`curatorID`);

ALTER TABLE `#__prj_exp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_exp_bank`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exbID` (`exbID`);

ALTER TABLE `#__prj_exp_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


COMMIT;
