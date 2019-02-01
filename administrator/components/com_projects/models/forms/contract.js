'use strict';
function scrolify(tblAsJQueryObject, height) {
    var oTbl = tblAsJQueryObject;

    // for very large tables you can remove the four lines below
    // and wrap the table with <div> in the mark-up and assign
    // height and overflow property
    var oTblDiv = jQuery("<div/>");
    oTblDiv.css('height', height);
    oTblDiv.css('overflow', 'scroll');
    oTbl.wrap(oTblDiv);

    // save original width
    oTbl.attr("data-item-original-width", oTbl.width());
    oTbl.find('thead tr th').each(function() {
        jQuery(this).attr("data-item-original-width", jQuery(this).width()).css('text-align', 'left');
    });
    oTbl.find('tbody tr:eq(0) th').each(function() {
        jQuery(this).attr("data-item-original-width", jQuery(this).width()).css('text-align', 'left');
    });


    // clone the original table
    var newTbl = oTbl.clone();

    // remove table header from original table
    oTbl.find('thead tr').remove();
    // remove table body from new table
    newTbl.find('tbody tr').remove();

    oTbl.parent().parent().prepend(newTbl);
    newTbl.wrap("<div/>");

    // replace ORIGINAL COLUMN width
    newTbl.width('100%');
    newTbl.find('thead tr td').each(function() {
        jQuery(this).width(jQuery(this).attr("data-item-original-width"));
    });
    oTbl.width('100%');
    oTbl.find('tbody tr:eq(0) td').each(function() {
        jQuery(this).width(jQuery(this).attr("data-item-original-width"));
    });
}

window.onload = function () {

    scrolify(jQuery('#tblNeedsScrolling'), 640); // 160 is height

    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', jQuery(this).attr('href'));
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        jQuery('[href="' + lastTab + '"]').tab('show');
    }
    setNumber();
};
function setNull(id, currency) {
    document.querySelector("#price_" + id).value = 0;
    getSum2(id, currency);
}
function removeTodo(id) {
    if (id < 1) return false;
    fetch('/administrator/index.php?option=com_projects&task=todos.removeTodo&id=' + id)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            document.querySelector('#rmTodo_' + id).remove();
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}
function removeStand(standID) {
    if (!confirm('Удалить стенд? Проверьте введённые данные, после удаления стенда страница перезагрузится. Нажмите ДА или ОК, если вы сохранили данные или НЕТ или ОТМЕНА, если вы не сохранили данные')) return;
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
                location.reload();
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

function setNumber() {
    var radios = document.forms["adminForm"].elements["jform[isCoExp]"];
    var val = document.querySelector('input[name="jform[isCoExp]"]:checked').value;
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
    /*if (newtr === null)*/ addSum(id);
    var span = document.querySelector('#sum_'+id);
    var spanS = document.querySelector('#sumS_'+id);
    var cnt = document.querySelector("#price_"+id).value;
    var factor = document.querySelector("#factor_" + id);
    if (factor !== null)
    {
        document.querySelector("#sum_factor_"+id).textContent = factor.value;
    }
    var markup = document.querySelector("#markup_" + id);
    if (markup !== null)
    {
        var mk = markup.options[markup.selectedIndex].value;
        document.querySelector("#sum_markup_"+id).textContent = mk;
    }
    var sum = getSum(id);
    if (sum !== 0 && sum !== null && sum !== undefined && !isNaN(sum) && sum !== '')
    {
        span.textContent = sum;
        var spanV = document.querySelector('#sumV_'+id);
        spanV.textContent = parseFloat(span.textContent).toLocaleString('ru', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.querySelector("#currency_"+id).textContent = currency;
        spanS.textContent = sum;
        var spanSV = document.querySelector('#sumSV_'+id);
        spanSV.textContent = parseFloat(spanS.textContent).toLocaleString('ru', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.querySelector("#currencyS_"+id).textContent = currency;
        document.querySelector("#sum_cnt_"+id).textContent = cnt;
    }
    else
    {
        span.textContent = 0;
        document.querySelector("#currency_"+id).textContent = currency;
        removeTr(id);
    }
    //subSum();
    subSumApp();
    var itg = calculate();
    document.querySelector("#sum_amountS").textContent = itg;
    document.querySelector("#sum_amountSV").textContent = parseFloat(itg).toLocaleString('ru', {minimumFractionDigits: 2, maximumFractionDigits: 2});;
}
function removeTr(id)
{
    try {
        document.querySelector("#summary_" + id).classList.add('hidden');
    }
    catch (e) {
        console.log("Элемента с id summary_" + id + " не существует");
    }
}
function addSum(id) {
    document.querySelector("#summary_" + id).classList.remove('hidden');
/*
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
*/
}
function getSum(id) {
    var b = 0;
    var c = 0;
    var field = parseFloat(document.querySelector("#price_" + id).dataset.cost);
    var value = parseFloat(document.querySelector("#price_" + id).value);
    var a = field * value;
    field = document.querySelector("#value2_" + id);
    if (field !== null)
    {
        a *= parseFloat(field.value);
    }
    field = document.querySelector("#markup_" + id);
    if (field !== null)
    {
        b = parseFloat(a * parseFloat((100 + parseInt(field.options[field.selectedIndex].value)) / 100) - a);
    }
    field = document.querySelector("#factor_" + id);
    if (field !== null)
    {
        c = parseFloat(a * parseFloat(1 - (100 - parseInt(field.value)) / 100));
    }
    return parseFloat(a + b - c).toFixed(2);
}
function calculate() {
    var amounts = document.querySelectorAll("span[id^='subsumapp_']");
    var sum = 0;
    for (var i = 0; i < amounts.length; i++)
    {
        if (amounts[i].textContent !== "0") sum = sum + parseFloat(amounts[i].textContent);
    }
    return parseFloat(sum).toFixed(2);
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
function subSumApp() {
    var trs = document.querySelectorAll("tr[class^='app_']");
    var sum = [];
    for (var i = 0; i < trs.length; i++)
    {
        var id = document.querySelector("#"+trs[i].id).dataset.app;
        if (sum[id] === undefined) sum[id] = 0;
        if (trs[i].classList.contains('hidden')) continue;
        var sps = document.querySelectorAll("#"+trs[i].id+" > td > span[id^='sumS_']");
        for (var j = 0; j < sps.length; j++)
        {
            sum[id] = sum[id] + parseFloat(sps[j].textContent);
            document.querySelector("#subsumapp_"+id).textContent = sum[id];
            document.querySelector("#subsumappV_"+id).textContent = parseFloat(sum[id]).toLocaleString('ru', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }
}
function showCard(id) {
    fetch('/administrator/index.php?option=com_projects&view=exhibitor&layout=edit&id=' + id + '&format=raw')
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var title = document.querySelector('#modalExpCardTitle');
            title.textContent = text.data.info.title;
            var myNode = document.querySelector('#cardValues');
            while (myNode.firstChild) {
                myNode.removeChild(myNode.firstChild);
            }
            if (text.data.info.director_name != null) {
                var director_name = document.createElement('p');
                director_name.textContent = 'Руководитель: ' + text.data.info.director_name;
                myNode.appendChild(director_name);
            }
            if (text.data.info.director_post != null) {
                var director_post = document.createElement('p');
                director_post.textContent = 'Должность: ' + text.data.info.director_post;
                myNode.appendChild(director_post);
            }
            if (text.data.info.phone_1 != null) {
                var phone1 = document.createElement('p');
                phone1.textContent = 'Телефон 1: ' + text.data.info.phone_1;
                if (text.data.info.phone_1_comment != null) {
                    phone1.textContent += ' (' + text.data.info.phone_1_comment + ')';
                }
                myNode.appendChild(phone1);
            }
            if (text.data.info.phone_2 != null) {
                var phone2 = document.createElement('p');
                phone2.textContent = 'Телефон 2: ' + text.data.info.phone_2;
                if (text.data.info.phone_2_comment != null) {
                    phone2.textContent += ' (' + text.data.info.phone_2_comment + ')';
                }
                myNode.appendChild(phone2);
            }
            if (text.data.info.email != null) {
                var email = document.createElement('p');
                var url = document.createElement('a');
                url.href = 'mailto:' + text.data.info.email;
                url.innerText = text.data.info.email;
                email.appendChild(url);
                myNode.appendChild(document.createTextNode('Электронная почта: '));
                myNode.appendChild(url);
            }
            if (text.data.info.site != null) {
                var site = document.createElement('p');
                var url1 = document.createElement('a');
                url1.href = text.data.info.site;
                url1.innerText = text.data.info.site;
                site.appendChild(url1);
                myNode.appendChild(document.createTextNode('Веб-сайт: '));
                myNode.appendChild(url1);
            }
            myNode = document.querySelector('#contactsValues');
            while (myNode.firstChild) {
                myNode.removeChild(myNode.firstChild);
            }
            for (var i = 0; i < text.data.persons.length; i++)
            {
                if (text.data.persons[i].main === '1')
                {
                    if (text.data.persons[i].fio != null) {
                        var fio = document.createElement('p');
                        fio.textContent = 'ФИО: ' + text.data.persons[i].fio;
                        myNode.appendChild(fio);
                    }
                    if (text.data.persons[i].post != null) {
                        var post = document.createElement('p');
                        post.textContent = 'Должность: ' + text.data.persons[i].post;
                        myNode.appendChild(post);
                    }
                    if (text.data.persons[i].phone_work != null) {
                        var phone_work = document.createElement('p');
                        phone_work.textContent = 'Рабочий тел.: ' + text.data.persons[i].phone_work;
                        myNode.appendChild(phone_work);
                    }
                    if (text.data.persons[i].phone_mobile != null) {
                        var phone_mobile = document.createElement('p');
                        phone_mobile.textContent = 'Мобильный тел.: ' + text.data.persons[i].phone_mobile;
                        myNode.appendChild(phone_mobile);
                    }
                    if (text.data.persons[i].email_clean != null) {
                        var email = document.createElement('p');
                        var url = document.createElement('a');
                        url.href = 'mailto:' + text.data.persons[i].email_clean;
                        url.innerText = text.data.persons[i].email_clean;
                        email.appendChild(url);
                        myNode.appendChild(document.createTextNode('Электронная почта: '));
                        myNode.appendChild(url);
                    }
                    myNode.appendChild(document.createElement('hr'));
                }
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}