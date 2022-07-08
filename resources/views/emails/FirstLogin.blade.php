<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h3>Bienvenido, {{$name}}</h3>

    <p>
        Tu usuario es <strong>{{$email}}</strong> <br>
        Tu contraseña es <strong>{{$password}}</strong>. Por favor accede a <a href="{{ env('APP_FRONTEND_URL').'/perfil' }}">este link</a> para cambiarla.
    </p>
</body>

</html>