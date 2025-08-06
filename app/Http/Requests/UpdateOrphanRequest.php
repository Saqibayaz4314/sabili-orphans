<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrphanRequest extends FormRequest
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

            // البيانات الاساسية
            'name' => ['sometimes' , 'string'],
            'id_number' => ['sometimes' , 'unique:orphans,id_number,except,id','digits:9'],
            'birth_date' => ['sometimes' , 'date'],
            'orphan_code' => ['sometimes' , 'unique:orphans,orphan_code,except,id'],
            'address' => ['sometimes' , 'string'],
            'gender' => ['sometimes' , 'in:ذكر,أنثى'],
            'health_status' => ['sometimes' , 'in:سليم,مريض'],
            'medical_report' => ['nullable' , 'image' , 'required_if:health_status,مريض' , 'dimensions:min_width=100,min_height=100','max:1048576'],

            // بيانات الأب
            'deceased_name' => ['sometimes' , 'string'],
            'deceased_id_number' => ['sometimes' , 'digits:9'],
            'death_deceased_date' => ['sometimes' , 'date'],
            'cause_deceased_death' => ['nullable' , 'in:شهيد,وفاة طبيعية'],
            'father_work' => ['nullable' , 'in:يعمل,لا يعمل'],
            'nature_father_work' => ['nullable' , 'in:موظف حكومي,موظف وكالة,عمل خاص'],
            'nature_work' => ['nullable' , 'string'],

            // بيانات الأم
            'mother_name' => ['sometimes','string'],
            'mother_id_number' => ['sometimes' , 'digits:9'],
            'mother_birth_date' => ['nullable' , 'date'],
            'mother_status' => ['sometimes' , 'in:على قيد الحياة- أرملة,على قيد الحياة- متزوجة زوج آخر,على قيد الحياة- مطلقة,شهيدة/ متوفية'],
            'mother_work' => ['nullable' , 'in:تعمل,لا تعمل'],
            'nature_mother_work' => ['nullable' , 'in:موظف حكومي,موظف وكالة,عمل خاص'],


            // بيانات الوكيل
            'guardian_name' => ['sometimes' , 'string'],
            'guardian_id_number' => ['sometimes' , 'digits:9'],
            'guardian_relation' => ['sometimes' , 'in:أم,أخ/ت,جد/ة,عم/ة,خال/ة,غير ذلك'],
            'guardian_anthor_relation' => ['nullable' , 'string' , 'required_if:guardian_relation,غير ذلك'],

            //بيانات التواصل
            'phone' => ['sometimes' , 'string'],
            'phone1' => ['sometimes' , 'string'],
            'email' => ['nullable' , 'email'],

            // بيانات استلام الكفالة
            'bank_name' => ['sometimes' , 'string'],
            'bank_account_owner' => ['sometimes' , 'string'],
            'bank_owner_id_number' => ['sometimes' , 'digits:9'],
            'phone_number_linked_bank' => ['sometimes' , 'string'],
            'bank_account_number' => ['sometimes' ,'digits_between:4,24'],
            'wallet_owner' => ['nullable' , 'string'],
            'wallet_owner_id_number' => ['nullable' , 'digits:9'],
            'owner_phone_linked_wallet' => ['nullable' , 'string'],

            // brother table
            'brother_name' => ['nullable', 'array'],
            'brother_name.*' => ['string', 'nullable'],

            'brother_id_number' => ['nullable', 'array'],
            'brother_id_number.*' => ['numeric', 'nullable', Rule::unique('brothers', 'brother_id_number')->ignore($this->id),'digits:9'],

            'brother_gender' => ['nullable', 'array'],
            'brother_gender.*' => ['string', 'nullable', 'in:ذكر,أنثى'],

            'brother_birth_date' => ['nullable', 'array'],
            'brother_birth_date.*' => ['date', 'nullable'],

            'brother_health_status' => ['nullable', 'array'],
            'brother_health_status.*' => ['nullable'  , 'in:مريض,سليم'],

            'brother_medical_report' => ['nullable', 'array'],
            'brother_medical_report.*' => ['image', 'nullable' , 'required_if:brother_health_status,مريض' , 'dimensions:min_width=100,min_height=100','max:1048576'],

            // images table
            'father_death_certificate' => ['sometimes' , 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],
            'wife_ID' => ['sometimes' , 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],
            'sponsor_ID' => ['sometimes' , 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],
            'birth_certificate' => ['sometimes' , 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],
            'personl_image' => ['sometimes' , 'image' , 'dimensions:min_width=100,min_height=100','max:1048576'],


        ];
    }
}
