<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class YearlyPayrollExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $fromYear;
    protected $toYear;

    public function __construct($fromYear, $toYear)
    {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
    }

    public function collection()
    {
        $payrolls = Payroll::with(['employee', 'companyBank', 'project'])
            ->whereBetween(DB::raw('YEAR(pay_date)'), [$this->fromYear, $this->toYear])
            ->get();
        // dd($payrolls);
        $mapped = collect();
        $index = 1;

        foreach ($payrolls as $p) {
            $mapped->push([
                'S.No'                => $index++,
                'Employee Name'       => $p->employee->name ?? 'N/A',
                'Father Name'         => $p->employee->father_name ?? 'N/A',
                'ID Number'           => $p->employee->employee_id ?? 'N/A',
                'Project'             => $p->project->project_name ?? 'N/A',
                'Bank'                => $p->companyBank->name ?? 'N/A',
                'Pay Date'            => $p->pay_date ?? '',
                'Basic Salary'        => $p->basic_salary,
                'Medical Allowance'   => $p->medical_allowance,
                'House Rent'          => $p->house_rent,
                'Utilities'           => $p->utilities,
                'Gross Salary'        => $p->gross_salary,
                'Arrears'             => $p->arrears,
                'Recovery'            => $p->recovery,
                'Security Deposit'    => $p->security_deposit,
                'Income Tax'          => $p->income_tax,
                'Absenteeism'         => $p->absenteeism,
                'Net Pay'             => $p->net_pay,
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
                'Father Name',
                'ID Number',
                'Project',
                'Bank',
                'Pay Date',
                'Basic Salary',
                'Medical Allowance',
                'House Rent',
                'Utilities',
                'Gross Salary',
                'Arrears',
                'Recovery',
                'Security Deposit',
                'Income Tax',
                'Absenteeism',
                'Net Pay',
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
                $title = "Yearly PayRoll Report for {$this->fromYear} to {$this->toYear}";
                $event->sheet->setCellValue('A1', $title);
                $event->sheet->mergeCells('A1:V1');
            },
        ];
    }
}

