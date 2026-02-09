{!! Form::model($estudioAuxiliar, [
    'route' => ['estudioauxiliar.update', $estudioAuxiliar->id],
    'method' => 'put',
]) !!}
<div class="form-group col-12">
    {!! Form::label('hallazgos', 'Hallazgos adicionales en el proceso de investigación', ['class' => 'form-label']) !!}
    {!! Form::textarea('hallazgos', null, [
        'class' => 'form-control editor',
        'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        'rows' => '2',
    ]) !!}
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
