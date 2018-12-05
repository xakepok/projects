'use strict';
function getTodosCountOnDate() {
    var dat = document.querySelector("#jform_dat").value;
    var uid = document.querySelector("#jform_managerID_id").value;
    var url = '/administrator/index.php?option=com_projects&task=todos.getTodosCountOnDate&date=' + dat + '&uid=' + uid;
    fetch(url)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            document.querySelector("#hidden-Todos").style.display = 'block';
            document.querySelector("#actTodos").textContent = text.data.cnt;
            document.querySelector("#goTodo").setAttribute('href', '/administrator/index.php?option=com_projects&view=todos&date=' + dat + '&uid=' + uid);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}
