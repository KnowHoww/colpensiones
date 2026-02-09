<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Informe Aprobado</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <h1 style="color: #333;">Revisión y aprobación del informe de actividades de  {{ $informe->Inicio }} a {{ $informe->Fin }}.</h1>
	<p>{{  mb_convert_case($investigador->name, MB_CASE_TITLE, "UTF-8")}}, buen dia.</p>
	
    <p>El informe ha sido aprobado el {{$informe->updated_at }} </p>

    <p><a href="{{url('/verinformeInvestigadorpdf/' . $informe->id) }}">Ver Informe Aprobado</a>
    </p>
    
    <p>Gracias,</p>
    <p><b>JAHV McGregor S.A.S-</b></p>
	<p><i>Signature:{{ $signature }}</i></p>
</body>

</html>
