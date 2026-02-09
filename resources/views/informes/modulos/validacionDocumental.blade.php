@if ($secciones->contains('nombre', 'ValidacionDocumental'))
    <div class="section">
      <table class="table-body">
        <tbody>
          <tr>
            <th colspan="2" class="bg-primary titulos">VALIDACIÓN DOCUMENTAL</th>
          </tr>
          <tr>
            <th colspan="2" class="roboto-bold titulos">VALIDACIÓN DOCUMENTAL DEL CAUSANTE</th>
          </tr>
          @if ($validacionDocumentalCausante->cedula != '')
            <tr>
              <td>CÉDULA DE CIUDADANÍA</td>
              <td class="text-break text-justify"><?php echo strip_tags($validacionDocumentalCausante->cedula); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->defuncion != '')
            <tr>
              <td>REGISTRO CIVIL DE DEFUNCIÓN</td>
              <td class="text-break text-justify"><?php echo strip_tags($validacionDocumentalCausante->defuncion); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->matrimonio != '')
            <tr>
              <td>REGISTRO CIVIL DE MATRIMONIO </td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->matrimonio); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->gastos_funebre != '')
            <tr>
              <td>EVIDENCIA GASTOS FÚNEBRES</td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->gastos_funebre); ?></td>
            </tr>
          @endif
          @if ($validacionDocumentalCausante->gastos_funerarios != '')
            <tr>
              <td>EVIDENCIA GASTOS FUNERARIOS</td>
              <td class="text-break text-justify"><?php echo clean_text1($validacionDocumentalCausante->gastos_funerarios); ?></td>
            </tr>
          @endif
          <tr>
            <th colspan="2" class="roboto-bold titulos">VALIDACIÓN DOCUMENTAL DEL SOLICITANTE</th>
          </tr>
          @foreach ($validacionDocumentalBeneficiarios as $item)
            <tr>
              <th class="roboto-bold-italic" colspan="2">{{ $item->PrimerNombre }}
                {{ $item->SegundoNombre }}
                {{ $item->PrimerApellido }} {{ $item->SegundoApellido }}</th>
            </tr>
            @if ($item->cedula != '')
              <tr>
                <th class="titulos">CÉDULA DE CIUDADANÍA</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->cedula); ?></td>
              </tr>
            @endif
            @if ($item->nacimiento != '')
              <tr>
                <th class="titulos">REGISTRO CIVIL DE NACIMIENTO</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->nacimiento); ?></td>
              </tr>
            @endif
            @if ($item->incapacidad != '')
              <tr>
                <th class="titulos">DICTAMEN MÉDICO DE INCAPACIDAD LABORAL </th>
                <td class="text-break text-justify"><?php echo clean_text1($item->incapacidad); ?></td>
              </tr>
            @endif
            @if ($item->escolaridad != '')
              <tr>
                <th class="titulos">CERTIFICADO DE ESCOLARIDAD</th>
                <td class="text-break text-justify"><?php echo clean_text1($item->escolaridad); ?></td>
              </tr>
            @endif
          @endforeach
        </tbody>
      </table>
    </div>
  @endif