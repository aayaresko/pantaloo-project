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
            "searching": false,
            "columnDefs": [
                {
                    "targets": 3,
                    "render": function (data, type, row) {
                        return viewAmount(data);
                    },
                },
                {
                    "targets": 4,
                    "render": function (data, type, row) {
                        return viewAmount(data);
                    },
                }
            ],
            "ajax": {
                "url": transactionRoute,
                "dataType": "json",
                "type": "GET",
                "data": options,
            },
            "columns": [
                {"data": "email"},
                {"data": "created_at"},
                {"data": "description"},
                {"data": "sum"},
                {"data": "bonus_sum"},
            ]
        });

        function viewAmount(data) {
            let number = Number(data);
            if (number > 0) {
                let value = `<span class="label label-success">number</span>`;
            } else {
                let value = `<span class="label label-warning">number</span>`;
            }
        }
    }

    $(function () {
        initDataTable();
    });

    $('.selectpicker').change(function () {
        setOptions();
        initDataTable();
    });
});