function eventToggleCheckbox() {
    $('input:checkbox').change(function () {
        let input = $(this);
        let filed = input.attr( "data-input" );
        $(`input[name='${filed}']`).attr('disabled', true);
        $(`select[name='${filed}']`).attr('disabled', true);
    });
}

(function () {

})();


$(document).ready(function () {
    eventToggleCheckbox();

    // $('body').on('click', '.toggle.btn', function() {
    //     var $self = $(this),
    //         array = $self.find('.toggle-controll').data('input').split(',')
    //
    //     if ( $self.hasClass('btn-default') == false ) {
    //         // $('.form-control').attr('disabled', 'disabled')
    //         console.log(array)
    //         for (var i = 0; i < array.length; i++) {
    //             // console.log( $.trim( array[i] ) )
    //             $('.table').find('input[name="' + $.trim( array[i] ) + '"]').attr('disabled', 'disabled')
    //             $('.table').find('select[name="' + $.trim( array[i] ) + '"]').attr('disabled', 'disabled')
    //         }
    //     } else {
    //         $('.form-control').removeAttr('disabled')
    //     }
    //
    // })


    // // $('body').on('click', '.toggle.btn', function() {
    //     let self = $(this);
    //     let input = $(self.children()[0]);
    //     let checked = input.attr("checked");
    //     console.log(checked);
    //     if (checked === undefined) {
    //         console.log(1);
    //         input.attr('checked', true)
    //     } else {
    //         console.log(2);
    //         input.removeAttr( "checked" )
    //         $('.toggle.btn').bootstrapToggle('off')
    // //     }
    // // });

    // $('input:checkbox').change(function() {
    //     let input = $(this);
    //     let checked = input.attr('checked');
    //     console.log(checked);
    //     if (checked === undefined) {
    //         input.attr('checked', true)
    //     } else {
    //         input.attr('checked', false)
    //     }
    // });


});

