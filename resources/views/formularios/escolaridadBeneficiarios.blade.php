{!! Form::model($beneficiario, [
    'route' => ['escolaridadBeneficiario.update', $beneficiario->id],
    'method' => 'put',
]) !!}
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('institucion', 'Nombre Entidad Educativa', ['class' => 'form-label']) !!}
        {!! Form::text('institucion', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('correo', 'Correo electronico entidad Educativa', ['class' => 'form-label']) !!}
        {!! Form::email('correo', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('telefono', 'Télefono Entidad Educativa', ['class' => 'form-label']) !!}
        {!! Form::text('telefono', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('observacion', 'Observación', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
