"use strict";

$(document).ready(function () {

    let options = {};
    options.push({name: "user_id", value: $('select[name="user_id"]').val()});
    options.push({name: "category_id", value: $('select[name="category_id"]').val()});
    options.push({name: "type_id", value: $('select[name="type_id"]').val()});

    let table = $('.datatable').DataTable({
        "order": [[0, "asc"]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": transactionRoute,
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
            {"data": "created_at"},
            {"data": "description"},
            {"data": "sum"},
            {"data": "bonus_sum"},
        ]
    });

    $('.selectpicker').change(function () {
        table.fnDraw();
    });
});