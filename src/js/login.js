const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: "swal2-popup-custom"
    },
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
                    triggerResponseMsg('success', $('.userFound').html());
                } else {
                    triggerResponseMsg('error', $('.userNotFound').html());
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
});