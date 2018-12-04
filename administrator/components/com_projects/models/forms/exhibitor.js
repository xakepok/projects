'use strict';
window.onload = function () {
    var field1 = document.getElementById("jform_title_ru_short");
    field1.addEventListener('keyup', checkExp, false);
    var field2 = document.getElementById("jform_title_ru_full");
    field2.addEventListener('keyup', checkExp, false);
    var field3 = document.getElementById("jform_title_en");
    field3.addEventListener('keyup', checkExp, false);
    var inn = document.getElementById("jform_inn");
    inn.addEventListener('keyup', checkExp, false);
    jQuery(".phone_mask").mask("+9 (999) 999-99-99");
};

function removePerson(personID) {
    fetch('/administrator/index.php?option=com_projects&task=exhibitors.removePerson&id=' + personID)
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
                document.querySelector('#row_person_' + personID).remove();
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

function checkURL (abc) {
    var string = abc.value;
    if (!~string.indexOf("http")) {
        string = "http://" + string;
    }
    abc.value = string;
    return abc
}

function checkExp() {
    var title = '';
    var t1 = document.getElementById("jform_title_ru_short").value;
    var t2 = document.getElementById("jform_title_ru_full").value;
    var t3 = document.getElementById("jform_title_en").value;
    var inn = document.getElementById("jform_inn").value;
    if (t1.length > 0) title = t1;
    if (t2.length > 0) title = t2;
    if (t3.length > 0) title = t3;
    if (title === '') return;
    var url = '/administrator/index.php?option=com_projects&view=exhibitors&text=' + title + '&format=raw';
    if (inn !== undefined) url += '&inn=' + inn;

    fetch(url)
        .then(function (response) {
            return response.json();
        })
        .then(function (text) {
            var div = document.querySelector('#similar');
            if (text.data.length > 0)
            {
                similarClear();
                div.style.display = 'block';
                text.data.forEach(similar);
            }
            else
            {
                div.style.display = 'none';
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

function similarClear()
{
    var ul = document.querySelector('#similar > ul');
    while (ul.firstChild)
    {
        ul.removeChild(ul.firstChild);
    }
}

function similar(element, index, arr)
{
    var link = document.createElement("a");
    link.href = '/administrator/index.php?option=com_projects&view=exhibitor&layout=edit&id=' + element.id;
    link.target = '_blank';
    link.innerText = element.title;
    var ul = document.querySelector('#similar > ul');
    var li = document.createElement("li");
    li.appendChild(link);
    ul.appendChild(li);
}