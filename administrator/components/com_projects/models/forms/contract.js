'use strict';
function setNumber() {
    var status = document.getElementById("jform_status");
    var tip = status.options[status.selectedIndex].value;
    if (tip === '1') loadNumber();
}

function loadNumber() {
    fetch('/administrator/index.php?option=com_projects&task=contracts.getNumber')
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            document.querySelector('#jform_number').value = text.data.number;
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}