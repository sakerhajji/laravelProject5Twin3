<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Meeting Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 0;
            margin: 0;
        }
        .container {
            background-color: #fff;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
        }
        p {
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>You're Invited to a Meeting!</h2>
        <p>Hello,</p>
        <p>You have been invited to join a video meeting. Click the button below to join:</p>
        <a href="https://meet.jit.si/{{ $roomName }}" class="btn">Join Meeting</a>
        <p>Room Name: <strong>{{ $roomName }}</strong></p>
        <div class="footer">
            <p>If you didn’t expect this email, please ignore it.</p>
        </div>
    </div>
</body>
</html>
        