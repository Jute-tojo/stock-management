<?php

namespace App\Http\Requests;

use App\Enums\StockMovement;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::enum(StockMovement::class)],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->type !== StockMovement::OUT->value) {
                return;
            }

            $product = Product::query()->findOrFail($this->product_id);

            if ($product->quantity < $this->quantity) {
                $validator->errors()->add(
                    'quantity',
                    "Insufficient stock. Only {$product->quantity} available.",
                );
            }
        });
    }
}
