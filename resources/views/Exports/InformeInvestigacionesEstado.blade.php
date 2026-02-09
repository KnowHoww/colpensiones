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
            <th>Número caso Padre</th>
            <th>Tipo Documento Causante</th>
            <th>Número de Documento Causante</th>
            <th>Primer Causante</th>
            <th>Segundo Causante</th>
            <th>Primer apellido Causante</th>
            <th>Segundo apellido Causante</th>
            <th>Coordinador</th>
            <th>Investigador</th>
            <th>Analista</th>
            <th>fecha limite</th>
            <th>Nombre carpeta</th>
            <th>Numero Caso Original</th>
            <th>Región</th>
            <th>Fecha Finalización</th>
            <th>Fecha Objetado</th>
            <th>Fecha Finalizado 
                Objetado</th>
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
                <td>{{ $item->NumeroRadicacionCaso }}</td>
                <td>{{ optional($item->TipoDocumentos)->nombre }}</td>
                <td>{{ $item->NumeroDeDocumento }}</td>
                <td>{{ $item->PrimerNombre }}</td>
                <td>{{ $item->SegundoNombre }}</td>
                <td>{{ $item->PrimerApellido }}</td>
                <td>{{ $item->SegundoApellido }}</td>
                <td>{{ $item->name_coordinador }} {{ $item->lastname_coordinador }}</td>
                <td>{{ $item->name_investigador }} {{ $item->lastname_investigador }}</td>
                <td>{{ $item->name_analista }} {{ $item->lastname_analista }}</td>
                <td>{{ $item->fechaLimite }}</td>
                <td>{{ $item->nombreCarpeta }}</td>
                <td>{{ $item->CasoPadreOriginal }}</td>
                <td>{{ optional($item->Regiones)->nombre }}</td>
                <td>{{ $item->FechaFinalizacion }}</td>
                <td>{{ $item->FechaObjecion }}</td>
                <td>{{ $item->FechaFinalizacionObjecion }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
