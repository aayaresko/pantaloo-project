"use strict";

$(document).ready(function () {

    let options = {};
    function setOptions() {
        options.user_id = $('select[name="user_id"]').val();
        options.category_id = $('select[name="category_id"]').val();
        options.type_id = $('select[name="type_id"]').val();
    }

    function initDataTable() {
        $('#tableOrder').DataTable().destroy();
        let table = $('.datatable').DataTable({
            "order": [[0, "asc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": transactionRoute,
                "dataType": "json",
                "type": "GET",
                "data": options,
            },
            "columns": [
                {"data": "id"},
                {"data": "created_at"},
                {"data": "description"},
                {"data": "sum"},
                {"data": "bonus_sum"},
            ]
        });
    }

    $(function () {
        initDataTable();
    });

    $('.selectpicker').change(function () {
        setOptions();
        initDataTable();
    });
});