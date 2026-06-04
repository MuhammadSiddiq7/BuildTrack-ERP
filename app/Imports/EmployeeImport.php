<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Designation;
use App\Models\Project;
use App\Models\EmployeeDepartment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        // map lookup fields: designation, project, department
        $designation_id = $this->getIdFromNameOrId(Designation::class, $row['designation'] ?? null, 'name');
        $project_id = $this->getIdFromNameOrId(Project::class, $row['project'] ?? null, 'project_name');
        $dept_id = $this->getIdFromNameOrId(EmployeeDepartment::class, $row['employee_department'] ?? null, 'name');

        $employeeData = [
            'employee_id' => $row['employee_id'] ?? null,
            'name' => $row['name'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'designation_id' => $designation_id,
            'joining_date' => $this->parseDate($row['joining_date'] ?? null),
            'tenure' => $row['tenure'] ?? null,
            'cnic' => $row['cnic'] ?? null,
            'mobile_number' => $row['mobile_number'] ?? null,
            'account_number' => $row['account_number'] ?? null,
            'dob' => $this->parseDate($row['dob'] ?? null),
            'age' => $row['age'] ?? null,
            'education' => $row['education'] ?? null,
            'deployment_area' => $row['deployment_area'] ?? null,
            'project_id' => $project_id,
            'employee_department_id' => $dept_id,
            'employee_status' => isset($row['employee_status']) ? strtolower($row['employee_status']) : 'active',
            'comment' => $row['comment'] ?? null,
            // employee_picture not handled here (see notes)
        ];

        // If employee_id provided => updateOrCreate; otherwise create new
        if (!empty($employeeData['employee_id'])) {
            $employee = Employee::updateOrCreate(
                ['employee_id' => $employeeData['employee_id']],
                $employeeData
            );
        } else {
            $employee = Employee::create($employeeData);
        }

        // Salary (use relation to keep things consistent with your controller)
        $employee->salary()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'basic_salary' => $this->nullableNumber($row['basic_salary'] ?? null),
                'house_rent' => $this->nullableNumber($row['house_rent'] ?? null),
                'medical' => $this->nullableNumber($row['medical'] ?? null),
                'utilities' => $this->nullableNumber($row['utilities'] ?? null),
                'fuel_allowance_monthly' => $this->nullableNumber($row['fuel_allowance_monthly'] ?? null),
                'gross_salary' => $this->nullableNumber($row['gross_salary'] ?? null),
            ]
        );

        // Allowance
        $employee->allowance()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'company_vehicle' => $this->booleanValue($row['company_vehicle'] ?? null),
                'fuel_allowance_vehicle' => $this->nullableNumber($row['fuel_allowance_vehicle'] ?? null),
                'card_account' => $row['card_account'] ?? null,
                'calling_card' => $this->booleanValue($row['calling_card'] ?? null),
                'scale_level' => $row['scale_level'] ?? null,
            ]
        );

        // optional: log per employee (if you have logUserActivity helper)
        if (function_exists('logUserActivity')) {
            logUserActivity('Employee', 'Imported Employee ' . ($employee->name ?? 'N/A'), $employee->id, 'Employee');
        }

        return $employee;
    }

    public function rules(): array
    {
        // basic validation: require name. You can expand rules for other columns.
        return [
            '*.name' => 'required|string',
        ];
    }

    public function chunkSize(): int
    {
        return 500; // adjust if needed
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->toDateString();
            } catch (\Exception $e) {
                return null;
            }
        }
        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    private function nullableNumber($v)
    {
        if ($v === null || $v === '') return null;
        return (float) $v;
    }

    private function booleanValue($v)
    {
        if ($v === null || $v === '') return 0;
        $v = strtolower(trim((string)$v));
        return in_array($v, ['1','yes','y','true','t','on']) ? 1 : 0;
    }

    private function getIdFromNameOrId($modelClass, $value, $columnName = 'name')
    {
        if (!$value) return null;
        $value = trim($value);
        if (is_numeric($value)) {
            return (int) $value;
        }
        // create if not exists
        $record = $modelClass::firstOrCreate([$columnName => $value]);
        return $record->id;
    }
}
