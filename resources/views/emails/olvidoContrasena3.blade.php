<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de contraseña</title>
</head>

<body style="font-family: Arial, sans-serif;">
    <h1 style="color: #333;">Reestablecimento de contraseña</h1>
    <p>Este es un correo generado automaticamente para el reestablecimento de la contraseña.    </p>
    <h2 style="color: #333;">Contraseña Temporal</h2>
    <div style = "border: 2px solid #5c5c5c;">
    <p><strong>{{ $password }}</strong></p>
    </div>
    <p><b>Una vez ingreses, se te deberá actualizar la contraseña por una nueva.</b></p>
    <p><b>JAHV McGregor S. A.S</b></p>
    <p><i>Signature:{{ $signature }}</i></p>

</body>

</html>
