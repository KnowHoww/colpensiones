<?php
namespace App\Exports;

use App\Models\Investigaciones;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Log;

class exportarFinalizadasHoy implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;
    protected $estadoInvestigacion;
    protected $tipoTramite;

    /**
     * Inicializa la clase con IDs opcionales.
     * @param array|null $ids
     */
    public function __construct(array $ids = null)
    {
        $this->ids = $ids;
        $this->estadoInvestigacion = "";
        $this->tipoTramite = "";
    }

    public function collection()
    {
        $query = Investigaciones::query();

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        } else {
            $startOfYesterday = now()->subDay()->startOfDay();
            $now = now();
            $query->whereBetween('FechaFinalizacion', [$startOfYesterday, $now]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'radicado',
            'estado',
            'tipoTramite'
        ];
    }

    public function map($investigaciones): array
    {
        $estadoInvestigacion = DB::table('states')
                                 ->where('id', $investigaciones->estado)
                                 ->value('name');

        $tipoTramite = $investigaciones->TipoTramite ?: "null";

        return [
            $investigaciones->id,
            $investigaciones->NumeroRadicacionCaso,
            $estadoInvestigacion,
            $tipoTramite
        ];
    }
}
