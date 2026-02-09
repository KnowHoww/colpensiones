<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\ToArray;

class DataImport implements ToArray, WithCalculatedFormulas
{
    public function array(array $array)
    {
        // Aquí procesas y retornas el array como necesites
        return $array;
    }
}
