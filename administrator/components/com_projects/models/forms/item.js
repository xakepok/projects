'use strict';
window.onload = function () {
    var field = document.querySelector('#jform_price_rub');
    field.addEventListener('keyup', convert, false);
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