<?php

namespace ViralsBackpack\BackPackExcel\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use ViralsBackpack\BackPackExcel\Exports\ViralsSheetExport;


class ViralsExport implements WithMultipleSheets
{
    use Exportable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $data = $this->data;
        $sheets = [];
        $sheets[] = new ViralsSheetExport('model', collect(
            $data['model']
        ));

        foreach ($data['relationship'] as $itemName => $item) {
            $sheets[] = new ViralsSheetExport($itemName, $item);
        }
        return $sheets;
    }
}
