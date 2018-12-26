function eventToggleCheckbox() {

    $('input:checkbox').change(function () {
        let input = $(this);
        let filed = input.attr('data-input');
        let checked = input.attr('checked');

        if (checked === undefined) {
            input.attr('checked', true);

            $(`input[name='${filed}']`).attr('disabled', false);
            $(`select[name='${filed}']`).attr('disabled', false);
        } else {
            input.attr('checked', false);

            $(`input[name='${filed}']`).attr('disabled', true);
            $(`select[name='${filed}']`).attr('disabled', true);
        }
    });
}

(function () {

})();


$(document).ready(function () {
    eventToggleCheckbox();
});

