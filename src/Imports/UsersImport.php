<?php

namespace ViralsBackpack\BackPackExcel\Imports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements WithMultipleSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $excelFile;

    public function __construct($excelFile)
    {
        $this->excelFile = $excelFile;
    }

    public function sheets(): array
    {
        return [
            new FirstSheetImport($this->excelFile)
        ];
    }

}
