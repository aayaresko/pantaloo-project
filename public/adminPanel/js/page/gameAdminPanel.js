"use strict";

function checkFile(e) {
    let txt;
    console.log(e);
    var file_list = e.target.files;
    var maxSize = 1000000;
    for (var i = 0, file; file = file_list[i]; i++) {
        var sFileName = file.name;
        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
        var iFileSize = file.size;
        var iConvert = (file.size / maxSize).toFixed(2);

        if (!(sFileExtension === "jpg" || sFileExtension === "jpeg" ||
            sFileExtension === "png") || iFileSize > 10000000) {
            txt = "File type : " + sFileExtension + "\n\n";
            txt += "Size: " + iConvert + " MB \n\n";
            txt += `Please make sure your file is in jpg, jpeg, png or doc format and less than ${maxSize / 1000000} MB.\n\n`;
            //reset
            document.getElementById("laodImage").value = "";
            alert(txt);
        }
    }
}

function laodImage() {
    $('#laodImage').change(checkFile);
}

$(function () {
    laodImage();
});

$(document).ready(function () {
    //something
});

