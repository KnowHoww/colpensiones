{!! Form::model($beneficiario, [
    'route' => ['investigacionverificacion.update', $beneficiario->id],
    'method' => 'put',
]) !!}
{{ csrf_field() }}
<div class="justify-content-around d-flex">
    <div class="form-group col-4">
        {!! Form::label('ciudad', 'Ciudad verificación', ['class' => 'form-label']) !!}
        {!! Form::text('ciudad', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('direccion', 'Dirección verificación', ['class' => 'form-label']) !!}
        {!! Form::text('direccion', null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('telefono', 'Teléfono verificación', ['class' => 'form-label']) !!}
        {!! Form::text('telefono', null, [
            'class' => 'form-control',
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
