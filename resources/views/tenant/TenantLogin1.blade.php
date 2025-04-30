<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DreamHouse • Login</title>
    <link rel="icon" href="{{ asset('uploads/ads/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        .login-page {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background-color: #fff;
            padding: 20px 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container img {
            width: 50px;
        }
        .login-container h2 {
            margin: 20px 0;
            font-weight: 500;
        }
        .login-container input[type="email"], .login-container input[type="password"], .login-container button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container button {
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container a {
            display: block;
            margin: 10px 0;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-page">
    <div class="login-container">
        <a href="your-link-here">
            <img src="{{ asset('uploads/ads/logo.png') }}" alt="Logo">
        </a>
        <h2>Tenant</h2>
        <form method="POST" action="{{ route('tenant_login_submit') }}">
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <a href="#">Don't have an account? Register</a>
    </div>
</div>

</body>
</html>
