<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .bg-image {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
    </style>
</head>
<body class="bg-image">
    <div class="max-w-md mx-auto p-8 glassmorphic">
        <h1 class="text-3xl text-center text-slate-900 mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-slate-900 text-sm mb-2">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 text-slate-900 border border-slate-900 rounded-lg" placeholder="Enter your username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                <div id="username-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-slate-900 text-sm mb-2">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 text-slate-900 border border-slate-900 rounded-lg" placeholder="Enter your password">
                <div id="password-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            <p class="text-center text-slate-900 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const response = await fetch('../backend/auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                document.getElementById('username-error').innerHTML = data.username_error ? data.username_error : '';
                document.getElementById('password-error').innerHTML = data.password_error ? data.password_error : '';
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It includes a form for username and password input, with standard HTML input pattern validators to support Arabic and Latin characters. The form is submitted using AJAX with the Fetch API, and the response or error is handled dynamically. The code also includes a direct link to the register page.