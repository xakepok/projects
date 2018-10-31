START TRANSACTION;

CREATE TABLE `#__prc_items` (
  `id`        int(11)                               NOT NULL,
  `sectionID` int(11)                               NOT NULL
  COMMENT 'ID раздела прайс-листа',
  `unit`      set ('piece', 'sqm', 'kit', 'letter') NOT NULL DEFAULT 'piece'
  COMMENT 'Единица измерения',
  `title_ru`  text COMMENT 'Название по-русски',
  `title_en`  text COMMENT 'Название по-английски',
  `price_rub` float                                          DEFAULT '0'
  COMMENT 'Цена в рублях',
  `price_usd` float                                          DEFAULT '0'
  COMMENT 'Цена в долларах',
  `price_eur` float                                          DEFAULT '0'
  COMMENT 'Цена в евро',
  `factor`    float                                 NOT NULL DEFAULT '1'
  COMMENT 'Множитель',
  `state`     tinyint(4)                            NOT NULL DEFAULT '1'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'Элементы прайс-листа';

--
-- Дамп данных таблицы `#__prc_items`
--

INSERT INTO `#__prc_items` (`id`,
                            `sectionID`,
                            `unit`,
                            `title_ru`,
                            `title_en`,
                            `price_rub`,
                            `price_usd`,
                            `price_eur`,
                            `factor`,
                            `state`)
VALUES (1, 1, 'piece', 'Величина регистрационного взноса', 'Registration fee', 30000.5, 500.5, 350.5, 1, 1),
       (2, 1, 'piece', 'Стоимость 1 кв. м. застройки \"ЭФФЕКТ\"', NULL, 17000, 300, 200, 1, 1),
       (3, 2, 'piece', 'Договор', NULL, 60000, 1000, 750, 0.95, 1),
       (10, 13, 'piece', 'Величина регистрационного взноса', 'Registration fee', 30000.5, 500.5, 350.5, 1, 1),
       (11, 13, 'piece', 'Стоимость 1 кв. м. застройки \"ЭФФЕКТ\"', NULL, 17000, 300, 200, 1, 1),
       (12, 14, 'piece', 'Договор', NULL, 60000, 1000, 750, 0.95, 1);

CREATE TABLE `#__prc_prices` (
  `id`    int(11)    NOT NULL,
  `title` text       NOT NULL
  COMMENT 'Название',
  `state` tinyint(4) NOT NULL DEFAULT '1'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Дамп данных таблицы `#__prc_prices`
--

INSERT INTO `#__prc_prices` (`id`, `title`, `state`)
VALUES (1, 'Прайс 1', 1),
       (2, 'Прайс 2', 1),
       (3, 'Прайс 3', 1);

CREATE TABLE `#__prc_sections` (
  `id`      int(11)    NOT NULL,
  `priceID` int(11)    NOT NULL
  COMMENT 'ID прайс-листа',
  `title`   text       NOT NULL
  COMMENT 'Название',
  `state`   tinyint(4) NOT NULL DEFAULT '1'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'Разделы прайс-листа';

INSERT INTO `#__prc_sections` (`id`, `priceID`, `title`, `state`)
VALUES (1, 1, 'Раздел 1 прайса 1', 1),
       (2, 1, 'Раздел 2 прайса 1', 1),
       (13, 3, 'Раздел 1 прайса 1', 1),
       (14, 3, 'Раздел 2 прайса 1', 1);

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
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `#__prc_items`
--
ALTER TABLE `#__prc_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT для таблицы `#__prc_prices`
--
ALTER TABLE `#__prc_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT для таблицы `#__prc_sections`
--
ALTER TABLE `#__prc_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `#__prc_items`
--
ALTER TABLE `#__prc_items`
  ADD CONSTRAINT `#__prc_items_ibfk_1` FOREIGN KEY (`sectionID`) REFERENCES `#__prc_sections` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__prc_sections`
--
ALTER TABLE `#__prc_sections`
  ADD CONSTRAINT `#__prc_sections_ibfk_1` FOREIGN KEY (`priceID`) REFERENCES `#__prc_prices` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `#__prj_projects`
  ADD `priceID` INT NULL DEFAULT NULL
COMMENT 'ID прайс-листа'
  AFTER `title_en`,
  ADD INDEX (`priceID`);

ALTER TABLE `#__prj_projects`
  ADD FOREIGN KEY (`priceID`) REFERENCES `#__prc_prices` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

COMMIT;
