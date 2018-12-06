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
function updateTodo(id) {
    var result = document.querySelector("#todo_res_"+id).value;
    if (result.length < 1) return false;
    fetch('/administrator/index.php?option=com_projects&task=todos.close&id=' + id + '&result=' + result)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var td = document.querySelector('.resultTodo_' + id);
            td.textContent = text.data.dat + ": " + text.data.user+ " " + result;
            td = document.querySelector('.resultTodoState_' + id);
            td.textContent = 'Выполнена';
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}