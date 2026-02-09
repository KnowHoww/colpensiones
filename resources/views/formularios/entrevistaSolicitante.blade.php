{!! Form::model($entrevistaSolicitante, [
    'route' => ['entrevistasolicitante.update', $entrevistaSolicitante->id],
    'method' => 'put',
]) !!}
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('trabajo_campo', 'Entrevista a solicitante', ['class' => 'form-label']) !!}
        {!! Form::textarea('trabajo_campo', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
            'rows' => '5',
        ]) !!}
    </div>
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
