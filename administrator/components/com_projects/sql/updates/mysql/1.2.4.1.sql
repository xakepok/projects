create or replace view `#__prc_item_types` as
select ci.id, ci.contractID, ci.itemID,
       IF(pi.is_sq = 1, 'stand',IF(pi.is_multimedia = 1 OR pi.is_internet = 1 OR pi.is_electric = 1 OR pi.is_cleaning = 1 OR pi.is_water = 1,'advanced', 'standard')) as `tip`
from `#__prj_contract_items` ci
         left join `#__prc_items` pi on ci.itemID = pi.id;
