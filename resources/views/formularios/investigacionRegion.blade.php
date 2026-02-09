{!! Form::model($investigacion, [
    'route' => ['investigacionregion.update', $investigacion->id],
    'method' => 'put',
]) !!}
{{ Form::token() }}
<div class="d-flex justify-content-around">
    <div class="row">
        <div class="form-group col-12 col-md-4">
            {!! Form::label('region', 'Region de investigación', ['class' => 'form-label']) !!}
            {!! Form::select(
                'region',
                ['0' => 'Seleccione una region'] + collect($InvestigacionRegiones)->pluck('nombre', 'id')->toArray(),
                $investigacion->region ?? 0,
                [
                    'class' => 'form-control',
                    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                ],
            ) !!}
        </div>
        <div class="form-group col-12 col-md-4">
            {!! Form::label('departamentoRegion', 'Departamento de investigación', ['class' => 'form-label']) !!}
            {!! Form::select(
                'departamentoRegion',
                ['0' => 'Seleccione un departamento'] +
                    collect($departamentos)->pluck('departamento', 'id')->toArray(),
                $investigacion->departamentoRegion,
                [
                    'class' => 'form-control',
                    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                ],
            ) !!}
        </div>
        <div class="form-group col-12 col-md-4">
            {!! Form::label('ciudadRegion', 'Municipio de investigación', ['class' => 'form-label']) !!}
            {!! Form::select(
                'ciudadRegion',
                ['0' => 'Seleccione un municipio'] + collect($municipio)->pluck('municipio', 'id')->toArray(),
                $investigacion->ciudadRegion,
                [
                    'class' => 'form-control',
                    'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
                ],
            ) !!}
        </div>
    </div>
</div>
@can('formulario.guardar-cambios')
    {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
@endcan
{!! Form::close() !!}
