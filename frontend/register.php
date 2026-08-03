<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .btn {
            width: 100%;
            height: 40px;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #444;
        }
        .error {
            color: #f00;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Register</h2>
        </div>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div class="error" id="username-error"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
                <div class="error" id="email-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <div class="error" id="password-error"></div>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var error = false;

                if (!username.match(pattern)) {
                    $('#username-error').text('Invalid username. Only letters, numbers, and spaces are allowed.');
                    error = true;
                } else {
                    $('#username-error').text('');
                }

                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    $('#email-error').text('Invalid email address.');
                    error = true;
                } else {
                    $('#email-error').text('');
                }

                if (password.length < 8) {
                    $('#password-error').text('Password must be at least 8 characters long.');
                    error = true;
                } else {
                    $('#password-error').text('');
                }

                if (!error) {
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
                                alert('Registration successful. Please login to continue.');
                                window.location.href = 'login.php';
                            } else {
                                alert('Registration failed. Please try again.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>


Please note that this code uses the `pattern` attribute on the input fields to validate the username and email address. The `pattern` attribute is not supported in older browsers, so you may need to use a JavaScript library like jQuery to validate the input fields on the client-side.

Also, this code assumes that the `auth.php` file is located in the `backend` directory and that it returns a string indicating whether the registration was successful or not. You will need to modify the code to match your specific backend implementation.