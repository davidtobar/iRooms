<!DOCTYPE html>
<html>
<head>
    <title>Notification creation of meeting</title>
    <link rel="stylesheet" href="{{ url('css/email.css') }}">
</head>
 
<body>
        <div style="width: 40%; margin: 0 auto;">
            <h2 style="color: #0971ba; margin-bottom: 40px; text-align: center;">Notify meetings {{ $data['app_name'] }}</h2>
            <p style="font-size: 18px;">This is a notification of the creation of this meeting:</p>
            <p style="font-size: 18px;"><strong>Name:</strong> {{ $data['name'] }}<br><br>
               <strong>Room:</strong> {{ $data['room_name'] }}<br><br>
               <strong>Start date:</strong> {{ $data['start_date'] }} - {{ $data['start_time'] }}<br><br>
               <strong>End date:</strong> {{ $data['end_date'] }} - {{ $data['end_time'] }}<br><br>
               <strong>Description:</strong> {{ $data['description'] }}
        </div>

</body>
 
</html>