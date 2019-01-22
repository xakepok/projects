CREATE VIEW `#__prj_score_payments` AS
SELECT `p`.`scoreID`, `s`.`amount`, SUM(`p`.`amount`) as `payments`
FROM `#__prj_payments` as `p`
       LEFT JOIN `#__prj_scores` as `s` on `s`.`id` = `p`.`scoreID`
GROUP BY `scoreID`;