<!DOCTYPE html>
<html>
<head>
    <title>Login Credentials</title>
    <style>
        /* General resets for email clients */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 120px;
            height: auto;
        }
        h1 {
            font-size: 24px;
            color: #7367F0;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .credentials {
            background-color: #f1f1f1;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .credentials strong {
            display: inline-block;
            width: 100px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Welcome to SBLMS!</h1>

        <p>Hello {{ $fullname }},</p>

        <p>Your account has been approved 🎉. You can now log in using the credentials below:</p>

        <div class="credentials">
            <p><strong>Student No:</strong> {{ $student_no }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <p>Please log in and change your password after your first login for security.</p>

        <p>Best regards,<br>SBLMS Administration Team</p>

        <div class="footer">
            &copy; {{ date('Y') }} SBLMS. All rights reserved.
        </div>
    </div>
</body>
</html>
