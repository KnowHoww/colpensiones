<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobación de Informe</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <h1 style="color: #333;">Revisión y aprobación del informe de actividades de  {{ $fecha_inicio }} a {{ $fecha_fin }}.</h1>
	<p>{{  mb_convert_case($investigador->name, MB_CASE_TITLE, "UTF-8")}}, buen dia.</p>
	
    <p>Se ha solicitadola revisión y aprobación del informe  Con el siguiente link podra acceder a la pantalla de aprobación para realizar la revisión y aprobación de dicho informe.</p>

    <p><a href="{{url('/verinformeInvestigador/' . $informe->id) }}">HAGA CLICK AQUI </a>
    </p>
    <p><strong>Instrucciones</strong></p>
    <p>-Recuerda que una vez ingreses, se te solicitará tu contraseña.</br>-Puede necesario se deberá ingresar al link de nuevo una vez suministre la contraseña.</p>

    <p>Gracias,</p>
    <p><b>JAHV McGregor S.A.S-</b></p>
	<p><i>Signature:{{ $signature }}</i></p>
</body>

</html>
