<?php

namespace ViralsLaravel\ImportRelationExcel\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use ViralsLaravel\ImportRelationExcel\Jobs\ImportExcel;

class FirstSheetImport implements ToCollection, WithHeadingRow
{
    protected $excelFile;

    public function __construct($excelFile)
    {
        $this->excelFile = $excelFile;
    }

    /**
     * @param Collection $rows
     * @return bool
     */
    public function collection(Collection $rows)
    {
        dispatch(new ImportExcel($rows, $this->excelFile));
        return true;
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }
}
