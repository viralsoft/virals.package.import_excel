<?php

namespace ViralsBackpack\BackPackExcel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Jobs\ImportExcel;

class FirstSheetImport implements ToCollection, WithHeadingRow
{
    protected $excelFile;

    public function __construct($excelFile)
    {
        $this->excelFile = $excelFile;
    }

    public function collection(Collection $rows)
    {
        dispatch(new ImportExcel($rows, $this->excelFile));
        return true;
    }

    public function headingRow(): int
    {
        return 1;
    }

}
