{!! Form::open(['route' => 'documentos.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
{{ Form::token() }}
<input type="hidden" name="id" value={{ $investigacion->id }}>
<div class="form-group">
    {{ Form::label('files', 'Seleccione archivos:') }}
    {{ Form::file('files[]', ['multiple', 'required', 'class' => 'form-control']) }}
</div>
@if (
    $investigacion->estado == 3 ||
        $investigacion->estado == 5 ||
        $investigacion->estado == 6 ||
        $investigacion->estado == 11 ||
        $investigacion->estado == 7 ||
        $investigacion->estado == 17 ||
        $investigacion->estado == 8)
    <div class="form-group">
        {{ Form::submit('Subir archivos', ['class' => 'btn btn-primary']) }}
    </div>
@endif
{!! Form::close() !!}
