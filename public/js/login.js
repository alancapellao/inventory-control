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

        var email = $("#login-form #email-login").val();
        var password = $("#login-form #password-login").val();

        if (email.trim() == "" || password.trim() == "") {
            alert("Fill in the fields.");
        } else {
            $.ajax({
                url: '/login',
                method: 'POST',
                data: {
                    email,
                    password
                },
                success: function (data) {
                    if (data['error']) {
                        alert(data['message']);
                    } else {
                        alert(data['message']);
                        window.location.href = '/index';
                    }
                }
            });
        }
    });

    //Requisição AJAX para registro no banco de dados via POST
    $("#register-form #register").on("click", function (e) {
        e.preventDefault();

        var name = $("#register-form #name-register").val();
        var email = $("#register-form #email-register").val();
        var password = $("#register-form #password-register").val();

        if (name.trim() == "" || email.trim() == "") {
            alert("Fill in the fields.");
        } else {
            if (password.length !== 8) {
                alert("Password must contain 8 characters.");
            } else {
                $.ajax({
                    url: '/register',
                    method: 'POST',
                    data: {
                        name,
                        email,
                        password
                    },
                    success: function (data) {
                        if (data['error']) {
                            alert(data['message']);
                        } else {
                            alert(data['message']);
                        }
                    }
                });
            }
        }
    });
});
