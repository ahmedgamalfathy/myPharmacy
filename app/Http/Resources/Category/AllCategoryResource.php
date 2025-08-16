<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Category\SubCategory\SubCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 //name , location , address , status
        return [
            'categoryId' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'description' => $this->description,
            'path' => $this->image,
        ];
    }
}
