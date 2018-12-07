function getToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function handleImage(img) {
    $(img).attr("src", dummy);
}

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

$(document).ready(function () {
    //something
});

