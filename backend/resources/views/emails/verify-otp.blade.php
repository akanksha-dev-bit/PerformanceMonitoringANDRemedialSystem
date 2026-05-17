<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #fbf9f2;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-wrapper {
            width: 100%;
            background-color: #fbf9f2;
            padding: 40px 0;
        }
        .email-card {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 40px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #111;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 22px;
            color: #111;
            margin-bottom: 10px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 30px;
        }
        .otp-box {
            background-color: #f5f3ff;
            border: 2px dashed #6c5ce7;
            border-radius: 12px;
            padding: 20px;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #6c5ce7;
            margin: 0 auto 30px;
            width: fit-content;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            <div class="logo">PMRS</div>
            <h1>Verify your email address</h1>
            <p>Hi {{ $name }},<br><br>Thank you for registering with PMRS. To complete your registration and secure your account, please enter the following 6-digit verification code on the verification page.</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <p style="font-size: 13px;">This code will expire in 30 minutes. If you did not create an account, please ignore this email.</p>
            
            <div class="footer">
                &copy; {{ date('Y') }} PMRS. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
