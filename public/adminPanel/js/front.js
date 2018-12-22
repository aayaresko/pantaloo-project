$( document ).ready(function() {

    $('body').on('click', '.toggle.btn', function() {
        var $self = $(this),
            array = $self.find('.toggle-controll').data('input').split(',')

        if ( $self.hasClass('btn-default') == false ) {
            // $('.form-control').attr('disabled', 'disabled')
            console.log(array)
            for (var i = 0; i < array.length; i++) {
                // console.log( $.trim( array[i] ) )
                $('.table').find('input[name="' + $.trim( array[i] ) + '"]').attr('disabled', 'disabled')
                $('.table').find('select[name="' + $.trim( array[i] ) + '"]').attr('disabled', 'disabled')
            }
        } else {
            $('.form-control').removeAttr('disabled')
        }

    })

});