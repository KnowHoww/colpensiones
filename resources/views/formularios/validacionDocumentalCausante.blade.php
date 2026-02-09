{!! Form::model($validacionDocumentalCausante, [
    'route' => ['validaciondocumentalsolicitante.update', $validacionDocumentalCausante->id],
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
        {!! Form::label('defuncion', 'Registro civil de defunción', ['class' => 'form-label']) !!}
        {!! Form::textarea('defuncion', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('matrimonio', 'Registro civil de matrimonio', ['class' => 'form-label']) !!}
        {!! Form::textarea('matrimonio', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('gastos_funebre', 'Evidencia gastos fúnebres', ['class' => 'form-label']) !!}
        {!! Form::textarea('gastos_funebre', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '2',
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('gastos_funerarios', 'Gastos funenarios', ['class' => 'form-label']) !!}
        {!! Form::textarea('gastos_funerarios', null, [
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
