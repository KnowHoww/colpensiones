{!! Form::open(['route' => ['trazabilidadactividad.store', $investigacion->id]]) !!}
{{ csrf_field() }}
{!! Form::hidden('idInvestigacion', $investigacion->id, [
    'class' => 'form-control',
    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
    'required',
]) !!}
<div class="justify-content-around d-flex">
    <!-- Actividad Realizada -->
    <div class="form-group col-4">
        {!! Form::label('actividad', 'Actividad realizada', ['class' => 'form-label']) !!}

        {!! Form::select('actividad', $actividadestipoinvestigacion, null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'required',
        ]) !!}
        {!! Form::hidden('actividadestipoinvestigacion', json_encode($actividadestipoinvestigacion)) !!}
    
    </div>

    <!-- Observacion -->
    <div class="form-group col-4">
        {!! Form::label('observacion', 'Observación', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => 1,
            'required',
        ]) !!}
    </div>
        <!-- Fecha -->
        <div class="form-group col-4">
        {!! Form::label('fecha', 'Fecha', ['class' => 'form-label']) !!}
        {!! Form::input('datetime-local', 'fecha', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
     </div>

</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
