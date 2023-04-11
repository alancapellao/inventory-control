$(function () {

    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Requisição AJAX para login
    $("#login-form #login").on("click", function (e) {
        e.preventDefault();

        var campoEmail = $("#login-form #email-login").val();
        var campoPassword = $("#login-form #password-login").val();

        if (campoEmail.trim() == "" || campoPassword.trim() == "") {
            alert("Fill in the fields.");
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
                        alert("Invalid email and/or password.");
                    } else {
                        window.location.href = '/dashboard';
                    }
                }
            });
        }
    });

    //Requisição AJAX para registro no banco de dados via POST
    $("#register-form #register").on("click", function (e) {
        e.preventDefault();

        var campoName = $("#register-form #name-register").val();
        var campoEmail = $("#register-form #email-register").val();
        var campoPassword = $("#register-form #password-register").val();

        if (campoName.trim() == "" || campoEmail.trim() == "" || campoPassword.trim() == "") {
            alert("Fill in the fields.");
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
                        alert("This user already exists.");
                    } else {
                        alert("Successfully registered.");
                    }
                }
            });
        }
    });
});
