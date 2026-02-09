{!! Form::model($auxilioFunerario, [
    'route' => ['auxilioFunerario.update', $auxilioFunerario],
    'method' => 'put',
]) !!}
{{ Form::token() }}
<div class="justify-content-around">
    <div class="form-group">
        {!! Form::label('valorGastosFunerarios', 'Valor Gastos Funerarios Solicitados ante Colpensiones', [
            'class' => 'form-label',
        ]) !!}
        {!! Form::text('valorGastosFunerarios', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('tipoPago', 'Tipo de servicio', ['class' => 'form-label']) !!}
        {!! Form::select('tipoPago', collect($facturaAuxilios)->pluck('name', 'id')->toArray(), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('personaSufrago', 'Persona que sufragó los gastos funerarios', ['class' => 'form-label']) !!}
        {!! Form::text('personaSufrago', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('detalleServicio', 'Detalle de servicios Funerarios', ['class' => 'form-label']) !!}
        {!! Form::textarea('detalleServicio', null, [
            'class' => 'form-control editor',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('cesionDerechos', 'Indique si realizo cesión de derechos', ['class' => 'form-label']) !!}
        {!! Form::select('cesionDerechos', collect($campoSino)->pluck('name', 'id')->toArray(), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label(
            'personaCesionDerechos',
            'Datos de la persona natural o jurídica a quien se le cedieron los derechos',
            ['class' => 'form-label'],
        ) !!}
        {!! Form::text('personaCesionDerechos', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('personaDocumento', 'Documento de Identidad', ['class' => 'form-label']) !!}
        {!! Form::text('personaDocumento', null, ['class' => 'form-control']) !!}
    </div>
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    @can('formulario.guardar-cambios')
        {{ Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) }}
    @endcan
@endif
{!! Form::close() !!}
