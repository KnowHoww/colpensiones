<?php
setlocale(LC_TIME, 'es_ES.UTF-8');

$i = 18;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>INFORME MENSUAL DE ACTIVIDADES DE CONTRATOS DE PRESTACIÓN DE SERVICIOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>

    <style>
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

        .border-table {
            border: 1px solid #000000;
            border-top: 0px solid transparent;
            font-family: "Roboto", sans-serif;
            padding: 2px 5px;
        }

        .border-table p {
            margin: 0px;
            border: 0px solid transparent !important;
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

        .table-body tr,
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
                    <th class="text-center roboto-bold">GESTIÓN DEL TALENTO HUMANO</th>
                </tr>
                <tr>
                    <th class="text-center roboto-bold">INFORME MENSUAL DE ACTIVIDADES DE CONTRATOS DE PRESTACIÓN DE SERVICIOS</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="content">


        <div class="section">
            <table class="table-body">
                <tr>
                    <th class="bg-primary" colspan="2">DATOS DEL CONTRATISTA</th>
                </tr>
                <tr>
                    <td>Nombre Contratista: <br/><b>{{ $contratista->nombres }}</b></td>
                    <td>No. de Identificación: <br/><b>{{ $contratista->identificacion }}</b></td>
                </tr>
                <tr>
                    <td>Nombre del proyecto: <br/><b>COLPENSIONES</b></td>
                    <td>Informe de Ejecución del periodo comprendido de: <br/><b>{{ $periodo }}</b></td>
                </tr>
            </table>
                
            <table class="table-body">
                <tr>
                    <th class="bg-primary" >DATOS DEL CONTRATO</th>
                </tr>
                <tr>
                    <td><b>Contrato No 004 : COLPENSIONES</b></td>

                </tr>
                
            </table>
                
                
            <table class="table-body">
                <tr>
                    <th class="bg-primary" colspan="3">RELACIÓN DE INVESTIGACIONES Y/O TRAMITES REALIZADOS DURANTE EL MES</th>
                </tr>
                <tr>
                    <th class="bg-primary" >INVESTIGACION TIPO </th>
                    <th class="bg-primary" style="width:30%" >NUMERO DE INVESTIGACIONES EFECTIVAS REALIZADAS</th>
                    <th class="bg-primary" >OBSERVACIONES </th>
                </tr>               
                 @foreach ($datos as $item)
                
                    <tr>
                        <td>{{ $item->tipoInvestigacion }}</b></td>
                        <td style="width:30%" >{{ $item->cantidad }}</b></td>
                        <td>
                        @foreach ($investigaciones as $item2)
                        
                         
                            @if ($item2->tipoInvestigacion == $item->tipoInvestigacion )
                                @php($i++)
                                {{ $item2->NumeroRadicacionCaso }}_{{ $item2->idInvestigacion }}
                                @if ($item2->porBeneficiario >0 )
                                    (Por Beneficiario)
                                @endif
                            
                            <br/>
                            @endif
                            @if ($i>47)
                                @php($i=1)
                                    </table>
                                    <table class="table-body" style="page-break-before: always;">
                                       
                                        <tr>
                                <td style="width:30%" ></td>
                                <td></td>
                                <td>
                            @endif

                        @endforeach
                        
                        @foreach ($apoyos as $item3)
                            
                            @if ($item3->tipoInvestigacion == $item->tipoInvestigacion )
                                @php($i++)
                                {{ $item3->NumeroRadicacionCaso }}_{{ $item3->idInvestigacion }}(APOYO)<br/>
                            @endif
                            @if ($i>47)
                                @php($i=1)
                                    </table>
                                    <table class="table-body" style="page-break-before: always;">
                                        
                                        <tr>
                                <td style="width:30%"  ></td>
                                <td></td>
                                <td>
                            @endif


                        @endforeach
                        </b></td>
                    </tr>
                   
                    
                 @endforeach
            </table>
            <table class="table-body">
                <tr>
                    <td class="bg-primary" rowspan ="3">CONTRATISTA </td>
                    <td>NOMBRE Y APELLIDO:  <br/><b>{{ $contratista->nombres }}</b></td>
                </tr>               
                <tr>
                    <td>FIRMA:<br/>&nbsp;<br/><b>&nbsp;<br/>&nbsp;</td>
                </tr>               
                <tr>
                    <td>No. IDENTIFICACION:<br/><b>{{ $contratista->identificacion }}</b> </td>
                </tr>               
            </table>
      <p style="page-break-before: always;">
            <table>
                <tr>
                    <th class="bg-primary"  colspan ="2">INFORME SUPERVISOR DEL CONTRATO</th>
                </tr>
                 <tr>
                    <td class="bg-primary" rowspan ="2"><b>RESPONSABLE <br/>
                            (Coordinador Regional)</b><br/>&nbsp;<br/>&nbsp;
                            </td>
                    <td>NOMBRE Y APELLIDO:<br/>&nbsp;</td>
                </tr>   
                 <tr>
                    
                    <td>FIRMA:<br/>&nbsp;<br/>&nbsp;</td>
                </tr>   
                </table>
                <br/>&nbsp;
                <table>
                <tr>
                    <td class="bg-primary"  colspan ="2">OTRAS OBSERVACIONES:</th>
                </tr>
                <tr>
                    
                    <td colspan ="2"><br/>&nbsp;<br/>&nbsp;<br/>&nbsp;&nbsp;</td>
                </tr>
            </table>
        
          </p>
          
        
    </div>
</body>

</html>
