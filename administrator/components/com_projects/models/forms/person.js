'use strict';
window.onload = function () {
    setMask('mask_jform[phone_work]', 'jform_phone_work');
    setMask('mask_jform[phone_mobile]', 'jform_phone_mobile');
};
function setMask(id, field) {
    var mask = "+9 (999) 999-99-99? доб. 999999";
    var object = document.getElementById(id);
    field = jQuery("#" + field);
    if (!object.checked) field.unmask(); else field.mask(mask);
}