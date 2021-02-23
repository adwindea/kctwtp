<?php

namespace App\Imports;

use App\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PelangganImport implements ToCollection, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            \App\Models\Pelanggan::firstOrCreate(
                [
                    'idpel' => $row[0],
                    'no_meter' => $row[1]
                ],
                [
                    'nama' => $row[2],
                    'alamat' => $row[3],
                    'tarif' => $row[4],
                    'daya' => $row[5],
                    'krn_lama' => $row[6],
                    'vkrn_lama' => $row[7],
                    'krn' => $row[6],
                    'vkrn' => $row[7],
                    'kct1a' => $row[8],
                    'kct1b' => $row[9],
                    'kct2a' => $row[10],
                    'kct2b' => $row[11],
                    'pic' => $this->data
                ]
            );
        }
    }
}
