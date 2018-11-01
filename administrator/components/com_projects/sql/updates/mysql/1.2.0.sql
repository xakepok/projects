START TRANSACTION;

CREATE TABLE `#__prj_contracts` (
  `id` int(11) NOT NULL,
  `prjID` int(11) NOT NULL COMMENT 'ID проекта',
  `expID` int(11) NOT NULL COMMENT 'ID экспонента',
  `managerID` int(11) NOT NULL COMMENT 'ID менеджера',
  `dat` date DEFAULT NULL COMMENT 'Дата заключения',
  `currency` set('rub','usd','eur') NOT NULL DEFAULT 'usd' COMMENT 'Валюта',
  `discount` float NOT NULL DEFAULT '1' COMMENT 'Скидочный коэффициент',
  `markup` float NOT NULL DEFAULT '1' COMMENT 'Наценка за позиционирование',
  `status` tinyint(4) DEFAULT NULL COMMENT 'Статус участия'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Договоры';

CREATE TABLE `#__prj_contract_items` (
  `id` int(11) NOT NULL,
  `contractID` int(11) NOT NULL COMMENT 'ID договора',
  `itemID` int(11) NOT NULL COMMENT 'ID пункта в прайс-листе',
  `value` float NOT NULL COMMENT 'Значение пункта из прайс-листа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Значения полей прайс-листа для договоров';

--
-- Индексы таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prjID` (`prjID`,`expID`),
  ADD KEY `managerID` (`managerID`),
  ADD KEY `expID` (`expID`);

--
-- Индексы таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contractID_2` (`contractID`,`itemID`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `itemID` (`itemID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  ADD CONSTRAINT `#__prj_contracts_ibfk_1` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_contracts_ibfk_2` FOREIGN KEY (`prjID`) REFERENCES `#__prj_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_contracts_ibfk_3` FOREIGN KEY (`expID`) REFERENCES `#__prj_exp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  ADD CONSTRAINT `#__prj_contract_items_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_contract_items_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `#__prc_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__prc_items` CHANGE `unit` `unit` SET('piece','sqm','kit','letter','pair','sym','pm','days','hours','nights','4h','1d1sqm','1sqm1p','1s1sqm','view','1pd') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'piece' COMMENT 'Единица измерения';

COMMIT;
