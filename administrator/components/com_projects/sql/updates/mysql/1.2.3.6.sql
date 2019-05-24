create or replace view `#__prj_stat_v2` as
select i.id, i.contractID, i.itemID, i.value, cost.cost,
       round((cost.cost * i.value * ifnull(i.value2, 1) * ifnull(i.markup,1)) - (cost.cost * i.value * ifnull(i.value2, 1) * (1 - i.factor)),2) as price
from `#__prj_contract_items` i
         inner join (
    select ci.id,
           round((case c.currency when 'rub' then i.price_rub when 'usd' then i.price_usd when 'eur' then i.price_eur end) *
                 (case ci.columnID when 1 then i.column_1 when 2 then i.column_2 when 3 then i.column_3 end),2) as cost
    from `#__prj_contract_items` ci
             left join `#__prc_items` i on ci.itemID = i.id
             left join `#__prj_contracts` c on c.id = ci.contractID)
    cost on cost.id = i.id;