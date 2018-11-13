'use strict';
window.onload = function () {
    setNumber();
};

function setNumber() {
    var status = document.getElementById("jform_status");
    var tip = status.options[status.selectedIndex].value;
    if (tip === '1') loadNumber();
    if (tip === '5' || tip === '6') unlockParent(); else lockParent();
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

function unlockParent() {
    jQuery('#jform_parentID').prop('disabled', false).trigger("liszt:updated");
}
function lockParent() {
    jQuery('#jform_parentID').val('').prop('disabled', true).trigger("liszt:updated");
}