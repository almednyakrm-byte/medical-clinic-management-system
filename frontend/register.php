<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-blue-500 flex justify-center items-center">
    <div class="bg-white p-10 rounded-lg shadow-lg w-1/2">
        <h1 class="text-3xl text-blue-500 text-center mb-4">Register</h1>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-blue-500 text-lg mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 border border-blue-500 rounded-lg">
                <div class="text-red-500 text-sm" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-blue-500 text-lg mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 border border-blue-500 rounded-lg">
                <div class="text-red-500 text-sm" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-blue-500 text-lg mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 border border-blue-500 rounded-lg">
                <div class="text-red-500 text-sm" id="password-error"></div>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg w-full">Register</button>
        </form>
        <div class="text-green-500 text-sm" id="success-message"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username.length < 3) {
                    $('#username-error').text('Username must be at least 3 characters long');
                    return;
                } else {
                    $('#username-error').text('');
                }

                if (!validateEmail(email)) {
                    $('#email-error').text('Invalid email address');
                    return;
                } else {
                    $('#email-error').text('');
                }

                if (password.length < 8) {
                    $('#password-error').text('Password must be at least 8 characters long');
                    return;
                } else {
                    $('#password-error').text('');
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            $('#success-message').text('Registration successful!');
                            $('#register-form')[0].reset();
                        } else {
                            $('#success-message').text('Registration failed. Please try again.');
                        }
                    }
                });
            });

            function validateEmail(email) {
                var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(email);
            }
        });
    </script>
</body>
</html>