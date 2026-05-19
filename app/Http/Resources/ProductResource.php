<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_name' => $this->when(isset($this->category), fn () => $this->category->name),
            'price' => 'Rp '.number_format($this->price ?? 0, 0, ',', '.'),
            'stock' => $this->stock ?? 0,
            'is_active' => (int) ($this->is_active ?? 0),
        ];
    }
}
