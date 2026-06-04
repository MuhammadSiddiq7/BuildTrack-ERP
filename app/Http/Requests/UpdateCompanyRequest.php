<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'established_date' => 'nullable|date',
            'website' => 'nullable|max:255',
            'visa_quota' => 'nullable',
            'status' => 'nullable|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'stamp' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'letter_head_header' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'letter_head_footer' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',

            // Contact Details
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|numeric|digits_between:0,15',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:50',
            'uan' => 'nullable|string|max:50',

            // License Details
            'license_type' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'place_of_issue' => 'nullable|string|max:255',
            'date_of_issue' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:place_of_issue|required_with:place_of_issue',
            'establishment_card_issue_date' => 'nullable|date',
            'establishment_card_expiry_date' => 'nullable|date|after_or_equal:establishment_card_issue_date|required_with:establishment_card_issue_date',
            'chamber_of_commerce_issue_date' => 'nullable|date',
            'chamber_of_commerce_expiry_date' => 'nullable|date|after_or_equal:chamber_of_commerce_issue_date|required_with:chamber_of_commerce_issue_date',
            'workmen_compensation_issue_date' => 'nullable|date',
            'workmen_compensation_expiry_date' => 'nullable|date|after_or_equal:workmen_compensation_issue_date|required_with:workmen_compensation_expiry_date',
            'medical_insurance_issue_date' => 'nullable|date',
            'medical_insurance_expiry_date' => 'nullable|date|after_or_equal:medical_insurance_issue_date|required_with:medical_insurance_issue_date',
            'sira_issue_date' => 'nullable|date',
            'sira_expiry_date' => 'nullable|date|after_or_equal:sira_issue_date|required_with:sira_issue_date',
        ];
    }
}
