<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'email|max:255',
            'rubric_id' => 'numeric|integer',
            'user_id' => 'numeric|integer',
            'limit' => 'numeric|max:200',
            'offset' => 'numeric|max:10000',
            'xml' => 'in:true,false'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge($this->route()->parameters());
    }

    public function messages()
    {
        return [
            'xml.in' => 'Xml property must be string with \'false\' or \'true\' value'
        ];
    }
}
