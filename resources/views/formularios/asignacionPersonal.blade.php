{!! Form::model($asignacion, ['route' => ['asignacion.update', $asignacion->id], 'method' => 'put']) !!}
{{ Form::token() }}
<div class="d-flex justify-content-around">
    <div class="form-group">
        {!! Form::label('CoordinadorRegional', 'Coordinador Regional:', ['class' => 'form-label']) !!}
        {!! Form::select(
            'CoordinadorRegional', // Ensure correct field name match
            ['0' => 'Seleccione un coordinador'] + collect($coordinadores)->pluck('full_name', 'id')->toArray(),
            $asignacion->coordinador_regional_id ?? null, // Bind to model property
            [
                'class' => 'form-control',
                'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            ],
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Investigador', 'Investigador:', ['class' => 'form-label']) !!}
        {!! Form::select(
            'Investigador', // Ensure correct field name match
            ['0' => 'Seleccione un investigador'] + collect($investigadores)->pluck('full_name', 'id')->toArray(),
            $asignacion->investigador_id ?? null, // Bind to model property
            [
                'class' => 'form-control',
                'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            ],
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Auxiliar', 'Auxiliar:', ['class' => 'form-label']) !!}
        {!! Form::select(
            'Auxiliar', // Ensure correct field name match
            ['0' => 'Seleccione un auxiliar'] + collect($investigadores)->pluck('full_name', 'id')->toArray(),
            $asignacion->auxiliar_id ?? null, // Bind to model property
            [
                'class' => 'form-control',
                'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            ],
        ) !!}
    </div>
    <div class="form-group">
        {!! Form::label('Analista', 'Analista:', ['class' => 'form-label']) !!}
        {!! Form::select(
            'Analista', // Ensure correct field name match
            ['0' => 'Seleccione un analista'] + collect($analistas)->pluck('full_name', 'id')->toArray(),
            $asignacion->analista_id ?? null, // Bind to model property
            [
                'class' => 'form-control',
                'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            ],
        ) !!}
    </div>
</div>
@if ($investigacion->estado == 3 || $investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
