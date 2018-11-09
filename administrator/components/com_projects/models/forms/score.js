'use strict';
window.onload = function () {
    getDebt();
};
function getDebt() {
    var field = document.querySelector('#jform_contractID');
    var contract = field.options[field.selectedIndex].value;
    fetch('/administrator/index.php?option=com_projects&view=contracts&format=raw&id=' + contract)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var field = document.querySelector('#jform_amount');
            var cur = document.querySelector('#currency');
            field.value = text.data[0].debt;
            cur.innerText = text.data[0].currency;
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}