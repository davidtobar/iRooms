<!DOCTYPE html>
<html>
<head>
    <title>Notification creation of user</title>
</head>
 
<body>
        <div style="width: 40%; margin: 0 auto;">
            <h2 style="color: #0971ba; margin-bottom: 40px; text-align: center;">User Invitation</h2>
            <p style="font-size: 16px;">{{ $data['user_l'] }} invited you as a user {{ $data['app_name'] }}.</p>
            <p style="font-size: 16px;">Your login credentials are:</p>
            <p style="font-size: 16px;"><strong>Username:</strong> {{ $data['usern'] }}<br><br>
               <strong>Password:</strong> {{ $data['password'] }}<br></p>
            <p style="font-size: 16px;">Click here to enter iRooms: {{ route('login') }}</p>
        </div>

</body>
 
</html>