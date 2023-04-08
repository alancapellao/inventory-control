$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#login-form #login").on("click", function (e) {
        e.preventDefault();

        var campoEmail = $("#login-form #email-login").val();
        var campoPassword = $("#login-form #password-login").val();

        if (campoEmail.trim() == "" || campoPassword.trim() == "") {
            alert("Preencha os campos.");
        } else {
            $.ajax({
                url: '/login',
                method: 'POST',
                data: {
                    email: campoEmail,
                    password: campoPassword
                },
                success: function (response) {
                    if (response['erro']) {
                        alert(response['mensagem']);
                    } else {
                        alert(response['mensagem']);
                    }
                }
            });
        }
    });

    $("#register-form #register").on("click", function (e) {
        e.preventDefault();

        var campoName = $("#register-form #name-register").val();
        var campoEmail = $("#register-form #email-register").val();
        var campoPassword = $("#register-form #password-register").val();

        if (campoName.trim() == "" || campoEmail.trim() == "" || campoPassword.trim() == "") {
            alert("Preencha os campos.");
        } else {
            $.ajax({
                url: '/register',
                method: 'POST',
                data: {
                    name: campoName,
                    email: campoEmail,
                    password: campoPassword
                },
                success: function (response) {
                    if (response['erro']) {
                        alert(response['mensagem']);
                    } else {
                        alert(response['mensagem']);
                    }
                }
            });
        }
    });
});
