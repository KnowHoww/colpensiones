{!! Form::open(['route' => 'DocumentosAnexosStore', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
{{ Form::token() }}
<input type="hidden" name="id" value={{ $investigacion->id }}>
    <div class="form-group">
        {{ Form::label('inmuebles', 'Inmueble') }}
        {{ Form::file('inmuebles[]', ['multiple', 'class' => 'form-control']) }}
    </div>
<div class="form-group">
    {{ Form::label('servicios', 'Servicio público') }}
    {{ Form::file('servicios[]', ['multiple', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('pertenencias', 'Pertenencias del causante (carnet, documentos de identificación, documentos personales, entre otros)') }}
    {{ Form::file('pertenencias[]', ['multiple', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('clinica', 'Historia clínica') }}
    {{ Form::file('clinica[]', ['multiple', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('familiares', 'Fotografías familiares') }}
    {{ Form::file('familiares[]', ['multiple', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('investigador', 'Fotografía del investigador junto al solicitante') }}
    {{ Form::file('investigador[]', ['multiple', 'class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('basesdedatos', 'Cargar en el anexo las consultas realizadas') }}
    {{ Form::file('basesdedatos[]', ['multiple', 'class' => 'form-control']) }}
</div>
@if ($investigacion->estado == 5 || $investigacion->estado == 6 || $investigacion->estado == 11  || $investigacion->estado == 7   || $investigacion->estado == 8)
    <div class="form-group">
        {{ Form::submit('Subir archivos', ['class' => 'btn btn-primary']) }}
    </div>
@endif
{!! Form::close() !!}
