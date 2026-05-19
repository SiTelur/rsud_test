<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID wajib diisi.',
            'product_id.exists' => 'Product tidak ditemukan.',
            'qty.required' => 'Jumlah (qty) wajib diisi.',
            'qty.integer' => 'Qty harus berupa angka bulat.',
            'qty.min' => 'Qty minimal 1.',
        ];
    }
}
