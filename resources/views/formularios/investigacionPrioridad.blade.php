{!! Form::model($investigacion, [
    'route' => ['investigacionregion.update', $investigacion->id],
    'method' => 'put',
]) !!}
{{ Form::token() }}
<div class="d-flex justify-content-around">
    <div class="form-group">
        {!! Form::label('Prioridad', 'Prioridad de investigación', ['class' => 'form-label']) !!}
        {!! Form::select(
            'Prioridad',
            collect($InvestigacionPrioridad)->pluck('nombre', 'id')->toArray(),
            $investigacion->Prioridad,
            [
                'class' => 'form-control',
            ],
        ) !!}
    </div>
</div>
{{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
{!! Form::close() !!}
