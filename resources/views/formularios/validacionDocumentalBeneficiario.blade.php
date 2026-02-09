{!! Form::model($beneficiario, [
    'route' => ['validaciondocumentalbeneficiario.update', $beneficiario->id],
    'method' => 'put',
]) !!}
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('cedula', 'Cédula de ciudadanía', ['class' => 'form-label']) !!}
        {!! Form::textarea('cedula', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('nacimiento', 'Registro civil de nacimiento', ['class' => 'form-label']) !!}
        {!! Form::textarea('nacimiento', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('incapacidad', 'Dictamen médico de incapacidad laboral', ['class' => 'form-label']) !!}
        {!! Form::textarea('incapacidad', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('escolaridad', 'Certificado de escolaridad', ['class' => 'form-label']) !!}
        {!! Form::textarea('escolaridad', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
