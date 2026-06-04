<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $index => $row) {

                $data = [
                    'company_id' => $row['company_id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'job_description' => $row['job_description'],
                    'contact' => $row['contact'],
                    'gender' => $row['gender'],
                    'marital_status' => $row['marital_status'],
                    'salary' => $row['salary'],
                    'joining_date' => $row['joining_date'],
                    'person_code' => $row['person_code'],
                    'id_number' => $row['id_number'],
                    'nationality' => $row['nationality'],
                    'position' => $row['position'],
                    'email' => $row['email'],
                    'labour_card_number_issue_date' => $row['labour_card_number_issue_date'],
                    'labour_card_number_expiry_date' => $row['labour_card_number_expiry_date'],
                    'labour_card_validity_days' => $row['labour_card_validity_days'],
                    'labour_card_status' => $row['labour_card_status'],
                    'passport_number' => $row['passport_number'],
                    'passport_issue_date' => $row['passport_issue_date'],
                    'passport_expiry_date' => $row['passport_expiry_date'],
                    'passport_validity_days' => $row['passport_validity_days'],
                    'passport_status' => $row['passport_status'],
                    'visa_expiry_date' => $row['visa_expiry_date'],
                    'visa_validity_days' => $row['visa_validity_days'],
                    'visa_status' => $row['visa_status'],
                    'emirates_id_number' => $row['emirates_id_number'],
                    'emirates_id_expiry_date' => $row['emirates_id_expiry_date'],
                    'emirates_id_validity_days' => $row['emirates_id_validity_days'],
                    'emirates_id_status' => $row['emirates_id_status'],
                    'date_of_birth' => $row['date_of_birth'],
                    'iloe_insurance_date' => $row['iloe_insurance_date'],
                    'medical_insurance_policy_date' => $row['medical_insurance_policy_date'],
                    'valid_sira_cartificate_date' => $row['valid_sira_cartificate_date'],
                    'sira_card_number' => $row['sira_card_number'],
                    'sira_card_expiry_date' => $row['sira_card_expiry_date'],
                    'life_guard_license_number' => $row['life_guard_license_number'],
                    'life_guard_license_expiry_date' => $row['life_guard_license_expiry_date'],
                    'act_training_date' => $row['act_training_date'],
                    'comment' => $row['comment'],
                ];

                Log::info('Processing row ' . ($index + 1) . ': ' . json_encode($data));
                Employee::create($data);
                Log::info('Employee created successfully for row ' . ($index + 1));
            }
        } catch (\Exception $e) {
            Log::error('Error saving employee on row ' . ($index + 1) . ': ' . $e->getMessage());
        }
    }
}
