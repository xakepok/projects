START TRANSACTION;
CREATE TABLE `#__prc_items` (
                                 `id` int(11) NOT NULL,
                                 `sectionID` int(11) NOT NULL COMMENT 'ID раздела прайс-листа',
                                 `unit` set('piece','sqm','kit','letter','pair','sym','pm','days','hours','nights','4h','1d1sqm','1sqm1p','1s1sqm','view','1pd') NOT NULL DEFAULT 'piece' COMMENT 'Единица измерения',
                                 `unit_2` set('piece','sqm','kit','letter','pair','sym','pm','days','hours','nights','4h','1d1sqm','1sqm1p','1s1sqm','view','1pd') DEFAULT NULL COMMENT 'Вторая единица измерения',
                                 `title_ru` text COMMENT 'Название по-русски',
                                 `title_en` text COMMENT 'Название по-английски',
                                 `price_rub` float NOT NULL DEFAULT '0' COMMENT 'Цена в рублях (начальная)',
                                 `price_usd` float NOT NULL DEFAULT '0' COMMENT 'Цена в долларах (начальная)',
                                 `price_eur` float NOT NULL DEFAULT '0' COMMENT 'Цена в евро (начальная)',
                                 `column_1` float NOT NULL DEFAULT '1' COMMENT 'Коэффициент наценки в колонке 1',
                                 `column_2` float NOT NULL DEFAULT '1.5' COMMENT 'Коэффициент наценки в колонке 2',
                                 `column_3` float NOT NULL DEFAULT '2' COMMENT 'Коэффициент наценки в колонке 3',
                                 `is_factor` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Есть ли скидка',
                                 `is_markup` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Есть ли наценка за позиционирование',
                                 `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Элементы прайс-листа';
CREATE TABLE `#__prc_prices` (
                                  `id` int(11) NOT NULL,
                                  `title` text NOT NULL COMMENT 'Название',
                                  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `#__prc_sections` (
                                    `id` int(11) NOT NULL,
                                    `priceID` int(11) NOT NULL COMMENT 'ID прайс-листа',
                                    `title` text NOT NULL COMMENT 'Название',
                                    `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Разделы прайс-листа';
CREATE TABLE `#__prj_activities` (
                                      `id` int(11) NOT NULL,
                                      `title` text NOT NULL COMMENT 'Название вида деятельности',
                                      `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Виды деятельности';
CREATE TABLE `#__prj_contracts` (
                                     `id` int(11) NOT NULL,
                                     `prjID` int(11) NOT NULL COMMENT 'ID проекта',
                                     `expID` int(11) NOT NULL COMMENT 'ID экспонента',
                                     `managerID` int(11) DEFAULT NULL COMMENT 'Менеджер',
                                     `parentID` int(11) DEFAULT NULL COMMENT 'Родитель для соэкспонентов и демоцентра',
                                     `dat` date DEFAULT NULL COMMENT 'Дата заключения',
                                     `currency` set('rub','usd','eur') NOT NULL DEFAULT 'usd' COMMENT 'Валюта',
                                     `status` tinyint(4) DEFAULT NULL COMMENT 'Статус участия',
                                     `number` varchar(255) DEFAULT NULL COMMENT 'Номер договора',
                                     `state` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Договоры';
CREATE TABLE `#__prj_contract_files` (
                                          `id` int(11) NOT NULL,
                                          `contractID` int(11) NOT NULL COMMENT 'ID сделки',
                                          `userID` int(11) NOT NULL COMMENT 'ID автора',
                                          `dat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время добавления',
                                          `path` text NOT NULL COMMENT 'Путь к файлу',
                                          `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Файлы сделок';
CREATE TABLE `#__prj_contract_items` (
                                          `id` int(11) NOT NULL,
                                          `contractID` int(11) NOT NULL COMMENT 'ID договора',
                                          `itemID` int(11) NOT NULL COMMENT 'ID пункта в прайс-листе',
                                          `columnID` int(11) NOT NULL DEFAULT '1' COMMENT 'ID колонки в прайсе',
                                          `factor` float NOT NULL DEFAULT '1' COMMENT 'Множитель первой цены',
                                          `markup` float DEFAULT NULL COMMENT 'Наценка за позиционирование',
                                          `value` float NOT NULL COMMENT 'Значение пункта из прайс-листа',
                                          `value2` float DEFAULT NULL COMMENT 'Значение из второй единицы измерения',
                                          `fixed` tinyint(4) DEFAULT NULL COMMENT 'Пункт закреплён и не доступен для изменений'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Значения полей прайс-листа для договоров';
CREATE TABLE `#__prj_exp` (
                               `id` int(11) NOT NULL,
                               `regID` int(11) UNSIGNED NOT NULL COMMENT 'ID региона',
                               `tip` varchar(25) DEFAULT NULL COMMENT 'Форма собственности',
                               `title_ru_full` text COMMENT 'Полное название на русском',
                               `title_ru_short` varchar(255) DEFAULT NULL COMMENT 'Короткое название на русском',
                               `title_en` text COMMENT 'Название на английском',
                               `checked_out` int(11) NOT NULL DEFAULT '0',
                               `checked_out_time` datetime DEFAULT NULL,
                               `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Экспоненты';
CREATE TABLE `#__prj_exp_act` (
                                   `id` int(11) NOT NULL,
                                   `exbID` int(11) NOT NULL COMMENT 'ID экспонента',
                                   `actID` int(11) NOT NULL COMMENT 'ID вида деятельности'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Виды деятельности';
CREATE TABLE `#__prj_exp_bank` (
                                    `id` int(11) NOT NULL,
                                    `exbID` int(11) NOT NULL COMMENT 'ID экспонента',
                                    `inn` int(11) DEFAULT NULL COMMENT 'ИНН',
                                    `kpp` int(11) DEFAULT NULL COMMENT 'КПП',
                                    `rs` int(11) DEFAULT NULL COMMENT 'Расчётный счёт',
                                    `ks` int(11) DEFAULT NULL COMMENT 'Кор. счёт',
                                    `bank` text COMMENT 'Наименование банка',
                                    `bik` int(11) DEFAULT NULL COMMENT 'БИК'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Банковские реквизиты экспонентов';
CREATE TABLE `#__prj_exp_contacts` (
                                        `id` int(11) NOT NULL,
                                        `exbID` int(11) NOT NULL COMMENT 'ID экспоната',
                                        `addr_legal_street` text COMMENT 'Юридический адрес улица',
                                        `addr_legal_home` text COMMENT 'Юридический адрес дом',
                                        `addr_fact_street` text COMMENT 'Фактический адрес улица',
                                        `addr_fact_home` text COMMENT 'Фактический адрес улица дом',
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
CREATE TABLE `#__prj_exp_history` (
                                       `id` int(11) NOT NULL,
                                       `dat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Время',
                                       `contractID` int(11) NOT NULL COMMENT 'ID сделки',
                                       `managerID` int(11) NOT NULL COMMENT 'ID менеджера',
                                       `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='История взаимодействия менеджеров и сделок';
CREATE TABLE `#__prj_exp_persons` (
                                       `id` int(11) NOT NULL,
                                       `exbID` int(11) NOT NULL COMMENT 'ID экспонента',
                                       `fio` text COMMENT 'ФИО',
                                       `post` text COMMENT 'Должность',
                                       `phone_work` text COMMENT 'Рабочий телефон',
                                       `phone_mobile` text COMMENT 'Мобильный телефон',
                                       `email` text COMMENT 'EMail'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Контактные лица экспонентов';
CREATE TABLE `#__prj_payments` (
                                    `id` int(11) NOT NULL,
                                    `dat` date NOT NULL COMMENT 'Дата оплаты',
                                    `scoreID` int(11) NOT NULL COMMENT 'Номер счёта',
                                    `pp` int(11) NOT NULL COMMENT 'Номер платёжного поручения',
                                    `amount` float NOT NULL DEFAULT '0' COMMENT 'Сумма',
                                    `created_by` int(11) NOT NULL COMMENT 'Автор записи',
                                    `checked_out` int(11) NOT NULL,
                                    `checked_out_time` datetime NOT NULL,
                                    `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Платежи';
CREATE TABLE `#__prj_plans` (
                                 `id` int(11) NOT NULL,
                                 `prjID` int(11) NOT NULL COMMENT 'ID проекта',
                                 `path` text NOT NULL COMMENT 'Путь к файлу',
                                 `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Планировки проектов';
CREATE TABLE `#__prj_projects` (
                                    `id` int(11) NOT NULL,
                                    `title` varchar(255) NOT NULL COMMENT 'Название',
                                    `title_ru` varchar(255) DEFAULT NULL COMMENT 'Название на русском языке',
                                    `title_en` varchar(255) DEFAULT NULL COMMENT 'Название на английском языке',
                                    `managerID` int(11) DEFAULT NULL COMMENT 'ID руководителя',
                                    `groupID` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID проектной группы',
                                    `priceID` int(11) DEFAULT NULL COMMENT 'ID прайс-листа',
                                    `columnID` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Номер ценовой колонки',
                                    `date_start` timestamp NOT NULL COMMENT 'Дата начала',
                                    `date_end` timestamp NOT NULL COMMENT 'Дата конца',
                                    `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Состояние'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Проекты';
CREATE TABLE `#__prj_scores` (
                                  `id` int(11) NOT NULL,
                                  `dat` date NOT NULL COMMENT 'Дата выставления',
                                  `contractID` int(11) NOT NULL COMMENT 'ID Договора',
                                  `number` varchar(25) NOT NULL COMMENT 'Внутренний номер',
                                  `amount` float NOT NULL COMMENT 'Сумма платежа',
                                  `state` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Платежи';
CREATE TABLE `#__prj_stands` (
                                  `id` int(11) NOT NULL,
                                  `contractID` int(11) NOT NULL COMMENT 'ID сделки',
                                  `number` varchar(25) DEFAULT NULL COMMENT 'Номер стенда',
                                  `tip` tinyint(4) NOT NULL COMMENT 'Тип стенда',
                                  `freeze` text COMMENT 'Фриз',
                                  `comment` text COMMENT 'Примечание',
                                  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Статус',
                                  `scheme` text COMMENT 'Путь к файлу схемы'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Стенды';
CREATE TABLE `#__prj_todos` (
                                 `id` int(11) NOT NULL,
                                 `dat` date NOT NULL COMMENT 'Дата, на которую назначено задание',
                                 `dat_open` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания задания',
                                 `dat_close` timestamp NULL DEFAULT NULL COMMENT 'Дата завершения задания',
                                 `contractID` int(11) NOT NULL COMMENT 'ID контракта',
                                 `managerID` int(11) NOT NULL COMMENT 'ID ответственного менеджера',
                                 `task` text NOT NULL COMMENT 'Задача',
                                 `result` text COMMENT 'Результат',
                                 `userOpen` int(11) NOT NULL COMMENT 'ID создателя задачи',
                                 `userClose` int(11) DEFAULT NULL COMMENT 'ID закрывшего задачу',
                                 `state` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Планировщик на сделку';
--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `#__prc_items`
--
ALTER TABLE `#__prc_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sectionID` (`sectionID`);

--
-- Индексы таблицы `#__prc_prices`
--
ALTER TABLE `#__prc_prices`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `#__prc_sections`
--
ALTER TABLE `#__prc_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `priceID` (`priceID`);

--
-- Индексы таблицы `#__prj_activities`
--
ALTER TABLE `#__prj_activities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expID` (`expID`),
  ADD KEY `prjID` (`prjID`) USING BTREE,
  ADD KEY `parentID` (`parentID`),
  ADD KEY `managerID` (`managerID`);

--
-- Индексы таблицы `#__prj_contract_files`
--
ALTER TABLE `#__prj_contract_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `userID` (`userID`);

--
-- Индексы таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `itemID` (`itemID`);

--
-- Индексы таблицы `#__prj_exp`
--
ALTER TABLE `#__prj_exp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regID` (`regID`);

--
-- Индексы таблицы `#__prj_exp_act`
--
ALTER TABLE `#__prj_exp_act`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exbID_2` (`exbID`,`actID`),
  ADD KEY `actID` (`actID`);

--
-- Индексы таблицы `#__prj_exp_bank`
--
ALTER TABLE `#__prj_exp_bank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exbID` (`exbID`);

--
-- Индексы таблицы `#__prj_exp_contacts`
--
ALTER TABLE `#__prj_exp_contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exbID` (`exbID`);

--
-- Индексы таблицы `#__prj_exp_history`
--
ALTER TABLE `#__prj_exp_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `managerID` (`managerID`);

--
-- Индексы таблицы `#__prj_exp_persons`
--
ALTER TABLE `#__prj_exp_persons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expID` (`exbID`);

--
-- Индексы таблицы `#__prj_payments`
--
ALTER TABLE `#__prj_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scoreID` (`scoreID`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `checked_out` (`checked_out`);

--
-- Индексы таблицы `#__prj_plans`
--
ALTER TABLE `#__prj_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prjID` (`prjID`);

--
-- Индексы таблицы `#__prj_projects`
--
ALTER TABLE `#__prj_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `priceID` (`priceID`),
  ADD KEY `managerID` (`managerID`),
  ADD KEY `groupID` (`groupID`);

--
-- Индексы таблицы `#__prj_scores`
--
ALTER TABLE `#__prj_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`);

--
-- Индексы таблицы `#__prj_stands`
--
ALTER TABLE `#__prj_stands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`);

--
-- Индексы таблицы `#__prj_todos`
--
ALTER TABLE `#__prj_todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `userOpen` (`userOpen`),
  ADD KEY `userClose` (`userClose`),
  ADD KEY `managerID` (`managerID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `#__prc_items`
--
ALTER TABLE `#__prc_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `#__prc_prices`
--
ALTER TABLE `#__prc_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `#__prc_sections`
--
ALTER TABLE `#__prc_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `#__prj_activities`
--
ALTER TABLE `#__prj_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `#__prj_contract_files`
--
ALTER TABLE `#__prj_contract_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp`
--
ALTER TABLE `#__prj_exp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp_act`
--
ALTER TABLE `#__prj_exp_act`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp_bank`
--
ALTER TABLE `#__prj_exp_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp_contacts`
--
ALTER TABLE `#__prj_exp_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp_history`
--
ALTER TABLE `#__prj_exp_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `#__prj_exp_persons`
--
ALTER TABLE `#__prj_exp_persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `#__prj_payments`
--
ALTER TABLE `#__prj_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `#__prj_plans`
--
ALTER TABLE `#__prj_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `#__prj_projects`
--
ALTER TABLE `#__prj_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `#__prj_scores`
--
ALTER TABLE `#__prj_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `#__prj_stands`
--
ALTER TABLE `#__prj_stands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `#__prj_todos`
--
ALTER TABLE `#__prj_todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `#__prc_items`
--
ALTER TABLE `#__prc_items`
  ADD CONSTRAINT `#__prc_items_ibfk_1` FOREIGN KEY (`sectionID`) REFERENCES `#__prc_sections` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prc_sections`
--
ALTER TABLE `#__prc_sections`
  ADD CONSTRAINT `#__prc_sections_ibfk_1` FOREIGN KEY (`priceID`) REFERENCES `#__prc_prices` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_contracts`
--
ALTER TABLE `#__prj_contracts`
  ADD CONSTRAINT `#__prj_contracts_ibfk_2` FOREIGN KEY (`prjID`) REFERENCES `#__prj_projects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_contracts_ibfk_3` FOREIGN KEY (`expID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_contracts_ibfk_5` FOREIGN KEY (`parentID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_contracts_ibfk_6` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_contract_files`
--
ALTER TABLE `#__prj_contract_files`
  ADD CONSTRAINT `#__prj_contract_files_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_contract_files_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_contract_items`
--
ALTER TABLE `#__prj_contract_items`
  ADD CONSTRAINT `#__prj_contract_items_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_contract_items_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `#__prc_items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_exp`
--
ALTER TABLE `#__prj_exp`
  ADD CONSTRAINT `#__prj_exp_ibfk_1` FOREIGN KEY (`regID`) REFERENCES `#__grph_cities` (`id`);

--
-- Ограничения внешнего ключа таблицы `#__prj_exp_act`
--
ALTER TABLE `#__prj_exp_act`
  ADD CONSTRAINT `#__prj_exp_act_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_exp_act_ibfk_2` FOREIGN KEY (`actID`) REFERENCES `#__prj_activities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_exp_bank`
--
ALTER TABLE `#__prj_exp_bank`
  ADD CONSTRAINT `#__prj_exp_bank_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_exp_contacts`
--
ALTER TABLE `#__prj_exp_contacts`
  ADD CONSTRAINT `#__prj_exp_contacts_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_exp_history`
--
ALTER TABLE `#__prj_exp_history`
  ADD CONSTRAINT `#__prj_exp_history_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_exp_history_ibfk_2` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_exp_persons`
--
ALTER TABLE `#__prj_exp_persons`
  ADD CONSTRAINT `#__prj_exp_persons_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_payments`
--
ALTER TABLE `#__prj_payments`
  ADD CONSTRAINT `#__prj_payments_ibfk_1` FOREIGN KEY (`scoreID`) REFERENCES `#__prj_scores` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_payments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_plans`
--
ALTER TABLE `#__prj_plans`
  ADD CONSTRAINT `#__prj_plans_ibfk_1` FOREIGN KEY (`prjID`) REFERENCES `#__prj_projects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_projects`
--
ALTER TABLE `#__prj_projects`
  ADD CONSTRAINT `#__prj_projects_ibfk_1` FOREIGN KEY (`priceID`) REFERENCES `#__prc_prices` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_projects_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `#__usergroups` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_projects_ibfk_3` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_scores`
--
ALTER TABLE `#__prj_scores`
  ADD CONSTRAINT `#__prj_scores_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_stands`
--
ALTER TABLE `#__prj_stands`
  ADD CONSTRAINT `#__prj_stands_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `#__prj_todos`
--
ALTER TABLE `#__prj_todos`
  ADD CONSTRAINT `#__prj_todos_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_todos_ibfk_2` FOREIGN KEY (`userOpen`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_todos_ibfk_3` FOREIGN KEY (`userClose`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `#__prj_todos_ibfk_4` FOREIGN KEY (`managerID`) REFERENCES `#__users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

COMMIT;