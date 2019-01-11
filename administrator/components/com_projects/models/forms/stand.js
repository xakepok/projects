'use strict';
window.onload = function () {
    var field = document.querySelector("#jform_number");
    field.addEventListener('keyup', upper, false);
    setImg();
    var url_string = window.location.href; // www.test.com?filename=test
    var url = new URL(url_string);
    var id = url.searchParams.get("id");
    if (id === undefined) setSquare();
    copyNum();
};
function setSquare() {
    var field = document.getElementById("jform_catalogID");
    var sq = field.options[field.selectedIndex].getAttribute('data-square');
    document.querySelector("#jform_sq").value = sq;
}
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
function copyNum() {
    var field = document.querySelector('#jform_catalogID').options;
    var num = document.querySelector("#jform_number").value;
    var index = 0;
    for (var i=0; i < field.length; i++)
    {
        var attrNum = field[i].getAttribute('data-num');
        if (attrNum === num) index = i;
    }
    document.querySelector('#jform_catalogID').selectedIndex = index;
    console.log('Transfer complete. Index: ', index);
}