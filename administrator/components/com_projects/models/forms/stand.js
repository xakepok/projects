'use strict';
window.onload = function () {
    var field = document.querySelector("#jform_number");
    field.addEventListener('keyup', upper, false);
    setImg();
};
function setImg() {
    var field = document.querySelector('#jform_scheme');
    var path = field.options[field.selectedIndex].value;
    field = document.querySelector('#jform_contractID');
    var contract = field.options[field.selectedIndex].value;
    if (path !== -1 && path !== '' && path != null)
    {
        document.querySelector("#scheme > a > img").src = '/images/contracts/' + contract + '/' + path;
        document.querySelector("#scheme > a").href = '/images/contracts/' + contract + '/' + path;
    }
}
function upper() {
    var field = document.querySelector("#jform_number");
    field.value = field.value.toUpperCase();
}