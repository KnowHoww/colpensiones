{!! Form::model($entrevistaFamiliares, [
    'route' => ['entrevistaFamiliares.update', $entrevistaFamiliares->id],
    'method' => 'put',
]) !!}
<div class="row">
    <div class="form-group col-12">
        {!! Form::label('laborCampo', 'Entrevista a familiares del causante', ['class' => 'form-label']) !!}
        {!! Form::textarea('laborCampo', null, [
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
