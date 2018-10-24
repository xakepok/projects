START TRANSACTION;

DROP TABLE IF EXISTS `#__prj_plans`;
CREATE TABLE `#__prj_plans` (
  `id` int(11) NOT NULL,
  `prjID` int(11) NOT NULL COMMENT 'ID проекта',
  `path` text NOT NULL COMMENT 'Путь к файлу'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Планировки проектов';

DROP TABLE IF EXISTS `#__prj_projects`;
CREATE TABLE `#__prj_projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Название',
  `title_ru` varchar(255) DEFAULT NULL COMMENT 'Название на русском языке',
  `title_en` varchar(255) DEFAULT NULL COMMENT 'Название на английском языке',
  `date_start` timestamp NOT NULL COMMENT 'Дата начала',
  `date_end` timestamp NOT NULL COMMENT 'Дата конца',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Состояние'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Проекты';

ALTER TABLE `#__prj_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prjID` (`prjID`);

ALTER TABLE `#__prj_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`);

ALTER TABLE `#__prj_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_plans`
  ADD CONSTRAINT `#__prj_plans_ibfk_1` FOREIGN KEY (`prjID`) REFERENCES `#__prj_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__prj_plans` ADD `state` TINYINT NOT NULL DEFAULT '1' AFTER `path`;
COMMIT;