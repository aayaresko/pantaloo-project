"use strict";

let globalTable;
let optionsDefault = {};
let options = JSON.parse(JSON.stringify(optionsDefault));


function initDataTable() {
    //destroy
    $('#tableOrder').DataTable().destroy();
    //draw new
    options['_token'] = getToken();
    let table = $('#tableOrder').DataTable({
        "order": [[0, "asc"]],
        "columnDefs": [
            {"orderable": false, "targets": 9},
            {"orderable": false, "targets": 3},
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/admin/integratedGame',
            "dataType": "json",
            "type": "GET",
            "data": options,
            "error": function (xhr, error, thrown) {
                if (statusError === 0) {
                    statusError++;
                    //table.ajax.reload()
                }
            }
        },
        "columns": [
            {"data": "id"},
            {"data": "name"},
            {"data": "provider"},
            {"data": "type"},
            {"data": "category"},
            {"data": "image"},
            {"data": "rating"},
            {"data": "active"},
            {"data": "mobile"},
            {"data": "edit"},
        ],
    });
    globalTable = table;
}

$(function () {
    initDataTable();
});


$('.selectType').change(function () {
    options.type_id = $('select[name="type_id"]').val();
    initDataTable();
});

$('.selectCategory').change(function () {
    options.category_id = $('select[name="category_id"]').val();
    initDataTable();
});


$('.selectMobile').change(function () {
    options.mobile = $('select[name="mobile"]').val();
    initDataTable();
});

$('.selectActive').change(function () {
    options.active = $('select[name="active"]').val();
    initDataTable();
});

$(document).ready(function () {
    //something
});

