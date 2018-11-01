START TRANSACTION;

ALTER TABLE `#__prc_items` CHANGE `unit` `unit` SET('piece','sqm','kit','letter','pair','sym','pm','days','hours','nights','4h','1d1sqm','1sqm1p','1s1sqm','view','1pd') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'piece' COMMENT 'Единица измерения';

COMMIT;
