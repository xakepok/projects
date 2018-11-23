CREATE TABLE `#__prj_stands` (
  `id`         int(11)    NOT NULL,
  `contractID` int(11)    NOT NULL
  COMMENT 'ID сделки',
  `number`     varchar(25) DEFAULT NULL
  COMMENT 'Номер стенда',
  `tip`        tinyint(4) NOT NULL
  COMMENT 'Тип стенда'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = 'Стенды';
ALTER TABLE `#__prj_stands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`);
ALTER TABLE `#__prj_stands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_stands`
  ADD CONSTRAINT `#__prj_stands_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
