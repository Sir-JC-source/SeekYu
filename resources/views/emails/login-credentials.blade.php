<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Credentials - SeekYu HRIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
        }
        .credentials {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #4f46e5;
        }
        .credentials h3 {
            margin-top: 0;
            color: #4f46e5;
        }
        .btn {
            display: inline-block;
            background-color: #000;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0000;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SeekYu HRIS</h1>
            <p>Your account has been created successfully!</p>
        </div>

        <p>Dear {{ $user->fullname }},</p>

        <p>Thank you for registering with SeekYu HRIS. Your account has been created and is pending email verification. To complete your registration and access your account, please verify your email address by clicking the button below.</p>

        <div class="credentials">
            <h3>Your Login Credentials:</h3>
            <p><strong>Login ID:</strong> {{ $user->login_id ?? $user->student_no ?? $user->faculty_no ?? $user->email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="btn">Verify Your Email Address</a>
        </div>

        <div class="warning">
            <strong>Important:</strong> You will not be able to log in until you verify your email address. Please check your spam/junk folder if you don't see this email in your inbox.
        </div>

        <p>If you did not create this account, please ignore this email.</p>

        <p>Best regards,<br>
        SeekYu HRIS Team</p>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} SeekYu HRIS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
