<?php

namespace App\Exports;

use App\Models\Orphan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;



class ExportOrphansPdfImport implements FromCollection, WithHeadings
{

    protected $ids;
    protected $fields;

    public function __construct(array $ids , array $fields)
    {
        $this->ids = $ids;
        $this->fields = $fields;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $selectedFields = array_keys($this->fields);

        // نسترجع فقط الحقول المطلوبة
        return Orphan::whereIn('id', $this->ids)->get($selectedFields);
    }

    public function headings(): array
    {
        return array_values($this->fields);
    }
}
