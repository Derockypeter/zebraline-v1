<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support message</title>
    <style>
        .center {
            text-align: center;
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif;">
    <h2>Hello,</h2>
    <p class="lead">An email has been recieved from <b>{{ $data['firstname'] }}  {{ $data['lastname'] }}</b></p>
    <p class="lead">The support request mail detail of user are:</p>
    <p>Names: <b>{{ $data['firstname'] }}  {{ $data['lastname'] }}</b></p>
    <p>Email: <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></p>
    <p>Phone: <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a></p>
    <blockquote>{{ $data['message'] }}</blockquote></p>
    <p class="center">&copy; Zebraline {{ date('Y') }}</p>
</body>
</html>