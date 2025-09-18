<?php

namespace App\Http\Resources\Product\Website;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Branch\Branch;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductMedia\ProductMediaResouce;
//
class AllProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->id,
            'name' => $this->name,
            'path'=>
            $this->firstProductMedia
            ? new ProductMediaResouce($this->firstProductMedia)
            : ($this->productMedia->isNotEmpty()
            ? ProductMediaResouce::collection($this->productMedia->take(1))
            : url('storage/ProductMedia/default-product.jpg')),
            'price' => $this->price,
            "userName"=>User::find($this->user_id)->name,
            "branchName"=>Branch::find($this->branch_id)->name,
            'status' => $this->status,
            'description' => $this->description,
        ];
    }//
}
