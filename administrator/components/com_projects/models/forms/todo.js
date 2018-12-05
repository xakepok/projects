'use strict';
function getTodosCountOnDate() {
    var dat = document.getElementById("jform_dat").value;
    fetch('/administrator/index.php?option=com_projects&task=todos.getTodosCountOnDate&date=' + dat)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            document.querySelector("#hidden-Todos").style.display = 'block';
            document.querySelector("#actTodos").textContent = text.data.cnt;
            document.querySelector("#goTodo").setAttribute('href', '/administrator/index.php?option=com_projects&view=todos&date=' + dat);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}
