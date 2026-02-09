<?php
setlocale(LC_TIME, 'es_ES.UTF-8');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>{{ $nombreArchivo }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>

    <style>
        .img-soporte-fotografico{
            width: 100%;
            padding: 10px
        }
        .roboto-thin {
            font-family: "Roboto", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .roboto-light {
            font-family: "Roboto", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .roboto-regular {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .roboto-medium {
            font-family: "Roboto", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .roboto-bold {
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .roboto-black {
            font-family: "Roboto", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .roboto-thin-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .roboto-light-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .roboto-regular-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .roboto-medium-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .roboto-bold-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .roboto-black-italic {
            font-family: "Roboto", sans-serif;
            font-weight: 900;
            font-style: italic;
        }

        .table-head .tr,
        th,
        td {
            border: 3px solid #011837;
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
            padding: 2px 5px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 170px;
            margin-top: 0px;
            background-color: #fff;
            font-family: "Roboto", sans-serif;
            font-weight: 700;
            font-style: normal;
            line-height: 1.4;
        }

        .content {
            /* margin-top: 180px; */
        }

        .table-body .tr,
        th,
        td {
            border: 1px solid black;
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
            padding: 2px 5px;
        }

        .logo {
            width: 100px;
            padding: 5px;
            align-content: center;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
        }

        .logo img {
            height: 20px;
        }

        .bg-primary {
            background: #dbe5f1 !important;
            text-align: center;
            font-weight: 700;
        }

        table {
            width: 100%;
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        * {
            font-size: 13px !important;
            word-wrap: break-word;
        }

        .text-left {
            text-align: left !important;
        }

        .section {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="headers">
        <table class="table datatable table-head">
            <thead>
                <tr>
                    <th rowspan="2" class="logo"><img src="../public/images/logo_javh.png" /></th>
                    <th class="text-center roboto-bold">Gestión de proyecto</th>
                    <th class="text-center roboto-bold">Página de </th>
                </tr>
                <tr>
                    <th class="text-center roboto-bold">Informe Técnico Investigaciones Administrativas</th>
                    <th class="text-center roboto-bold">Versión 01</th>
                </tr>
            </thead>
        </table>
    </div>
    <div>
        @foreach ($documentos as $item)
        <img class="img-soporte-fotografico" src="investigaciones/{{ $item }}" alt="">
        @endforeach
    </div>
</body>

</html>
