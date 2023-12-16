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
    <p class="lead">An email has been recieved from <b>{{ $email }}</b></p>
    <p class="lead">The support request mail detail of user are:</p>
    <p>Email: <a href="mailto:{{ $email }}">{{ $email }}</a></p>
    <p>Hello, I am requesting assistance</p></p>
    <p class="center">&copy; whitecoatdomain {{ date('Y') }}</p>
</body>
</html>