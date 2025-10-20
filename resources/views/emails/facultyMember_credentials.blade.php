<!DOCTYPE html>
<html>
<head>
    <title>Faculty Account Created</title>
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
            width: 120px;
        }
        .security-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
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
        <h1>Welcome to SBLMS Faculty Portal!</h1>

        <p>Dear {{ $fullname }},</p>

        <p>Your faculty account has been successfully created and approved 🎉. You can now access the SBLMS Faculty Portal using the credentials below:</p>

        <div class="credentials">
            <p><strong>Faculty No:</strong> {{ $faculty_no }} <em>(use this to login)</em></p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>

        <div class="security-note">
            <p><strong>Important Security Notice:</strong></p>
            <p>For your account security, please log in and change your password immediately after your first login. Your temporary password should not be shared with anyone.</p>
        </div>

        <p>As a faculty member, you now have access to:</p>
        <ul>
            <li>Course management tools</li>
            <li>Student information systems</li>
            <li>Academic resources and materials</li>
            <li>Faculty communication portal</li>
        </ul>

        <p>If you have any questions or need assistance with your account, please don't hesitate to contact our support team.</p>

        <p>Welcome to our academic community!</p>

        <p>Best regards,<br>SBLMS Administration Team</p>

        <div class="footer">
            &copy; {{ date('Y') }} SBLMS. All rights reserved.
        </div>
    </div>
</body>
</html>