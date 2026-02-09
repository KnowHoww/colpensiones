@if (optional($gastosVivienda)->totalValor != 0 && optional($gastosVivienda)->totalValor != null)
  <div>
    <table>
      <tbody>
        <tr>
          <th class="bg-primary titulos" colspan="3">GASTOS DE LA VIVIENDA</th>
        </tr>
        <tr>
          <th class="bg-primary" colspan="2">Total gastos del hogar</th>
          <th class="bg-primary">Aportes del afiliado a los gastos del hogar</th>
        </tr>
        <tr>
          <th class="text-center">Concepto</th>
          <th class="text-center">Valor</th>
          <th class="text-center">Valor</th>
        </tr>
        <tr>
          <th>Servicios Públicos</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->serviciosPublicosValor ?? 0) }}</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->serviciosPublicosValorAporte ?? 0) }}</th>
        </tr>
        <tr>
          <th>Arriendo</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->arriendoValor ?? 0) }}</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->arriendoValorAporte ?? 0) }}</th>
        </tr>
        <tr>
          <th>Mercado</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->mercadoValor ?? 0) }}</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->mercadoValorAporte ?? 0) }}</th>
        </tr>
        <tr>
          <th>Otros</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->otrosValor ?? 0) }}</th>
          <th class="text-center">{{ number_format(optional($gastosVivienda)->otrosValorAporte ?? 0) }}</th>
        </tr>
        <tr>
          <th class="text-center bg-primary">Total</th>
          <th class="text-center bg-primary">{{ number_format(optional($gastosVivienda)->totalValor ?? 0) }}</th>
          <th class="text-center bg-primary">{{ number_format(optional($gastosVivienda)->totalValorAporte ?? 0) }}</th>
        </tr>
        <tr>
          <td colspan="3">
            {!! strip_tags(optional($gastosVivienda)->observacion ?? '', '<b><i><br>') !!}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@endif
