START TRANSACTION;

CREATE TABLE `#__prj_exp_act` (
  `id` int(11) NOT NULL,
  `exbID` int(11) NOT NULL COMMENT 'ID экспонента',
  `actID` int(11) NOT NULL COMMENT 'ID вида деятельности'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Виды деятельности';

ALTER TABLE `#__prj_exp_act`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exbID_2` (`exbID`,`actID`),
  ADD KEY `actID` (`actID`);

ALTER TABLE `#__prj_exp_act`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__prj_exp_act`
  ADD CONSTRAINT `#__prj_exp_act_ibfk_1` FOREIGN KEY (`exbID`) REFERENCES `#__prj_exp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__prj_exp_act_ibfk_2` FOREIGN KEY (`actID`) REFERENCES `#__prj_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

COMMIT;
