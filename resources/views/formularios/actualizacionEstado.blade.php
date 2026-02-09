{!! Form::model($investigacion, ['route' => ['investigacionstep', $investigacion], 'method' => 'put']) !!}
{{ Form::token() }}
<div class="justify-content-around">
    <div class="form-group">
        {!! Form::label('estado', 'Estado', ['class' => 'form-label']) !!}
        {!! Form::select('estado', collect($estados)->pluck('name', 'id'), null, [
            'class' => 'form-control',
        ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('observacion', 'Observación', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion', null, [
            'class' => 'form-control',
            'rows' => 2,
            'required',
        ]) !!}
    </div>
    <div id="seleccionObjecion" class="d-none">
        <div class="form-group">
            {!! Form::label('CausalPrimariaObjecion', 'Objeción causal primaria*', ['class' => 'form-label']) !!}
            {!! Form::select(
                'CausalPrimariaObjecion',
                [null => 'Seleccione... '] + collect($causalObjecion)->pluck('nombre', 'id')->toArray(),
                null,
                [
                    'class' => 'form-control',
                    'id' => 'causalPrimaria'
                ],
            ) !!}

        </div>
        <div class="form-group">
            {!! Form::label('CausalSecundariaObjecion', 'Objeción causal secundaria', ['class' => 'form-label']) !!}
            {!! Form::select(
                'CausalSecundariaObjecion',
                [null => 'Seleccione... '] + collect($causalObjecion)->pluck('nombre', 'id')->toArray(),
                null,
                [
                    'class' => 'form-control',
                ],
            ) !!}
        </div>
    </div>
</div>
{{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
{!! Form::close() !!}
