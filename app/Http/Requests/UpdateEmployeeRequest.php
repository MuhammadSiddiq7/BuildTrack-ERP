<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'job_description' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,engaged',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
            'person_code' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'labour_card_number_issue_date' => 'nullable|string|max:255',
            'labour_card_number_expiry_date' => 'nullable|string|max:255',
            'labour_card_validity_days' => 'nullable|string|max:255',
            'labour_card_status' => 'nullable|in:valid,expired',
            'passport_number' => 'nullable|string|max:255',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date',
            'passport_validity_days' => 'nullable|string|max:255',
            'passport_status' => 'nullable|in:valid,expired',
            'visa_expiry_date' => 'nullable|string|max:255',
            'visa_validity_days' => 'nullable|string|max:255',
            'visa_status' => 'nullable|in:valid,expired',
            'emirates_id_number' => 'nullable|string|max:255',
            'emirates_id_expiry_date' => 'nullable|date',
            'emirates_id_validity_days' => 'nullable|string|max:255',
            'emirates_id_status' => 'nullable|in:valid,expired',
            'date_of_birth' => 'nullable|date',
            'iloe_insurance_date' => 'nullable|date',
            'medical_insurance_policy_date' => 'nullable|date',
            'valid_sira_cartificate_date' => 'nullable|date',
            'sira_card_number' => 'nullable|string|max:255',
            'sira_card_expiry_date' => 'nullable|date',
            'life_guard_license_number' => 'nullable|string|max:255',
            'life_guard_license_expiry_date' => 'nullable|date',
            'act_training_date' => 'nullable|date',
            'comment' => 'nullable|string',
        ];
    }
}
