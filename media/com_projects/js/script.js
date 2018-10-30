'use strict';
function imp(to)
{
    var id = document.querySelector("#valimp option:checked");
    console.log(id.value);
    if (id.value !== undefined)
    {
        location.href='/administrator/index.php?option=com_projects&task=sections.import&from=' + id.value + '&to=' + to;
    }
}
