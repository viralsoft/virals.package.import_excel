<?php

namespace ViralsBackpack\BackPackExcel\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersImport implements WithMultipleSheets
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $excelFile;

    /**
     * UsersImport constructor.
     * @param $excelFile
     */
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
