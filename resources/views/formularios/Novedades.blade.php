{!! Form::open(['route' => ['novedad.store']]) !!}
{{ csrf_field() }}
{!! Form::hidden('idInvestigacion', $investigacion->id, [
    'class' => 'form-control',
    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
    'required',
]) !!}
<div class="justify-content-around d-flex">
    <div class="form-group col-6">
        {!! Form::label('fecha', 'Fecha', ['class' => 'form-label']) !!}
        {!! Form::input('datetime-local', 'fecha', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('novedad', 'Novedad', ['class' => 'form-label']) !!}
        {!! Form::textarea('novedad', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => 2,
            'required',
        ]) !!}
    </div>
</div>
@can('formulario.guardar-cambios')
    {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
@endcan
{!! Form::close() !!}
