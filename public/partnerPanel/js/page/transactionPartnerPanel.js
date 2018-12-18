"use strict";

$(document).ready(function () {

    var oTable = $('.datatable').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "searching": false,
        "sAjaxSource": transactionRoute,
        "fnServerParams": function (aoData) {
            aoData.push({name: "user_id", value: $('select[name="user_id"]').val()});
            aoData.push({name: "category_id", value: $('select[name="category_id"]').val()});
            aoData.push({name: "type_id", value: $('select[name="type_id"]').val()});
        }
    });

    $('.selectpicker').change(function () {
        oTable.fnDraw();
    });
});