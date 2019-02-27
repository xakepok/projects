'use strict';
window.onload = function () {
    getDebt();
};
function getDebt() {
    var field = document.getElementById("jform_contractID");
    var txt = field.options[field.selectedIndex].getAttribute('data-amount');
    document.querySelector("#jform_amount").value = txt;
}