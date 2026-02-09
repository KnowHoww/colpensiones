<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de contraseña</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <h1 style="color: #333;">Recuperación de contraseña</h1>

    <p>Hola {{ $name }},</p>

    <p>Se ha iniciado el reestablecimiento de su contraseña.Esta es tu contraseña temporal:<strong>{{ $password }}</strong></p>
    <p><b>Recuerda que una vez ingreses, se te solicitará actualizar tu contraseña.</b></p>
    <p>Si no fuiste tú, notifican de  inmediato al equipo de soporte.</p>
    <p><b>JAHV McGregor S. A.S</b></p>
    <p><i>Signature:{{ $signature }}</i></p>

</body>

</html>
