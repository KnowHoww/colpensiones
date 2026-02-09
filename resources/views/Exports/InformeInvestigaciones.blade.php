<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Estado</th>
            <th>Centro de costos</th>
            <th>Prioridad</th>
            <th>Fecha Transferencia</th>
            <th>Tipo de Investigación</th>
            <th>Tipo de Riesgo</th>
            <th>Tipo de Trámite</th>
            <th>Detalle del Riesgo</th>
            <th>Número caso Padre</th>
            <th>Tipo Documento Causante</th>
            <th>Número de Documento Causante</th>
            <th>Primer Causante</th>
            <th>Segundo Causante</th>
            <th>Primer apellido Causante</th>
            <th>Segundo apellido Causante</th>
            <th>Dirección Causante</th>
            <th>Teléfono Causante</th>
            <th>Coordinador</th>
            <th>Investigador</th>
            <th>Analista</th>
            <th>Junta</th>
            <th>Número Dictamen</th>
            <th>Fecha dictamen</th>
            <th>Observaciones o Causa de la investigación</th>
            <th>Punto De Atención</th>
            <th>punto direccion</th>
            <th>fecha limite</th>
            <th>Nombre carpeta</th>
            <th>Numero Caso Original</th>
            <th>Región</th>
            <th>Tipo documento Beneficiario 1</th>
            <th>Número de documento Beneficiario 1</th>
            <th>Parentesco 1</th>
            <th>Nombres beneficiario 1</th>
            <th>Apellidos beneficiario1</th>
            <th>Nit1</th>
            <th>Institución educativa1</th>
            <th>Tipo documento Beneficiario 2</th>
            <th>Número de documento Beneficiario 2</th>
            <th>Parentesco 2</th>
            <th>Nombres beneficiario 2</th>
            <th>Apellidos beneficiario2</th>
            <th>Nit2</th>
            <th>Institución educativa2</th>
            <th>Tipo documento Beneficiario 3</th>
            <th>Número de documento Beneficiario 3</th>
            <th>Parentesco 3</th>
            <th>Nombres beneficiario 3</th>
            <th>Apellidos beneficiario3</th>
            <th>Nit3</th>
            <th>Institución educativa3</th>
            <th>Tipo documento Beneficiario 4</th>
            <th>Número de documento Beneficiario 4</th>
            <th>Parentesco 4</th>
            <th>Nombres beneficiario 4</th>
            <th>Apellidos beneficiario4</th>
            <th>Nit4</th>
            <th>Institución educativa4</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->IdCase }}</td>
                <td>{{ optional($item->estados)->name }}</td>
                <td>{{ $item->CentroCosto }}</td>
                <td>{{ Optional($item->Prioridades)->nombre }}</td>
                <td>{{ $item->MarcaTemporal }}</td>
                <td>{{ optional($item->TipoInvestigaciones)->nombre }}</td>
                <td>{{ optional($item->TipoRiesgos)->nombre }}</td>
                <td>{{ optional($item->TipoTramites)->nombre }}</td>
                <td>{{ optional($item->DetalleRiesgos)->nombre }}</td>
                <td>{{ $item->NumeroRadicacionCaso }}</td>
                <td>{{ optional($item->TipoDocumentos)->nombre }}</td>
                <td>{{ $item->NumeroDeDocumento }}</td>
                <td>{{ $item->PrimerNombre }}</td>
                <td>{{ $item->SegundoNombre }}</td>
                <td>{{ $item->PrimerApellido }}</td>
                <td>{{ $item->SegundoApellido }}</td>
                <td>{{ $item->DireccionCausante }}</td>
                <td>{{ $item->TelefonoCausante }}</td>
                <td>{{ $item->name_coordinador }} {{ $item->lastname_coordinador }}</td>
                <td>{{ $item->name_investigador }} {{ $item->lastname_investigador }}</td>
                <td>{{ $item->name_analista }} {{ $item->lastname_analista }}</td>
                <td>{{ $item->Junta }}</td>
                <td>{{ $item->NumeroDictamen }}</td>
                <td>{{ $item->FechaDictamen }}</td>
                <td>{{ $item->Observacion }}</td>
                <td>{{ $item->PuntoAtencion }}</td>
                <td>{{ $item->DireccionPunto }}</td>
                <td>{{ $item->fechaLimite }}</td>
                <td>{{ $item->nombreCarpeta }}</td>
                <td>{{ $item->CasoPadreOriginal }}</td>
                <td>{{ optional($item->Regiones)->nombre }}</td>
                @foreach ($item->beneficiarios as $beneficiario)
                    <td>{{ $beneficiario->TipoDocumento }}</td>
                    <td>{{ $beneficiario->NumeroDocumento }}</td>
                    <td>{{ optional($beneficiario->Parentescos)->nombre }}</td>
                    <td>{{ $beneficiario->PrimerNombre }} {{ $beneficiario->SegundoNombre }}</td>
                    <td>{{ $beneficiario->PrimerApellido }} {{ $beneficiario->SegundoApellido }}</td>
                    <td>{{ $beneficiario->Nit }}</td>
                    <td>{{ $beneficiario->InstitucionEducativa }}</td>
                @endforeach

            </tr>
        @endforeach
    </tbody>

</table>
