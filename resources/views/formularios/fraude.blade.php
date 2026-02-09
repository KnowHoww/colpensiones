{!! Form::model($esFraude, ['route' => ['fraude.update', $esFraude->id], 'method' => 'put']) !!}
<div class="row">
    <div class="form-group col-12">
        <div class="form-group col-3">
            {!! Form::label('fraude', '¿Es presunto fraude?', ['class' => 'form-label']) !!}
            {!! Form::select('fraude', collect($campoSino)->pluck('name', 'id'), null, [
                'class' => 'form-control',
                'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            ]) !!}
        </div>
    </div>
    <div class="form-group col-12">
        {!! Form::label('observacion', 'Observación del presunto fraude', ['class' => 'form-label']) !!}
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
