$('#uploadForm').submit(function (event) {
    event.preventDefault();
});

$('input#inputUpload').on('change', function (e) {
    //get the file name
    let fileName = e.target.files[0].name;
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});

$('button#btnUpload').on('click', function () {

	// Get the selected file(s) and store them in the files variable --- [0].files; because we just want to have one file
    let fd = new FormData();
    let files = $('#inputUpload')[0].files;

	// Add the file to the form data variable
    if (files.length > 0) {
        fd.append('file', files[0]);
    }

	// Ajax POST request
    $.ajax({
        type: 'POST',
        url: 'php/_ajax/csvUploadSample.php',
        contentType: false,
        processData: false,
        data: fd,
        success: function (rtn) {
			// Parse the response JSON from php to an object
            let obj = JSON.parse(rtn);
			// Upload Successful
            if (obj.status == 'success') {
                Toast.fire({
                    icon: 'success',
                    title: 'Die Datei wurde erfolgreich hochgeladen'
                })
            } else {
                if (obj.status == "type_error") {
					// File is not of the correct type
                    Toast.fire({
                        icon: 'error',
                        title: 'Die Datei hat nicht den richtigen Dateityp!'
                    })
                } else {
					// File upload error
                    Toast.fire({
                        icon: 'error',
                        title: 'Die Datei konnte nicht hochgeladen werden!'
                    })
                }
            }

			// Clear the upload file input
            $('#inputUpload').val('');
        }
    });
});