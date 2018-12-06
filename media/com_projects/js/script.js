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
function showCard(id) {
    fetch('/administrator/index.php?option=com_projects&view=exhibitor&layout=edit&id=' + id + '&format=raw')
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var title = document.querySelector('#modalExpCardTitle');
            title.textContent = text.data.title;
            var myNode = document.querySelector('#cardValues');
            while (myNode.firstChild) {
                myNode.removeChild(myNode.firstChild);
            }
            if (text.data.phone_1 != null) {
                var phone1 = document.createElement('p');
                phone1.textContent = 'Телефон 1: ' + text.data.phone_1;
                myNode.appendChild(phone1);
            }
            if (text.data.phone_2 != null) {
                var phone2 = document.createElement('p');
                phone2.textContent = 'Телефон 2: ' + text.data.phone_2;
                myNode.appendChild(phone2);
            }
            if (text.data.email != null) {
                var email = document.createElement('p');
                var url = document.createElement('a');
                url.href = 'mailto:' + text.data.email;
                url.innerText = text.data.email;
                email.appendChild(url);
                myNode.appendChild(document.createTextNode('Электронная почта: '));
                myNode.appendChild(url);
            }
            if (text.data.site != null) {
                var site = document.createElement('p');
                var url1 = document.createElement('a');
                url1.href = text.data.site;
                url1.innerText = text.data.site;
                site.appendChild(url1);
                myNode.appendChild(document.createTextNode('Веб-сайт: '));
                myNode.appendChild(url1);
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}