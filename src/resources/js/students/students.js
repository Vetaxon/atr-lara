import $ from 'jquery';
import axios from 'axios';

$(window).on('load', function () {
    $('#select-all').on('click', initSelectAll);
    $('.js-export').on('click', handleExportClick);
});

function initSelectAll(e) {
    e.preventDefault();
    const target$ = $(e.target);
    const students = $('input[name="studentId"]');
    if (!target$.prop('checked')) {
        students.prop('checked', true);
        target$.prop('checked', true);
    } else {
        students.prop('checked', false);
        target$.prop('checked', false);
    }
}

function handleExportClick(e) {
    e.preventDefault();
    const target$ = $(e.target);
    const studentsChecked = $('input:checked[name="studentId"]');
    const students = $('input[name="studentId"]');
    let ids = [];
    if (students.length !== studentsChecked.length) {
        ids = [...studentsChecked].map(student => student.value);
    }
    let href = target$.data('href') + '?' + ids.map(id => 'ids[]=' + id).join('&');
    axios.get(href)
        .then(function (response) {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', response.headers['content-filename']);
            document.body.appendChild(link);
            link.click();
        })
        .catch(function (error) {
            console.log(error);
        });
}






