'use strict';
window.onload = function () {
    getDebt();
};
function getDebt() {
    var field = document.querySelector('#jform_contractID');
    var contract = field.options[field.selectedIndex].value;
    fetch('http://bgp.ru/administrator/index.php?option=com_projects&view=contracts&format=raw&id=' + contract)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var field = document.querySelector('#jform_amount');
            field.value = text.data[0].debt;
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}