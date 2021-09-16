const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

$('#loginBtn').on('click', function() {
    $.post(
        'src/php/_ajax/ajax.login.php',
        {
            data: {
                user: $('#inputUser').val().trim(),
                password: $('#inputPassword').val()
            },
        },
        function(rtn) {
            try {
                let obj = JSON.parse(rtn);
                if (obj.user) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Benutzer gefunden!'
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Es konnte kein Benutzer gefunden werden!'
                    })
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
});