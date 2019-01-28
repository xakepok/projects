'use strict';
window.onload = function () {

    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', jQuery(this).attr('href'));
        console.log(jQuery(this).attr('href'));
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        jQuery('[href="' + lastTab + '"]').tab('show');
    }
};

function convert() {
    var field = document.querySelector('#jform_price_rub');
    var rub = parseFloat(field.value);
    fetch('https://www.cbr-xml-daily.ru/daily_json.js')
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var field_usd = document.querySelector('#jform_price_usd');
            var field_eur = document.querySelector('#jform_price_eur');
            field_usd.value = parseFloat(rub/text.Valute.USD.Value).toFixed(2);
            field_eur.value = parseFloat(rub/text.Valute.EUR.Value).toFixed(2);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}