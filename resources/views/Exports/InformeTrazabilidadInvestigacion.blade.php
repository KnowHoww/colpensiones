<!-- InformeInvestigaciones.blade.php -->
<table>
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Actividad</th>
            {{-- <th>Fecha</th> --}}
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $dato)
            <tr>
                <td>{{ $dato->creadores->name }} {{ $dato->creadores->lastname }}</td>
                <td>{{ $dato->rol_usuario }}</td>
                <td>{{ $dato->actividad }}</td>
                {{-- <td>{{ $dato->fecha }}</td> --}}
                <td>{{ $dato->observacion }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
