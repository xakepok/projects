create or replace view `#__prj_contract_amounts` as
select `contractID`, sum(`price`) as `price`, `currency`
from `#__prj_stat`
group by `contractID`;
