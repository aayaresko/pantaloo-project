"use strict";

function getToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function handleImage(img) {
    $(img).attr("src", dummy);
}

function checkFile(e) {
    let txt;
    var file_list = e.target.files;
    var maxSize = 1000000;
    for (var i = 0, file; file = file_list[i]; i++) {
        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / maxSize).toFixed(2);

        if (!(sFileExtension === "jpg" || sFileExtension === "jpeg" ||
            sFileExtension === "png") || iFileSize > maxSize) {
            txt = "File type : " + sFileExtension + "\n\n";
            txt += "Size: " + iConvert + " MB \n\n";
            txt += `Please make sure your file is in jpg, jpeg, png or doc format and less than ${maxSize / 1000000} MB.\n\n`;
            //reset
            document.getElementById("laodImage").value = "";
            alert(txt);
        }
    }
}