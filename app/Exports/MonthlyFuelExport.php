<?php

namespace App\Exports;

use App\Models\FuelReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyFuelExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
   protected $month, $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $fuelReports = FuelReport::with('employee.designation')
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get();

        $mapped = collect();
        $index = 1;

        foreach ($fuelReports as $r) {
            $mapped->push([
                'S.No'              => $index++,
                'Employee Name'     => $r->employee->name ?? 'N/A',
                'Designation'       => $r->employee->designation->name ?? 'N/A',
                'MBL Account #'     => $r->employee->account_number ?? 'N/A',
                'D.O.J'             => $r->employee->joining_date ?? 'N/A',
                'Fuel Authorized'   => $r->fuel_authorized,
                'Reimbursement'     => $r->reimbursement,
            ]);
        }

        return $mapped;
    }

    public function headings(): array
    {
        return [
            [],
            [
                'S.No',
                'Employee Name',
                'Designation',
                'MBL Account #',
                'D.O.J',
                'Fuel Authorized (Ltrs)',
                'Total Reimbursement',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => 'center']],
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $monthName = date("F", mktime(0, 0, 0, $this->month, 1));
                $title = "Monthly Fuel Report for {$monthName} {$this->year}";
                $event->sheet->setCellValue('A1', $title);
                $event->sheet->mergeCells('A1:G1');
            },
        ];
    }
}

