START TRANSACTION;

CREATE TABLE `#__prc_items` (
  `id`        int(11)                               NOT NULL AUTO_INCREMENT,
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
  `state`     tinyint(4)                            NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sectionID` (`sectionID`),
  CONSTRAINT `#__prc_items_ibfk_1` FOREIGN KEY (`sectionID`) REFERENCES `#__prc_sections` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 13
  DEFAULT CHARSET = utf8
  COMMENT = 'Элементы прайс-листа';

CREATE TABLE `#__prc_prices` (
  `id`    int(11)    NOT NULL AUTO_INCREMENT,
  `title` text       NOT NULL
  COMMENT 'Название',
  `state` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARSET = utf8
  COMMENT = 'Прайс-листы';

CREATE TABLE `#__prc_sections` (
  `id`      int(11)    NOT NULL AUTO_INCREMENT,
  `priceID` int(11)    NOT NULL
  COMMENT 'ID прайс-листа',
  `title`   text       NOT NULL
  COMMENT 'Название',
  `state`   tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `priceID` (`priceID`),
  CONSTRAINT `#__prc_sections_ibfk_1` FOREIGN KEY (`priceID`) REFERENCES `#__prc_prices` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 15
  DEFAULT CHARSET = utf8
  COMMENT = 'Разделы прайс-листа'

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
