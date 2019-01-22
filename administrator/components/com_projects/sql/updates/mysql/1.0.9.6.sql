alter table `#__prc_items`
  modify unit set ('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd', 'ppl', 'zvqm') default 'piece' not null comment 'Единица измерения';

alter table `#__prc_items`
  modify unit_2 set ('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd', 'ppl', 'zvqm') null comment 'Вторая единица измерения';
