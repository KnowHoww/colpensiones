<?php

namespace App\Exports;

use App\Models\CentroCostos;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InformeInvestigacionesComisionResumen implements FromView
{
    public function view(): View
    {
        ini_set('memory_limit', '2048M');
          $sql = 'SELECT   ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) as nombre, ' .
				'informes_investigador.id AS idInforme , informes_investigador.valor 		' .	
				'FROM informes_investigador INNER JOIN ' .
				'users as Investigador ON Investigador.id = informes_investigador.idInvestigador ' .
				'WHERE informes_investigador.aceptado >0 ' .
				' and informes_investigador.Inicio  >= \'' . request('fecha_inicio') . '\' and informes_investigador.Fin <=\'' . request('fecha_fin') . '\'';

		$data = DB::Select($sql);
		
		 $sql2 = 'SELECT   ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) as nombre, ' .
				'informes_investigador.id AS idInforme , investigaciones.CasoPadreOriginal , investigaciones.id , 	' .	
				'investigaciones_comision.porBeneficiario , investigaciones_comision.doble , investigaciones_comision.AuxiliarCompleta, ' .
				'investigaciones_comision.ValorInvestigador , investigaciones_comision.ValorAuxiliar	' .			
				'FROM investigaciones INNER JOIN 	' .	
				'investigaciones_comision ON investigaciones_comision.idInvestigacion = investigaciones.id INNER JOIN 	' .	
				'informes_investigador ON (investigaciones_comision.idInformeInvestigador = informes_investigador.id OR investigaciones_comision.idInformeAuxiliar = informes_investigador.id) INNER JOIN	' .	
				'users AS investigador ON investigador.id = informes_investigador.idInvestigador	' .	
				'WHERE informes_investigador.aceptado >0  and informes_investigador.Inicio  >= \'' . request('fecha_inicio') . '\' and informes_investigador.Fin <=\'' . request('fecha_fin') . '\' '.
				'ORDER BY nombre, investigaciones_comision.idInformeInvestigador desc' ;

		$data2 = DB::Select($sql2);
		
		
        return view('exports.InformeInvestigacionesComisionResumen', ['data' => $data,'data2' => $data2]);
    }
}
