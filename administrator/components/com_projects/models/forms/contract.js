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
    var status = document.getElementById("jform_status");
    var tip = status.options[status.selectedIndex].value;
    if (tip === '5' || tip === '6') unlockParent(); else lockParent();
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
    if (tr === null) addSum(id);
    var span = document.querySelector('#sum_'+id);
    var sum = getSum(id);
    if (sum !== 0 && sum !== null && sum !== undefined)
    {
        span.textContent = sum;
    }
    else
    {
        removeTr(id);
    }
    document.querySelector("#currency_"+id).textContent = currency;
    document.querySelector("#sum_amount").textContent = calculate() + ' ' + currency;
}
function removeTr(id)
{
    document.querySelector("#summary_"+id).remove();
}
function addSum(id) {
    var tbody = document.querySelector(".sumbody");
    var tr = document.createElement('tr');
    var td = document.createElement('td');
    tr.id = 'summary_'+id;
    td.width='80%';
    var label = document.querySelector('#label_'+id);
    var title = document.createTextNode(label.textContent);
    td.appendChild(title);
    tr.appendChild(td);
    td = document.createElement('td');
    var span = document.createElement('span');
    span.id='sum_'+id;
    span.className='amounts';
    td.width='20%';
    td.appendChild(span);
    span = document.createElement('span');
    span.id='currency_'+id;
    td.appendChild(document.createTextNode(' '));
    td.appendChild(span);
    tr.appendChild(td);
    tbody.appendChild(tr);
}
function getSum(id) {
    var sum = 0;
    var field = parseFloat(document.querySelector("#price_" + id).dataset.cost);
    var value = parseInt(document.querySelector("#price_" + id).value);
    sum = field * value;
    field = document.querySelector("#value2_" + id);
    if (field !== null)
    {
        sum = sum * parseInt(field.value);
    }
    field = document.querySelector("#factor_" + id);
    if (field !== null)
    {
        sum = sum * parseFloat((100 - parseInt(field.value)) / 100);
    }
    field = document.querySelector("#markup_" + id);
    if (field !== null)
    {
        sum = sum * parseFloat((100 + parseInt(field.options[field.selectedIndex].value)) / 100);
    }
    sum = Math.round(sum);
    return sum;
}
function calculate() {
    var amounts = document.querySelectorAll(".amounts");
    var sum = 0;
    for (var i = 0; i < amounts.length; i++)
    {
        if (amounts[i].textContent !== "") sum = sum + parseFloat(amounts[i].textContent);
    }
    return sum;
}