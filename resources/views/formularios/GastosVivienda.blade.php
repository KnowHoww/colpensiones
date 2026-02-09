{!! Form::model($gastosVivienda, ['route' => ['gastosvivienda.update', $gastosVivienda], 'method' => 'put']) !!}
{{ csrf_field() }}
<table class="table datatable table-bordered">
    <thead>
        <tr>
            <th colspan="2">Total gastos del hogar</th>
            <th>Aportes del afiliado a los gastos del hogar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Concepto</th>
            <th>Valor</th>
            <th>Valor</th>
        </tr>
        <tr>
            <td>Servicios Públicos</td>
            <td>{!! Form::number('serviciosPublicosValor', null, [
                'class' => 'form-control gasto',
                'id' => 'serviciosPublicosValor',
            ]) !!}</td>
            <td>{!! Form::number('serviciosPublicosValorAporte', null, [
                'class' => 'form-control gasto',
                'id' => 'serviciosPublicosValorAporte',
            ]) !!}</td>
        </tr>
        <tr>
            <td>Arriendo</td>
            <td>{!! Form::number('arriendoValor', null, ['class' => 'form-control gasto', 'id' => 'arriendoValor']) !!}</td>
            <td>{!! Form::number('arriendoValorAporte', null, ['class' => 'form-control gasto', 'id' => 'arriendoValorAporte']) !!}</td>
        </tr>
        <tr>
            <td>Mercado</td>
            <td>{!! Form::number('mercadoValor', null, ['class' => 'form-control gasto', 'id' => 'mercadoValor']) !!}</td>
            <td>{!! Form::number('mercadoValorAporte', null, ['class' => 'form-control gasto', 'id' => 'mercadoValorAporte']) !!}</td>
        </tr>
        <tr>
            <td>Otros</td>
            <td>{!! Form::number('otrosValor', null, ['class' => 'form-control gasto', 'id' => 'otrosValor']) !!}</td>
            <td>{!! Form::number('otrosValorAporte', null, ['class' => 'form-control gasto', 'id' => 'otrosValorAporte']) !!}</td>
        </tr>
        <tr>
            <td>Total</td>
            <td>{!! Form::number('totalValor', null, ['class' => 'form-control gasto', 'id' => 'totalValor']) !!}</td>
            <td>{!! Form::number('totalValorAporte', null, ['class' => 'form-control gasto', 'id' => 'totalValorAporte']) !!}</td>
        </tr>
    </tbody>
</table>
<div class="form-group col-12">
    {!! Form::label('observacion', 'Observación', ['class' => 'form-label']) !!}
    {!! Form::textarea('observacion', null, ['class' => 'form-control editor']) !!}
</div>

@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
