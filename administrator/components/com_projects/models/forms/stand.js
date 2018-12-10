'use strict';
window.onload = function () {
    var field = document.querySelector("#jform_number");
    field.addEventListener('keyup', upper, false);

};
function upper() {
    var field = document.querySelector("#jform_number");
    field.value = field.value.toUpperCase();
}