DELIMITER $$
create function getStandPavilion(number varchar(7)) returns varchar(2)
BEGIN
       DECLARE a VARCHAR(2);
       SET a = LEFT(number,2);
       RETURN IF(a REGEXP "^[[:digit:]][a-nA-N]$" = 1,LEFT(a,1),IF(a REGEXP "[A-Na-n][0-9]" = 1,'V',IF(a REGEXP "[Oo][0-9]" = 1,'Z',CONCAT(RIGHT(a,1),LEFT(a,1)))));
end $$
DELIMITER ;