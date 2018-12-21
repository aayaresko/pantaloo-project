"use strict";

function getToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function handleImage(img) {
    $(img).attr("src", dummy);
}

function checkFile(e) {
    console.log(maxSizeImage);
    let txt;
    var file_list = e.target.files;
    var maxSize = maxSizeImage * 1000;
    for (var i = 0, file; file = file_list[i]; i++) {
        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / maxSize).toFixed(2);

        if (!typesImage.includes(sFileExtension) || iFileSize > maxSize) {
            txt = "File type : " + sFileExtension + "\n\n";
            txt += "Size: " + iConvert + " MB \n\n";
            txt += `Please make sure your file is in ${typesImage.join()}. Format and less than ${maxSize / 1000000} MB.\n\n`;
            //reset
            document.getElementById("laodImage").value = "";
            alert(txt);
        }
    }
}


$( document ).ready(function() {

    $('body').on('click', '.toggle.btn', function() {
        var $self = $(this),
            array = $self.find('.toggle-controll').data('input').split(',')

        if ( $self.hasClass('btn-default') == false ) {
            // alert( 'Have Class' )
            // $('.form-control').attr('disabled', 'disabled')
            console.log(array)
            for (var i = 0; i < array.length; i++) {
                console.log( $.trim( array[i] ) )
                $('.table').find('input[name="' + array[i] + '"]').attr('disabled', 'disabled')
            }
        } else {
            $('.form-control').removeAttr('disabled')
        }
    })

});