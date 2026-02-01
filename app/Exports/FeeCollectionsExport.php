<?php

namespace App\Exports;

use App\Models\FeeCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeeCollectionsExport implements FromCollection, WithHeadings
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return FeeCollection::with('student')
            ->where('month', $this->month)
            ->get()
            ->map(function($c){
                return [
                    'Receipt No'  => $c->receipt_no,
                    'Student'     => $c->student->name ?? 'N/A',
                    'Unique ID'   => $c->student->unique_id ?? '-',
                    'Class'       => $c->student->class ?? '-',
                    'Month'       => $c->month,
                    'Total'       => $c->total_amount,
                    'Paid'        => $c->paid_amount,
                    'Due'         => $c->due_amount,
                    'Paid Date'   => $c->paid_date->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Receipt No','Student','Unique ID','Class','Month','Total','Paid','Due','Paid Date'
        ];
    }
}
