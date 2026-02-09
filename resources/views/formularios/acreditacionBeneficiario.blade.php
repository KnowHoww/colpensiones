{!! Form::model($beneficiario, ['route' => ['acreditacion.update', $beneficiario->id], 'method' => 'put']) !!}
<div class="row">
    <div class="form-group col-12">
        <div class="form-group col-3">
            {!! Form::label('acreditacion', 'Acreditación', ['class' => 'form-label']) !!}
            {!! Form::select(
                'acreditacion',
                [null => 'Seleccione... '] + collect($seleccionAcreditacion)->pluck('name', 'id')->toArray(),
                null,
                [
                    'class' => 'form-control',
                    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                ],
            ) !!}
        </div>
    </div>
    <div class="form-group col-12">
        {!! Form::label('resumen', 'resumen investigación', ['class' => 'form-label']) !!}
        {!! Form::textarea('resumen', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('conclusion', 'resumen conclusión', ['class' => 'form-label']) !!}
        {!! Form::textarea('conclusion', null, [
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
