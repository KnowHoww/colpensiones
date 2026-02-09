@if ($secciones->contains('nombre', 'ConsultaBaseDatos'))
    <div class="section">
      <table class="table-body">
        <tbody>
          <tr>
            <th colspan="2" class="bg-primary titulos">CONSULTA BASES DE DATOS</th>
          </tr>
          <tr>
            <th colspan="2" class="roboto-bold titulos">CAUSANTE</th>
          </tr>
          @if ($AntecedentesCausante->adres == 12)
            <tr>
              <th>ADRES</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_adres }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->ruaf == 12)
            <tr>
              <th>RUAF</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_ruaf }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rues == 12)
            <tr>
              <th>RUES</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rues }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rnec == 12)
            <tr>
              <th>RNEC</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rnec }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->cufe == 12)
            <tr>
              <th>CUFE</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_cufe }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->sispro == 12)
            <tr>
              <th>SISPRO</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_sispro }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->rama_judicial == 12)
            <tr>
              <th>RAMA JUDICIAL</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_rama_judicial }}</td>
            </tr>
          @endif
          @if ($AntecedentesCausante->samai == 12)
            <tr>
              <th>SAMAI</th>
              <td class="text-break text-justify">{{ $AntecedentesCausante->observacion_samai }}</td>
            </tr>
          @endif
          <tr>
            <th colspan="2" class="roboto-bold titulos">SOLICITANTE(S)</th>
          </tr>
          @foreach ($antecedentesBeneficiarios as $item)
            <tr>
              <th class="roboto-bold-italic" colspan="2">{{ $item->PrimerNombre }}
                {{ $item->SegundoNombre }}
                {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</th>
            </tr>
            @if ($item->adres == 12)
              <tr>
                <th class="titulos">ADRES</th>
                <td class="text-break text-justify">{{ $item->observacion_adres }}</td>
              </tr>
            @endif
            @if ($item->ruaf == 12)
              <tr>
                <th class="titulos">RUAF</th>
                <td class="text-break text-justify">{{ $item->observacion_ruaf }}</td>
              </tr>
            @endif
            @if ($item->rues == 12)
              <tr>
                <th class="titulos">RUES</th>
                <td class="text-break text-justify">{{ $item->observacion_rues }}</td>
              </tr>
            @endif
            @if ($item->rnec == 12)
              <tr>
                <th class="titulos">RNEC</th>
                <td class="text-break text-justify">{{ $item->observacion_rnec }}</td>
              </tr>
            @endif
            @if ($item->cufe == 12)
              <tr>
                <th class="titulos">CUFE</th>
                <td class="text-break text-justify">{{ $item->observacion_cufe }}</td>
              </tr>
            @endif
            @if ($item->sispro == 12)
              <tr>
                <th class="titulos">SISPRO</th>
                <td class="text-break text-justify">{{ $item->observacion_sispro }}</td>
              </tr>
            @endif
            @if ($item->rama_judicial == 12)
              <tr>
                <th class="titulos">RAMA JUDICIAL</th>
                <td class="text-break text-justify">{{ $item->observacion_rama_judicial }}</td>
              </tr>
            @endif
            @if ($item->samai == 12)
              <tr>
                <th class="titulos">SAMAI</th>
                <td class="text-break text-justify">{{ $item->observacion_samai }}</td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  @endif