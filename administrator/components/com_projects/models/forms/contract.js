'use strict';
window.onload = function () {
    setNumber();
};

function removeStand(standID) {
    fetch('/administrator/index.php?option=com_projects&task=contracts.removeStand&id=' + standID)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            if (text.data.result !== 1)
            {
                alert(text.data.message);
            }
            else
            {
                document.querySelector('#row_stand_' + standID).remove();
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

function setNumber() {
    var radios = document.forms["adminForm"].elements["jform[isCoExp]"];
    var val = document.querySelector('input[name="jform[isCoExp]"]:checked').value;
    console.log(val);
    if (val !== '1') lockParent(); else unlockParent();
    for(var i = 0, max = radios.length; i < max; i++) {
        radios[i].onclick = function() {
            if (this.value !== '1') lockParent(); else unlockParent();
        }
    }
}

function unlockParent() {
    jQuery('#jform_parentID').prop('disabled', false).trigger("liszt:updated");
}
function lockParent() {
    jQuery('#jform_parentID').val('').prop('disabled', true).trigger("liszt:updated");
}
function closeTask(id) {
    var result = document.querySelector("input[name='result_" + id + "']").value;
    fetch('/administrator/index.php?option=com_projects&task=todos.close&id=' + id + '&result=' + result)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var td = document.querySelector('.resultTodo_' + id);
            td.innerText = text.data.dat + ": " + text.data.user+ " " + result;
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}
function getSum2(id, currency)
{
    var tr = document.querySelector('#sum_'+id);
    var newtr = document.querySelector('#summary_'+id);
    if (newtr === null) addSum(id);
    var span = document.querySelector('#sum_'+id);
    var spanS = document.querySelector('#sumS_'+id);
    var sum = getSum(id);
    if (sum !== 0 && sum !== null && sum !== undefined && !isNaN(sum) && sum !== '')
    {
        span.textContent = sum;
        document.querySelector("#currency_"+id).textContent = currency;
        spanS.textContent = sum;
        document.querySelector("#currencyS_"+id).textContent = currency;
    }
    else
    {
        span.textContent = 0;
        document.querySelector("#currency_"+id).textContent = currency;
        removeTr(id);
    }
    subSum();
    var itg = calculate();
    document.querySelector("#sum_amount").textContent = itg.toLocaleString('ru') + ' ' + currency;
    document.querySelector("#sum_amountS").textContent = itg.toLocaleString('ru');
}
function removeTr(id)
{
    try {
        document.querySelector("#summary_" + id).remove();
    }
    catch (e) {
        console.log("Элемента с id summary_" + id + " не существует");
    }
}
function addSum(id) {
    var tbody = document.querySelector(".sumbody");
    var tr = document.createElement('tr');
    var td = document.createElement('td');
    tr.id = 'summary_'+id;
    var label = document.querySelector('#label_'+id);
    var title = document.createTextNode(label.textContent);
    var section = document.createTextNode(label.dataset.section);
    td.appendChild(title);
    tr.appendChild(td);
    td = document.createElement('td');
    td.appendChild(section);
    tr.appendChild(td);
    td = document.createElement('td');
    var span = document.createElement('span');
    span.id='sumS_'+id;
    span.className='amountsS';
    td.appendChild(span);
    span = document.createElement('span');
    span.id='currencyS_'+id;
    td.appendChild(document.createTextNode(' '));
    td.appendChild(span);
    tr.appendChild(td);
    tbody.appendChild(tr);
}
function getSum(id) {
    var b = 0;
    var c = 0;
    var field = parseFloat(document.querySelector("#price_" + id).dataset.cost);
    var value = parseInt(document.querySelector("#price_" + id).value);
    var a = field * value;
    field = document.querySelector("#value2_" + id);
    if (field !== null)
    {
        a *= parseInt(field.value);
    }
    field = document.querySelector("#markup_" + id);
    if (field !== null)
    {
        b = Math.round(a * parseFloat((100 + parseInt(field.options[field.selectedIndex].value)) / 100) - a);
    }
    field = document.querySelector("#factor_" + id);
    if (field !== null)
    {
        c = Math.round(a * parseFloat(1 - (100 - parseInt(field.value)) / 100));
    }
    console.log(a,b,c);
    return Math.round(a + b - c);
}
function calculate() {
    var amounts = document.querySelectorAll("span[id^='subsum_']");
    var sum = 0;
    for (var i = 0; i < amounts.length; i++)
    {
        if (amounts[i].textContent !== "0") sum = sum + parseFloat(amounts[i].textContent);
    }
    return sum;
}
function subSum() {
    var trs = document.querySelectorAll("tr[class^='section_']");
    var sum = [];
    for (var i = 0; i < trs.length; i++)
    {
        var id = document.querySelector("#"+trs[i].id).dataset.section;
        if (sum[id] === undefined) sum[id] = 0;
        document.querySelector("#subsum_"+id).textContent = '0';
        var sps = document.querySelectorAll("#"+trs[i].id+" > td > span[id^='sum_']");
        for (var j = 0; j < sps.length; j++)
        {
            sum[id] = sum[id] + parseFloat(sps[j].textContent);
            document.querySelector("#subsum_"+id).textContent = sum[id];
        }
    }

}