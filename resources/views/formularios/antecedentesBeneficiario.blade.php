{!! Form::model($beneficiario, [
    'route' => ['antecedentesbeneficiario.update', $beneficiario->id],
    'method' => 'put',
]) !!}
<div class="row">
    <div class="form-group col-12 col-md-6">
        {!! Form::label('adres', 'ADRES', ['class' => 'form-label']) !!}
        {!! Form::select('adres', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_adres', 'Observación ADRES', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_adres', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('ruaf', 'RUAF', ['class' => 'form-label']) !!}
        {!! Form::select('ruaf', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_ruaf', 'observacion RUAF', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_ruaf', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('rues', 'RUES', ['class' => 'form-label']) !!}
        {!! Form::select('rues', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_rues', 'observacion RUES', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_rues', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('rnec', 'RNEC', ['class' => 'form-label']) !!}
        {!! Form::select('rnec', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_rnec', 'observacion RNEC', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_rnec', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('cufe', 'CUFE', ['class' => 'form-label']) !!}
        {!! Form::select('cufe', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_cufe', 'observacion CUFE', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_cufe', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('sispro', 'SISPRO', ['class' => 'form-label']) !!}
        {!! Form::select('sispro', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_sispro', 'observacion sispro', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_sispro', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('rama_judicial', 'Rama judicial', ['class' => 'form-label']) !!}
        {!! Form::select('rama_judicial', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_rama_judicial', 'observacion Rama judicial', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_rama_judicial', null, [
            'class' => 'form-control',
            'rows' => 2,
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('samai', 'SAMAI', ['class' => 'form-label']) !!}
        {!! Form::select('samai', collect($campoSino)->pluck('name', 'id'), null, [
            'class' => 'form-control',
            'disabled' => Gate::allows('formulario.modificar-informacion') ? false : true,
        ]) !!}
    </div>
    <div class="form-group col-12 col-md-6">
        {!! Form::label('observacion_samai', 'observacion SAMAI', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion_samai', null, [
            'class' => 'form-control',
            'rows' => 2,
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
