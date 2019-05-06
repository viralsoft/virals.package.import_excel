<?php

namespace ViralsLaravel\ImportRelationExcel\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

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
        if (isset($data['model'])) {
            $sheets[] = new ViralsSheetExport('model', collect(
                $data['model']
            ));
        }

        if (isset($data['relationship'])) {
            foreach ($data['relationship'] as $itemName => $item) {
                $sheets[] = new ViralsSheetExport($itemName, $item);
            }
        }
        return $sheets;
    }
}
