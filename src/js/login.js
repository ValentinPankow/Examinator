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
   login();
});

$('#inputPassword').keypress(function(e) {
    if(e.which == 13){
        login();
    }
 });

function login(){
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
                console.log(obj);
                if (obj.success) {
                    triggerResponseMsg('success', $('.loginSuccess').html());
                } else {
                    triggerResponseMsg('error', $('.loginError').html());
                }
            } catch(e) {
                console.log(e);
            }
        }
    );
}