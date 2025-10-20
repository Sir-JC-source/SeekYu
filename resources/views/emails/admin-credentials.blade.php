<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Account Credentials</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
        }
        h2 {
            color: #007bff;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .credentials {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        .button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello, {{ $name }}!</h2>

    <div class="content">
        <p>An admin account has been created for you. Please use the credentials below to login:</p>

        <div class="credentials">
            <strong>Email:</strong> {{ $email }}<br>
            <strong>Temporary Password:</strong> {{ $password }}
        </div>

        <p>We strongly recommend that you change your password immediately after logging in.</p>

        <a href="{{ url('/login') }}" class="button">Go to Login</a>
    </div>

    <div class="footer">
        <p>If you did not request this account, please contact the administrator immediately.</p>
    </div>
</div>
</body>
</html>
