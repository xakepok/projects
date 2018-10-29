START TRANSACTION;

CREATE TABLE `#__price_sections` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL COMMENT 'Название',
  `state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Разделы прайс-листа';

ALTER TABLE `#__price_sections`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__price_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
