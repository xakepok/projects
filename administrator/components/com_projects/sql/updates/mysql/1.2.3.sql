START TRANSACTION;
CREATE TABLE `#__prj_todos` (
  `id` int(11) NOT NULL,
  `dat` date NOT NULL COMMENT 'Дата',
  `contractID` int(11) NOT NULL COMMENT 'ID контракта',
  `task` text NOT NULL COMMENT 'Задача',
  `result` text COMMENT 'Результат',
  `userOpen` int(11) NOT NULL COMMENT 'ID создателя задачи',
  `userClose` int(11) DEFAULT NULL COMMENT 'ID закрывшего задачу',
  `state` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Планировщик на сделку';
ALTER TABLE `#__prj_todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractID` (`contractID`),
  ADD KEY `userOpen` (`userOpen`),
  ADD KEY `userClose` (`userClose`);
ALTER TABLE `#__prj_todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__prj_todos`
  ADD CONSTRAINT `#__prj_todos_ibfk_1` FOREIGN KEY (`contractID`) REFERENCES `#__prj_contracts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_todos_ibfk_2` FOREIGN KEY (`userOpen`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_todos_ibfk_3` FOREIGN KEY (`userClose`) REFERENCES `#__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
