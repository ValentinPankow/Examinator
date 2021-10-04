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
                        title: $('.userFound').html()
                    })
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: $('.userNotFound').html()
                    })
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
});