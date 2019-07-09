//contact page
var newFileList = []
$("#contFile").click(function() { 
    $(this).val(null)
})

function resetContForm(fullReset) {
    $("#contFile").val(null)
    $('#contFilesWrap').html('')
    $('#attachTxt').show()
    if (fullReset) {
        $('#contForm')[0].reset();
        newFileList = []
    }
}

function showNoty(filesErr, otherErr) {
    if (filesErr) {
        $('.'+filesErr).addClass('showAlert')		
    }
    if (otherErr) {
        $('.otherErr .alertText').html(otherErr)
        $('.otherErr').addClass('showAlert')
    }
    setTimeout(function () {
        $(".contactError").removeClass("showAlert");
    }, 3000);
}

$("#contFile").change(function() {
    let files = this.files				
    var removeBlock = $('.addFileWrap .removeTxt').clone()

    if (files.length > 5) {
        showNoty('max_file_count')
        resetContForm()		
        return
    }

    let maxFileSize = 1048576; //1mb

    if(files.length != 0) {
        $.each(files, function(i, file) {	
            let validExt = (/\.(jpg|jpeg|png)$/i).test(file.name.replace(/ /g,''))

            if (!validExt) {
                showNoty('not_valid_ext')
                resetContForm()				
                return false;
            }				

            if (file.size > maxFileSize) {
                showNoty('max_file_size')
                resetContForm()
                return false;
            } 
            $('#attachTxt').hide()
            $('.addFileWrap').addClass('expand')
                        
            $('#contFilesWrap').append(removeBlock.clone().addClass('showRemove animated fadeIn').append('&nbsp;&nbsp;&nbsp;<span class="fileToUpload">'+file.name+'</span>'))								
        })
    } 
    
    newFileList = Array.from(files);

    $('.addFileWrap .removeTxt').on('click', function() {

        
        let fileToDelete = $(this).find($('.fileToUpload')).html();
        $(this).hide()		
        const index = newFileList.findIndex(item => item.name === fileToDelete);
        newFileList.splice(index,1);
        
        if (newFileList.length == 0) {
            resetContForm()
        }
    })
});



$('#contForm').submit(function(e) {
    e.preventDefault()
    $('#contForm .submitBtn').prop('disabled', true)
    
    var formData = new FormData(); 
    formData.append('email', $('#contEmail').val());
    formData.append('message', $('#contText').val());

    if (newFileList) {	
        newFileList.forEach(file => formData.append("files[]", file));		
    }
    
    $.ajax({
        method: 'post',
        contentType: false,   
        cache: false,             
        processData:false, 
        url: 'contact',
        data: formData
    }).done(function (response) {
        $('#contForm .submitBtn').prop('disabled', false)
        showNoty('success')
        resetContForm(1)
        
    }).fail(function(response) {
        $('#contForm .submitBtn').prop('disabled', false)
        if (response.status == 500) {
            showNoty('cantSendErr')
            console.log('cant send email');
            return;
        }

        let err = response.responseJSON.errors

        if (err.email) {
            showNoty(0, err.email)
            return;
        } else if (err.message) {
            showNoty(0, err.message)
            return;
        }

        let errorFiles = err[Object.keys(err)[0]]			
        showNoty(errorFiles)
        
        
    })	
        
})